import './bootstrap';
import { initCalculatorTools } from './calculator-tools';

const pushEvent = (event, params = {}) => {
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({ event, ...params });
};

document.addEventListener('DOMContentLoaded', () => {
    const headerWrap = document.querySelector('.rw-header__wrap');
    const toggle = document.querySelector('.rw-mobile-toggle');
    const mobileMenu = document.querySelector('.rw-mobile-menu');
    const contactSection = document.querySelector('#contact');
    const hasFormFeedback = Boolean(document.querySelector('.rw-form-alert'));
    const stickyCta = document.getElementById('rw-sticky-cta');
    const contactForm = document.getElementById('contact-form');

    const params = new URLSearchParams(window.location.search);
    const intent = params.get('intent');

    if (intent && contactForm) {
        const sourceInput = contactForm.querySelector('input[name="source"]');
        if (sourceInput && !sourceInput.value) {
            sourceInput.value = intent;
        }
    }

    if (contactSection && (window.location.hash === '#contact' || hasFormFeedback || intent)) {
        requestAnimationFrame(() => {
            contactSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }

    if (headerWrap && toggle && mobileMenu) {
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
    }

    if (stickyCta) {
        const isHomePage = document.body.classList.contains('rw-theme') && document.querySelector('.rw-home');
        const scrollThreshold = window.innerWidth <= 900 ? (isHomePage ? 150 : 0) : 500;

        const showSticky = () => {
            const scrolled = window.scrollY > scrollThreshold;
            const contactVisible = contactSection && isHomePage
                ? contactSection.getBoundingClientRect().top < window.innerHeight * 0.75
                : false;

            stickyCta.hidden = !scrolled || contactVisible;
            document.body.classList.toggle('has-sticky-cta', !stickyCta.hidden);
        };

        showSticky();
        window.addEventListener('scroll', showSticky, { passive: true });
        window.addEventListener('resize', showSticky, { passive: true });
    }

    document.querySelectorAll('[data-cta]').forEach((element) => {
        element.addEventListener('click', () => {
            pushEvent('cta_click', {
                cta_id: element.getAttribute('data-cta'),
                cta_text: element.textContent?.trim() ?? '',
            });
        });
    });

    document.querySelectorAll('.rw-track-phone').forEach((element) => {
        element.addEventListener('click', () => {
            pushEvent('click_phone', {
                phone: element.getAttribute('href')?.replace('tel:', '') ?? '',
            });
        });
    });

    if (contactForm) {
        let formStarted = false;
        contactForm.querySelectorAll('input, select, textarea').forEach((field) => {
            field.addEventListener('focus', () => {
                if (!formStarted) {
                    formStarted = true;
                    pushEvent('form_start', { form_name: 'contact' });
                }
            }, { once: true });
        });
    }

    document.querySelectorAll('.js-book-chat').forEach((element) => {
        element.addEventListener('click', () => {
            pushEvent('book_chat_click', {
                cta_id: element.getAttribute('data-cta') ?? '',
                destination: element.getAttribute('href') ?? '',
            });
        });
    });

    document.querySelectorAll('[data-track-form]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (form.dataset.submitting === 'true') {
                event.preventDefault();

                return;
            }

            pushEvent('form_submit', {
                form_name: form.getAttribute('data-track-form') ?? 'unknown',
            });

            form.dataset.submitting = 'true';
            form.classList.add('is-submitting');
            form.setAttribute('aria-busy', 'true');

            const submitButton = form.querySelector('button[type="submit"]');

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.setAttribute('aria-busy', 'true');

                if (!submitButton.dataset.submitLabel) {
                    submitButton.dataset.submitLabel = submitButton.textContent.trim();
                }

                const loadingText = submitButton.dataset.loadingText ?? 'Sending…';
                submitButton.textContent = loadingText;
            }
        });
    });

    if (document.querySelector('[data-lead-conversion]')) {
        const conversion = document.querySelector('[data-lead-conversion]');
        pushEvent('form_submit', {
            form_name: conversion.getAttribute('data-lead-conversion') ?? 'calculator',
        });
    }

    initCalculatorTools();
});
