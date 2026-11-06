// =====================================================================
//  OMS – Téléversement d'un dossier d'images dans une galerie
//  - glisser-déposer d'un dossier (sous-dossiers compris) ou sélection
//    via <input type="file" webkitdirectory>
//  - redimensionnement côté navigateur (max 1920 px) pour passer sous la
//    limite upload_max_filesize de PHP
//  - envoi image par image vers upload_galerie.php
// =====================================================================
const OMSGalerie = (function () {
  const TAILLE_MAX = 1920;           // côté le plus long, en pixels
  const POIDS_SANS_RETOUCHE = 1.5e6; // en dessous : envoyé tel quel
  const EXTENSIONS = /\.(jpe?g|png|gif|webp)$/i;

  function estImage(fichier) {
    return EXTENSIONS.test(fichier.name) || /^image\/(jpeg|png|gif|webp)$/.test(fichier.type);
  }

  // Parcourt récursivement un dossier déposé (API FileSystem Entry)
  function lireEntree(entree) {
    return new Promise(function (resolve) {
      if (entree.isFile) {
        entree.file(function (f) { resolve([f]); }, function () { resolve([]); });
        return;
      }
      if (!entree.isDirectory) { resolve([]); return; }
      const lecteur = entree.createReader();
      const enfants = [];
      (function lireLot() {
        // readEntries renvoie les entrées par paquets : on boucle jusqu'au vide
        lecteur.readEntries(function (lot) {
          if (!lot.length) {
            Promise.all(enfants.map(lireEntree)).then(function (listes) {
              resolve([].concat.apply([], listes));
            });
            return;
          }
          enfants.push.apply(enfants, lot);
          lireLot();
        }, function () { resolve([]); });
      })();
    });
  }

  async function fichiersDepuisDepot(dataTransfer) {
    const items = Array.from(dataTransfer.items || []);
    const entrees = items
      .map(function (it) { return it.webkitGetAsEntry ? it.webkitGetAsEntry() : null; })
      .filter(Boolean);
    let fichiers;
    if (entrees.length) {
      const listes = await Promise.all(entrees.map(lireEntree));
      fichiers = [].concat.apply([], listes);
    } else {
      fichiers = Array.from(dataTransfer.files || []);
    }
    return trier(fichiers.filter(estImage));
  }

  function trier(fichiers) {
    return fichiers.sort(function (a, b) {
      return a.name.localeCompare(b.name, 'fr', { numeric: true });
    });
  }

  function chargerImage(fichier) {
    return new Promise(function (resolve, reject) {
      const url = URL.createObjectURL(fichier);
      const img = new Image();
      img.onload = function () { URL.revokeObjectURL(url); resolve(img); };
      img.onerror = function () { URL.revokeObjectURL(url); reject(new Error('image illisible')); };
      img.src = url;
    });
  }

  // Réduit les photos trop grandes ; les GIF (animés) restent intacts
  async function redimensionner(fichier) {
    if (/gif$/i.test(fichier.type) || /\.gif$/i.test(fichier.name)) return fichier;
    let img;
    try { img = await chargerImage(fichier); } catch (e) { return fichier; }
    const ratio = Math.min(1, TAILLE_MAX / Math.max(img.naturalWidth, img.naturalHeight));
    if (ratio === 1 && fichier.size <= POIDS_SANS_RETOUCHE) return fichier;

    const canvas = document.createElement('canvas');
    canvas.width = Math.round(img.naturalWidth * ratio);
    canvas.height = Math.round(img.naturalHeight * ratio);
    const ctx = canvas.getContext('2d');
    ctx.fillStyle = '#fff'; // fond blanc pour les PNG transparents
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
    const blob = await new Promise(function (r) { canvas.toBlob(r, 'image/jpeg', 0.85); });
    if (!blob) return fichier;
    return new File([blob], fichier.name.replace(/\.[^.]+$/, '') + '.jpg', { type: 'image/jpeg' });
  }

  async function lireJson(reponse) {
    try { return await reponse.json(); } catch (e) {
      return { ok: false, erreur: 'réponse inattendue du serveur (session expirée ?)' };
    }
  }

  // Envoie les fichiers un par un ; onProgres(fait, total, fichier, resultat)
  async function televerser(idDossier, fichiers, onProgres) {
    const erreurs = [];
    let reussies = 0;
    for (let i = 0; i < fichiers.length; i++) {
      const original = fichiers[i];
      let resultat;
      try {
        const envoi = new FormData();
        envoi.append('id_dossier', idDossier);
        envoi.append('image', await redimensionner(original));
        const reponse = await fetch('upload_galerie.php', { method: 'POST', body: envoi });
        resultat = await lireJson(reponse);
      } catch (e) {
        resultat = { ok: false, erreur: 'connexion impossible' };
      }
      if (resultat.ok) reussies++;
      else erreurs.push(original.name + ' : ' + (resultat.erreur || 'erreur'));
      if (onProgres) onProgres(i + 1, fichiers.length, original, resultat);
    }
    return { reussies: reussies, erreurs: erreurs };
  }

  // Branche une zone de dépôt + un input fichier/dossier sur un callback
  function initZone(zone, input, surFichiers) {
    if (input) {
      input.addEventListener('change', function () {
        surFichiers(trier(Array.from(input.files).filter(estImage)));
        input.value = '';
      });
    }
    if (!zone) return;
    ['dragenter', 'dragover'].forEach(function (evt) {
      zone.addEventListener(evt, function (e) {
        e.preventDefault();
        zone.classList.add('survol');
      });
    });
    ['dragleave', 'drop'].forEach(function (evt) {
      zone.addEventListener(evt, function (e) {
        e.preventDefault();
        if (evt === 'dragleave' && zone.contains(e.relatedTarget)) return;
        zone.classList.remove('survol');
      });
    });
    zone.addEventListener('drop', async function (e) {
      surFichiers(await fichiersDepuisDepot(e.dataTransfer));
    });
  }

  return { initZone: initZone, televerser: televerser, redimensionner: redimensionner };
})();
