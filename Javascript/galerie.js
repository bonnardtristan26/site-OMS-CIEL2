// Comportement basique de la maquette : clic sur une pastille de date
// pour changer l'état actif (à brancher plus tard sur un vrai filtrage).
document.addEventListener("DOMContentLoaded", function () {
  const pills = document.querySelectorAll(".oms-date-pill");
  const prevBtn = document.querySelector(".oms-date-arrow.prev");
  const nextBtn = document.querySelector(".oms-date-arrow.next");

  function setActive(pill) {
    pills.forEach(p => p.classList.remove("active"));
    pill.classList.add("active");
  }

  pills.forEach(pill => {
    pill.addEventListener("click", () => setActive(pill));
  });

  function moveActive(direction) {
    const current = document.querySelector(".oms-date-pill.active");
    const list = Array.from(pills);
    let index = list.indexOf(current);
    index = index + direction;
    if (index < 0) index = 0;
    if (index > list.length - 1) index = list.length - 1;
    setActive(list[index]);
  }

  if (prevBtn) prevBtn.addEventListener("click", () => moveActive(-1));
  if (nextBtn) nextBtn.addEventListener("click", () => moveActive(1));
});