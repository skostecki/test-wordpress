document.addEventListener('DOMContentLoaded', function () {
    // Add class to body when control panel bar is present
    const bar = document.querySelector('.controlpanelbar');
    if (bar) {
        document.body.classList.add('has-controlpanelbar');

        if (bar.classList.contains('controlpanelbar-warning')) {
            document.body.classList.add('has-controlpanelbar-warning');
        } else if (bar.classList.contains('controlpanelbar-danger')) {
            document.body.classList.add('has-controlpanelbar-danger');
        }
    }

    const dismissButton = document.querySelector('.controlpanelbar-dismiss-btn');

    if (!dismissButton) {
        return;
    }

    dismissButton.addEventListener('click', function (e) {
        e.preventDefault();

        if (typeof controlpanelBarData === 'undefined') {
            console.error('Control Panel Bar data not found');
            return;
        }

        fetch(controlpanelBarData.restUrl, {
            method: 'POST',
            headers: {
                'X-WP-Nonce': controlpanelBarData.nonce,
            },
        })
            .then(function (response) {
                return response.json().then(function (data) {
                    return { ok: response.ok, data: data };
                });
            })
            .then(function (result) {
                if (result.ok && result.data.success) {
                    const bar = document.querySelector('#controlpanelbar');
                    if (bar) {
                        bar.classList.add('controlpanelbar-hiding');

                        setTimeout(function () {
                            bar.remove();
                            document.body.classList.remove('has-controlpanelbar');
                            document.body.classList.remove('has-controlpanelbar-warning');
                            document.body.classList.remove('has-controlpanelbar-danger');
                        }, 400);
                    }
                } else {
                    console.error('Failed to dismiss bar:', result.data.message || result.data.code);
                }
            })
            .catch(function (error) {
                console.error('Error dismissing bar:', error);
            });
    });
});
