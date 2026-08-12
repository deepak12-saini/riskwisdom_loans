<script>
    (function () {
        const hideBranding = @json(calendly_hide_branding());
        let booted = false;

        const hideLoader = (loader, mount) => {
            if (loader && !loader.hidden) {
                loader.hidden = true;
            }

            mount?.classList.add('is-ready');

            const badge = document.querySelector('[data-calendly-badge]');
            if (badge && !badge.dataset.ready) {
                badge.dataset.ready = '1';
                badge.textContent = 'Live availability';
            }
        };

        const watchIframeReady = (mount, loader) => {
            const bindIframe = (iframe) => {
                if (!iframe || iframe.dataset.rwBound) {
                    return;
                }

                iframe.dataset.rwBound = '1';
                iframe.addEventListener('load', () => hideLoader(loader, mount), { once: true });
            };

            const existing = mount.querySelector('iframe');
            if (existing) {
                bindIframe(existing);
                return;
            }

            const observer = new MutationObserver(() => {
                const iframe = mount.querySelector('iframe');
                if (iframe) {
                    bindIframe(iframe);
                    observer.disconnect();
                }
            });

            observer.observe(mount, { childList: true, subtree: true });
            window.setTimeout(() => observer.disconnect(), 15000);
        };

        const initBookCalendly = () => {
            const mount = document.getElementById('rw-calendly-mount');
            const loader = document.getElementById('rw-calendly-loader');
            const url = mount?.dataset.url?.trim() ?? '';

            if (!mount || !url || !window.Calendly || booted) {
                return Boolean(window.Calendly && mount && url && booted);
            }

            if (!window.Calendly) {
                return false;
            }

            booted = true;

            const options = {
                url,
                parentElement: mount,
                resize: true,
            };

            if (hideBranding) {
                options.branding = false;
            }

            window.Calendly.initInlineWidget(options);
            watchIframeReady(mount, loader);

            const onCalendlyMessage = (event) => {
                if (!event.origin?.includes('calendly.com')) {
                    return;
                }

                const eventName = event.data?.event ?? '';

                if (eventName.startsWith('calendly.')) {
                    hideLoader(loader, mount);
                }

                if (eventName === 'calendly.event_scheduled') {
                    mount.classList.add('is-scheduled');
                    mount.closest('.rw-book__embed-wrap')?.classList.add('is-scheduled');

                    if (typeof window.rwPushEvent === 'function') {
                        window.rwPushEvent('book_appointment', { lead_type: 'calendly' });
                    } else {
                        window.dataLayer = window.dataLayer || [];
                        window.dataLayer.push({ event: 'book_appointment', lead_type: 'calendly' });
                    }

                    if (typeof window.fbq === 'function') {
                        window.fbq('track', 'Schedule');
                    }
                }
            };

            window.addEventListener('message', onCalendlyMessage);
            window.setTimeout(() => hideLoader(loader, mount), 4500);

            return true;
        };

        const boot = () => {
            if (initBookCalendly()) {
                return;
            }

            let attempts = 0;
            const timer = window.setInterval(() => {
                attempts += 1;

                if (initBookCalendly() || attempts >= 80) {
                    window.clearInterval(timer);
                }
            }, 50);
        };

        // Start as soon as the mount exists — do not wait for full page load.
        if (document.getElementById('rw-calendly-mount')) {
            boot();
        } else if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', boot, { once: true });
        } else {
            boot();
        }

        // If widget.js finishes after our first poll window, still boot.
        window.addEventListener('load', () => {
            if (!booted) {
                boot();
            }
        }, { once: true });
    })();
</script>
