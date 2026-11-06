document.addEventListener("DOMContentLoaded", function () {
  const carousel = document.querySelector("[data-carousel]");

  if (!carousel) {
    return;
  }

  const slides = Array.from(carousel.querySelectorAll("[data-carousel-slide]"));
  const previousButton = carousel.querySelector("[data-carousel-prev]");
  const nextButton = carousel.querySelector("[data-carousel-next]");
  const status = document.querySelector("[data-carousel-status]");

  if (slides.length < 2) {
    return;
  }

  let activeIndex = 0;

  function showSlide(index) {
    activeIndex = (index + slides.length) % slides.length;

    slides.forEach(function (slide, slideIndex) {
      const isActive = slideIndex === activeIndex;
      slide.hidden = !isActive;
      slide.setAttribute("aria-hidden", String(!isActive));
    });

    if (status) {
      status.textContent = (activeIndex + 1) + " / " + slides.length;
    }
  }

  previousButton.addEventListener("click", function () {
    showSlide(activeIndex - 1);
  });

  nextButton.addEventListener("click", function () {
    showSlide(activeIndex + 1);
  });

  carousel.addEventListener("keydown", function (event) {
    if (event.key === "ArrowLeft") {
      event.preventDefault();
      showSlide(activeIndex - 1);
    } else if (event.key === "ArrowRight") {
      event.preventDefault();
      showSlide(activeIndex + 1);
    }
  });
});
