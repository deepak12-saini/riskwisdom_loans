<script>
    (() => {
        const form = document.querySelector('[data-user-form]');
        if (!form) return;

        const roleInput = form.querySelector('[data-role-select]');
        const permissions = form.querySelector('[data-staff-permissions]');
        const roleButtons = form.querySelectorAll('[data-role-option]');
        const presetBtn = form.querySelector('[data-perms-preset]');
        const clearBtn = form.querySelector('[data-perms-clear]');

        const syncPermissionStyles = () => {
            form.querySelectorAll('.rw-user-perm').forEach((label) => {
                const input = label.querySelector('[data-permission-checkbox]');
                if (!input) return;
                label.classList.toggle('is-on', input.checked && !input.disabled);
            });
        };

        const syncRole = () => {
            if (!roleInput || !permissions) return;
            const isStaff = roleInput.value === 'staff';
            permissions.hidden = !isStaff;
            permissions.classList.toggle('is-disabled', !isStaff);

            roleButtons.forEach((btn) => {
                const active = btn.getAttribute('data-role-option') === roleInput.value;
                btn.classList.toggle('is-active', active);
                btn.setAttribute('aria-pressed', active ? 'true' : 'false');
            });

            permissions.querySelectorAll('[data-permission-checkbox]').forEach((input) => {
                const locked = input.closest('.is-locked');
                input.disabled = !isStaff || !!locked;
            });

            syncPermissionStyles();
        };

        roleButtons.forEach((btn) => {
            btn.addEventListener('click', () => {
                if (!roleInput) return;
                roleInput.value = btn.getAttribute('data-role-option') || 'staff';
                syncRole();
            });
        });

        form.querySelectorAll('[data-permission-checkbox]').forEach((input) => {
            input.addEventListener('change', syncPermissionStyles);
        });

        presetBtn?.addEventListener('click', () => {
            form.querySelectorAll('[data-permission-checkbox]').forEach((input) => {
                if (input.disabled) return;
                input.checked = input.getAttribute('data-preset') === '1';
            });
            syncPermissionStyles();
        });

        clearBtn?.addEventListener('click', () => {
            form.querySelectorAll('[data-permission-checkbox]').forEach((input) => {
                if (input.disabled) return;
                input.checked = false;
            });
            syncPermissionStyles();
        });

        syncRole();
    })();
</script>
