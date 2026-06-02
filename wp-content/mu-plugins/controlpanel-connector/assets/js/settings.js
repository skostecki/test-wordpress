document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('controlpanel-settings-form');
    const notice = document.getElementById('controlpanel-settings-notice');

    if (!form || !notice) {
        return;
    }

    if (typeof controlpanelSettingsData === 'undefined') {
        console.error('Control Panel settings data not found');
        return;
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const submitButton = form.querySelector('[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
        }

        hideNotice();

        const body = {};
        form.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
            body[checkbox.name] = checkbox.checked;
        });

        fetch(controlpanelSettingsData.restUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': controlpanelSettingsData.nonce,
            },
            body: JSON.stringify(body),
        })
            .then(function (response) {
                return response.json().then(function (data) {
                    return { ok: response.ok, data: data };
                });
            })
            .then(function (result) {
                if (result.ok && result.data.success) {
                    applySettingsToDOM(body);
                    showNotice(controlpanelSettingsData.i18n.saved, 'success');
                } else {
                    const message = result.data.message || controlpanelSettingsData.i18n.error;
                    showNotice(message, 'error');
                }
            })
            .catch(function (error) {
                console.error('Error saving settings:', error);
                showNotice(controlpanelSettingsData.i18n.unknown, 'error');
            })
            .finally(function () {
                if (submitButton) {
                    submitButton.disabled = false;
                }
            });
    });

    function applySettingsToDOM(settings) {
        var barVisible = settings['bar_visible'];

        if (barVisible === false) {
            var bar = document.querySelector('#controlpanelbar');
            if (bar) {
                bar.remove();
                document.body.classList.remove('has-controlpanelbar');
                document.body.classList.remove('has-controlpanelbar-warning');
                document.body.classList.remove('has-controlpanelbar-danger');
            }
        } else if (barVisible === true && !document.querySelector('#controlpanelbar')) {
            // Bar was hidden but now enabled — reload to render it server-side
            window.location.reload();
        }
    }

    function showNotice(message, type) {
        notice.className = 'notice notice-' + type + ' is-dismissible';
        notice.innerHTML = '<p>' + message + '</p>';
        notice.style.display = 'block';
        notice.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function hideNotice() {
        notice.style.display = 'none';
        notice.className = 'notice is-dismissible';
        notice.innerHTML = '';
    }
});
