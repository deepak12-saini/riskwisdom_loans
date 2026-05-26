import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const headerWrap = document.querySelector('.rw-header__wrap');
    const toggle = document.querySelector('.rw-mobile-toggle');
    const mobileMenu = document.querySelector('.rw-mobile-menu');
    const contactSection = document.querySelector('#contact');
    const hasFormFeedback = Boolean(document.querySelector('.rw-form-alert'));

    if (contactSection && (window.location.hash === '#contact' || hasFormFeedback)) {
        requestAnimationFrame(() => {
            contactSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }

    if (!headerWrap || !toggle || !mobileMenu) {
        return;
    }

    const setMenuState = (isOpen) => {
        headerWrap.classList.toggle('is-mobile-open', isOpen);
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    };

    toggle.addEventListener('click', () => {
        setMenuState(!headerWrap.classList.contains('is-mobile-open'));
    });

    mobileMenu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => setMenuState(false));
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 1100) {
            setMenuState(false);
        }
    });
});
