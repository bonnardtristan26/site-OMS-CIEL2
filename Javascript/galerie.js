document.addEventListener("DOMContentLoaded", function () {
  const dateButtons = Array.from(document.querySelectorAll("[data-gallery-date]"));
  const photos = Array.from(document.querySelectorAll("[data-photo-date]"));
  const previousButton = document.querySelector("[data-date-prev]");
  const nextButton = document.querySelector("[data-date-next]");
  const count = document.getElementById("galerie-compteur");

  if (dateButtons.length === 0 || photos.length === 0) {
    return;
  }

  const initialActiveDate = dateButtons.findIndex(function (button) {
    return button.classList.contains("active");
  });
  let activeIndex = initialActiveDate >= 0 ? initialActiveDate : 0;

  function showDate(index, updateUrl) {
    activeIndex = (index + dateButtons.length) % dateButtons.length;
    const activeDate = dateButtons[activeIndex].dataset.galleryDate;
    let visiblePhotoCount = 0;

    dateButtons.forEach(function (button, buttonIndex) {
      const isActive = buttonIndex === activeIndex;
      button.classList.toggle("active", isActive);
      if (isActive) {
        button.setAttribute("aria-current", "date");
      } else {
        button.removeAttribute("aria-current");
      }
    });

    photos.forEach(function (photo) {
      const isVisible = photo.dataset.photoDate === activeDate;
      photo.hidden = !isVisible;
      if (isVisible) {
        visiblePhotoCount += 1;
      }
    });

    if (count) {
      count.textContent = visiblePhotoCount + (visiblePhotoCount > 1 ? " photos" : " photo");
    }

    if (updateUrl) {
      const url = new URL(window.location.href);
      url.searchParams.set("date", activeDate);
      window.history.pushState({ date: activeDate }, "", url);
    }
  }

  dateButtons.forEach(function (button, index) {
    button.addEventListener("click", function (event) {
      event.preventDefault();
      showDate(index, true);
    });
  });

  if (previousButton && previousButton.tagName === "A") {
    previousButton.addEventListener("click", function (event) {
      event.preventDefault();
      showDate(activeIndex - 1, true);
    });
  }

  if (nextButton && nextButton.tagName === "A") {
    nextButton.addEventListener("click", function (event) {
      event.preventDefault();
      showDate(activeIndex + 1, true);
    });
  }

  window.addEventListener("popstate", function () {
    const urlDate = new URL(window.location.href).searchParams.get("date");
    const index = dateButtons.findIndex(function (button) {
      return button.dataset.galleryDate === urlDate;
    });
    showDate(index >= 0 ? index : 0, false);
  });

  showDate(activeIndex, false);
});
