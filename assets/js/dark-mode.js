jQuery(document).ready(function ($) {
    const toggleButton = $('<button>Toggle Dark Mode</button>').attr('id', 'dark-mode-toggle');
    $('body').prepend(toggleButton);

    toggleButton.on('click', function () {
        $('body').toggleClass('dark-mode');
        localStorage.setItem('darkMode', $('body').hasClass('dark-mode'));
    });

    if (localStorage.getItem('darkMode') === 'true') {
        $('body').addClass('dark-mode');
    }
});
