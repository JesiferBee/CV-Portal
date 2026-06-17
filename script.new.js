document.addEventListener('DOMContentLoaded', function () {
    const slides = Array.from(document.querySelectorAll('.slide'));
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const menuToggle = document.getElementById('menuToggle');
    const siteNav = document.getElementById('siteNav');
    const scrollTop = document.getElementById('scrollTop');
    const pageLoader = document.getElementById('pageLoader');
    let activeIndex = 0;
    let slideInterval;

    function setActiveSlide(index) {
        if (!slides.length) {
            return;
        }
        activeIndex = (index + slides.length) % slides.length;
        slides.forEach((slide, slideIndex) => {
            slide.classList.toggle('active', slideIndex === activeIndex);
        });
    }

    function moveSlide(direction) {
        setActiveSlide(activeIndex + direction);
        resetSlideInterval();
    }

    function resetSlideInterval() {
        clearInterval(slideInterval);
        slideInterval = setInterval(() => moveSlide(1), 5000);
    }

    if (slides.length) {
        setActiveSlide(0);
        resetSlideInterval();
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => moveSlide(-1));
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => moveSlide(1));
    }

    if (menuToggle && siteNav) {
        menuToggle.addEventListener('click', () => {
            siteNav.classList.toggle('nav-active');
            menuToggle.classList.toggle('open');
        });
    }

    if (scrollTop) {
        window.addEventListener('scroll', () => {
            scrollTop.classList.toggle('visible', window.scrollY > 320);
        });

        scrollTop.addEventListener('click', (event) => {
            event.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    window.addEventListener('load', () => {
        if (pageLoader) {
            pageLoader.classList.add('loaded');
        }
    });
});
