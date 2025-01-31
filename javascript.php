// JavaScript to toggle the navigation menu in mobile view
document.getElementById('menuToggle').addEventListener('click', function() {
    const navLinks = document.querySelector('.nav-links');
    navLinks.classList.toggle('active');
});

// Smooth scrolling to sections
function scrollToSection(sectionId) {
    const section = document.getElementById(sectionId);
    window.scrollTo({
        top: section.offsetTop - 50,
        behavior: 'smooth'
    });
}
