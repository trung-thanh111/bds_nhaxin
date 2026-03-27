(function ($) {
    "use strict";
    var filterRE = {};

    filterRE.execute = function () {
        filterRE.handleSort();
        filterRE.handleFilter();
        filterRE.handlePagination();
    };

    filterRE.handleSort = function () {
        $(document).on('click', '.ajax-sort', function (e) {
            e.preventDefault();
            const sortVal = $(this).data('sort');
            $('.hp-sort-list li').removeClass('uk-active');
            $(this).closest('li').addClass('uk-active');
            filterRE.sendRequest({ sort: sortVal });
        });
    };

    filterRE.handleFilter = function () {
        // Modal Apply
        $(document).on('submit', '#filter-form', function (e) {
            e.preventDefault();
            if (typeof UIkit !== 'undefined' && UIkit.modal('#modal-all-filters')) {
                UIkit.modal('#modal-all-filters').hide();
            }
            filterRE.sendRequest();
        });

        // Bar Apply
        $(document).on('click', '.hp-btn-apply-bar', function (e) {
            e.preventDefault();
            filterRE.sendRequest();
        });
    };

    filterRE.handlePagination = function () {
        $(document).on('click', '.ajax-pagination a', function (e) {
            e.preventDefault();
            const url = $(this).attr('href');
            if (url) {
                filterRE.sendRequest({}, url);
            }
        });
    };

    filterRE.sendRequest = function (extraData = {}, targetUrl = null) {
        const $container = $('#ajax-listing-container');
        if (!$container.length) return;

        $container.css('opacity', 0.5);

        let url = targetUrl || window.location.pathname;
        let form = document.getElementById('filter-form');
        let formData = [];

        if (form) {
            formData = $(form.elements).filter(function() {
                let $el = $(this);
                if ($el.is(':disabled') || !this.name) return false;
                if ($el.is(':radio, :checkbox') && !$el.is(':checked')) return false;
                
                // Ensure we don't send inputs from the hidden location branch (Old vs New)
                // Use a more robust check that doesn't fail when Select2 hides the input itself
                let $locationMode = $el.closest('#gl-location-after-filter, #gl-location-before-filter');
                if ($locationMode.length && $locationMode.css('display') === 'none') return false;
                
                return true;
            }).serializeArray();
        }

        // Handle Sort
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
                console.error("AJAX Error (RE):", err);
            },
            complete: function () {
                $container.css('opacity', 1);
                $('html, body').animate({ scrollTop: $container.offset().top - 150 }, 500);
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
        filterRE.execute();
    });

})(jQuery);
