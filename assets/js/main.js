document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.querySelector('.menu-toggle');
    const nav = document.getElementById('site-nav');

    if (!toggle || !nav) return;

    function closeMenu() {
        nav.classList.remove('open');
        toggle.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('no-scroll');
    }

    function openMenu() {
        nav.classList.add('open');
        toggle.classList.add('open');
        toggle.setAttribute('aria-expanded', 'true');
        document.body.classList.add('no-scroll');
    }

    toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        if (nav.classList.contains('open')) closeMenu(); else openMenu();
    });

    // Close on link click
    nav.addEventListener('click', function (e) {
        if (e.target.tagName === 'A') closeMenu();
    });

    // Close on outside click
    document.addEventListener('click', function (e) {
        if (!nav.contains(e.target) && !toggle.contains(e.target)) closeMenu();
    });

    // Close on escape
    document.addEventListener('keyup', function (e) {
        if (e.key === 'Escape') closeMenu();
    });
});
