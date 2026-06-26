const hideLoader = (loader, mount) => {
    if (loader) {
        loader.hidden = true;
    }

    mount?.classList.add('is-ready');
};

const initBookCalendly = () => {
    const mount = document.getElementById('rw-calendly-mount');
    const loader = document.getElementById('rw-calendly-loader');
    const url = mount?.dataset.url?.trim() ?? '';

    if (!mount || !url || !window.Calendly) {
        return false;
    }

    window.Calendly.initInlineWidget({
        url,
        parentElement: mount,
        resize: true,
    });

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
        }
    };

    window.addEventListener('message', onCalendlyMessage);

    window.setTimeout(() => hideLoader(loader, mount), 6000);

    return true;
};

const boot = () => {
    if (initBookCalendly()) {
        return;
    }

    let attempts = 0;
    const timer = window.setInterval(() => {
        attempts += 1;

        if (initBookCalendly() || attempts >= 40) {
            window.clearInterval(timer);
        }
    }, 100);
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}
