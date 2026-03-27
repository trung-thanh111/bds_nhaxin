(function ($) {
    "use strict";
    var filterProject = {};

    filterProject.execute = function () {
        filterProject.handleSort();
        filterProject.handleFilter();
        filterProject.handlePagination();
    };

    filterProject.handleSort = function () {
        $(document).on('click', '.ajax-sort', function (e) {
            e.preventDefault();
            const sortVal = $(this).data('sort');
            $('.hp-sort-list li').removeClass('uk-active');
            $(this).closest('li').addClass('uk-active');
            filterProject.sendRequest({ sort: sortVal });
        });
    };

    filterProject.handleFilter = function () {
        // Modal Apply
        $(document).on('submit', '#filter-form', function (e) {
            e.preventDefault();
            if (typeof UIkit !== 'undefined' && UIkit.modal('#modal-all-filters')) {
                UIkit.modal('#modal-all-filters').hide();
            }
            filterProject.sendRequest();
        });

        // Bar Apply
        $(document).on('click', '.hp-btn-apply-bar', function (e) {
            e.preventDefault();
            filterProject.sendRequest();
        });
    };

    filterProject.handlePagination = function () {
        $(document).on('click', '.ajax-pagination a', function (e) {
            e.preventDefault();
            const url = $(this).attr('href');
            if (url) {
                filterProject.sendRequest({}, url);
            }
        });
    };

    filterProject.sendRequest = function (extraData = {}, targetUrl = null) {
        const $container = $('#ajax-listing-container');
        if (!$container.length) return;

        $container.css('opacity', 0.5);

        let url = targetUrl || window.location.pathname;
        let formData = $('#filter-form').serializeArray();

        // Handle Sort
        let currentSort = extraData.sort;
        if (!currentSort) {
            const sortItem = formData.find(x => x.name === 'sort');
            currentSort = sortItem ? sortItem.value : new URLSearchParams(window.location.search).get('sort');
        }

        // Ensure sort is present and unique
        formData = formData.filter(x => x.name !== 'sort');
        if (currentSort) {
            formData.push({ name: 'sort', value: currentSort });
        }

        $.ajax({
            url: url,
            type: 'GET',
            data: $.param(formData),
            dataType: 'json',
            success: function (res) {
                if (res.html) {
                    $container.html(res.html);
                    $('#total-records').text(res.total);
                    if (res.sortLabel) {
                        $('#sort-label').text(res.sortLabel);
                    }
                    const newUrl = url + '?' + $.param(formData);
                    window.history.pushState({ path: newUrl }, '', newUrl);
                }
            },
            error: function (err) {
                console.error("AJAX Error (Project):", err);
            },
            complete: function () {
                $container.css('opacity', 1);
                $('html, body').animate({ scrollTop: $container.offset().top - 150 }, 500);
            }
        });
    };

    $(document).ready(function () {
        filterProject.execute();
    });

})(jQuery);
