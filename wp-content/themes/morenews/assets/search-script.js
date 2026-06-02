jQuery(document).ready(function($) {

    if (!afLiveSearch.enabled) return;

    let timer;
    const resultsBox = $('#af-live-search-results');
    const searchInput = $('input[type="search"]');

    let items = [];
    let index = -1;

    function resetNavigation() {
        items = resultsBox.find('[role="option"]');
        index = -1;
        items.attr('aria-selected', 'false');
    }

    searchInput.on('keyup', function (e) {

        // Keyboard navigation
        if (e.key === 'ArrowDown' && items.length) {
            index = (index + 1) % items.length;
            items.attr('aria-selected', 'false')
                .eq(index).attr('aria-selected', 'true').focus();
            return;
        }

        if (e.key === 'ArrowUp' && items.length) {
            index = (index - 1 + items.length) % items.length;
            items.attr('aria-selected', 'false')
                .eq(index).attr('aria-selected', 'true').focus();
            return;
        }

        if (e.key === 'Enter' && index >= 0) {
            window.location = items.eq(index).find('a').attr('href');
            return;
        }

        // Regular typing
        const query = $(this).val().trim();
        clearTimeout(timer);

        timer = setTimeout(function() {

            if (query.length < 2) {
                resultsBox.html('').attr('aria-expanded', 'false');
                return;
            }

            $.ajax({
                url: afLiveSearch.ajax_url,
                type: 'POST',
                data: {
                    action: 'morenews_live_search',
                    s: query,
                    nonce: afLiveSearch.nonce,
                    limit: afLiveSearch.results_count
                },
                beforeSend: function () {
                    resultsBox
                        .html('<div class="loading" role="status">' + afLiveSearch.searching_text + '</div>')
                        .attr('aria-expanded', 'true');
                },
                success: function (response) {
                    resultsBox.html(response).attr('aria-expanded', 'true');
                    resetNavigation();
                }
            });

        }, 300);
    });

    // Close when clicking outside
    $(document).on('click', function(e){
        if (!$(e.target).closest('.af-search-wrap').length) {
            resultsBox.html('').attr('aria-expanded', 'false');
        }
    });
});
