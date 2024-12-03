document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.querySelector('.menu-toggle');
    const menu = document.querySelector('#primary-menu');

    if (menuToggle && menu) {
        menuToggle.addEventListener('click', function () {
            menu.classList.toggle('active');
            const expanded = menuToggle.getAttribute('aria-expanded') === 'true';
            menuToggle.setAttribute('aria-expanded', !expanded);
        });
    }
});
