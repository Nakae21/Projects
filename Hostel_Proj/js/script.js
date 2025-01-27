// Wait until the DOM content is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    const menuIcon = document.querySelector('.menu-icon');
    const navMenu = document.querySelector('header nav ul');

    // Check if both elements are present
    if (menuIcon && navMenu) {
        menuIcon.addEventListener('click', () => {
            navMenu.classList.toggle('open');
            menuIcon.classList.toggle('opened');
        });
    }

    // Slider functionality for the welcome section
    let currentIndex = 0;
    const slides = document.querySelectorAll('.slider img');
    const totalSlides = slides.length;

    // Function to show the next slide
    function showNextSlide() {
        slides[currentIndex].style.opacity = 0; // Hide current slide
        currentIndex = (currentIndex + 1) % totalSlides; // Move to the next slide
        slides[currentIndex].style.opacity = 1; // Show the next slide
    }

    // Change slide every 3 seconds
    setInterval(showNextSlide, 3000);

    // Initialize the first slide visibility
    slides[currentIndex].style.opacity = 1;
});
