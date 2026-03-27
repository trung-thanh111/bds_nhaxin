(function ($) {
    "use strict";
    var filter = {};

    filter.execute = function () {
        filter.handleSort();
        filter.handleFilter();
        filter.handlePagination();
    };

    filter.handleSort = function () {
        $(document).on('click', '.ajax-sort', function (e) {
            e.preventDefault();
            const sortVal = $(this).data('sort');
            const $btn = $(this).closest('.hp-sort-dropdown').find('.hp-sort-btn');

            // Update UI immediately (optional but smoother)
            $('.hp-sort-list li').removeClass('uk-active');
            $(this).closest('li').addClass('uk-active');

            filter.sendRequest({ sort: sortVal });
        });
    };

    filter.handleFilter = function () {
        // Modal Apply
        $(document).on('submit', '#filter-form', function (e) {
            e.preventDefault();
            UIkit.modal('#modal-all-filters').hide();
            filter.sendRequest();
        });

        // Bar Apply
        $(document).on('click', '.hp-btn-apply-bar', function (e) {
            e.preventDefault();
            filter.sendRequest();
        });
    };

    filter.handlePagination = function () {
        $(document).on('click', '.ajax-pagination a', function (e) {
            e.preventDefault();
            const url = $(this).attr('href');
            if (url) {
                filter.sendRequest({}, url);
            }
        });
    };

    filter.sendRequest = function (extraData = {}, targetUrl = null) {
        const $container = $('#ajax-listing-container');
        const $loader = $('<div class="uk-text-center uk-margin-large-top"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');

        $container.css('opacity', 0.5);

        let url = targetUrl || window.location.pathname;
        let form = document.getElementById('filter-form');
        let formData = [];
        
        if (form) {
            // Use native form.elements which automatically includes elements with form="filter-form"
            // and filter out disabled elements and unchecked radios/checkboxes
            formData = $(form.elements).filter(function() {
                let $el = $(this);
                if ($el.is(':disabled') || !this.name) return false;
                if ($el.is(':radio, :checkbox') && !$el.is(':checked')) return false;
                
                // Ensure we don't send inputs from the hidden location branch (Old vs New)
                let $locationMode = $el.closest('#gl-location-after-filter, #gl-location-before-filter');
                if ($locationMode.length && $locationMode.css('display') === 'none') return false;
                
                return true;
            }).serializeArray();
        }

        if (extraData.sort) {
            let found = false;
            for (let i = 0; i < formData.length; i++) {
                if (formData[i].name === 'sort') {
                    formData[i].value = extraData.sort;
                    found = true;
                    break;
                }
            }
            if (!found) formData.push({ name: 'sort', value: extraData.sort });
        } else {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('sort') && !formData.find(x => x.name === 'sort')) {
                formData.push({ name: 'sort', value: urlParams.get('sort') });
            }
        }

        // Add a dummy param for AJAX to differentiate it from the page URL in browser cache
        let ajaxData = [...formData];
        ajaxData.push({ name: '_ajax', value: 1 });

        $.ajax({
            url: url,
            type: 'GET',
            data: ajaxData,
            dataType: 'json',
            success: function (res) {
                if (res.html) {
                    $container.html(res.html);
                    $('#total-records').text(res.total);
                    if (res.sortLabel) {
                        $('#sort-label').text(res.sortLabel);
                    }

                    const newUrl = url + '?' + $.param(formData);
                    // Push the CLEAN URL (without _ajax) to the history
                    window.history.pushState({ path: newUrl, ajax: true }, '', newUrl);
                }
            },
            error: function (err) {
                console.error("AJAX Error:", err);
            },
            complete: function () {
                $container.css('opacity', 1);
                $('html, body').animate({ scrollTop: $('#ajax-listing-container').offset().top - 150 }, 500);
            }
        });
    };

    // Handle Back/Forward buttons
    window.onpopstate = function(event) {
        if (event.state && event.state.ajax) {
            window.location.reload();
        }
    };

    $(document).ready(function () {
        filter.execute();
    });

})(jQuery);
