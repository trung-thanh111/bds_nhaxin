(function ($) {
    "use strict";
    var HT = {};

    HT.priceSuggestion = () => {
        $(document).on('keyup', '.price-input', function () {
            let _this = $(this);
            let val = _this.val().replace(/\./g, '');
            let suggestionBox = _this.parent().find('.price-suggestions');

            if (val == '' || isNaN(val)) {
                suggestionBox.hide().html('');
                return;
            }

            let num = parseInt(val);
            let suggestions = [
                num * 1000,
                num * 10000,
                num * 100000,
                num * 1000000,
                num * 10000000,
                num * 100000000,
                num * 1000000000,
                num * 10000000000,
                num * 100000000000,
            ];

            let html = '<ul class="list-group p-0 m-0">';
            suggestions.forEach(s => {
                if (s > 0 && s <= 1000000000000) { // Limit to 100 billion
                    let formatted = HT.formatNumber(s);
                    let text = HT.convertPriceToText(s);
                    html += `<li class="list-group-item suggestion-item cursor-pointer" data-value="${formatted}">
                        <strong>${formatted}</strong> <span class="text-danger">(${text})</span>
                    </li>`;
                }
            });
            html += '</ul>';

            suggestionBox.show().html(html);
        });

        $(document).on('click', '.suggestion-item', function () {
            let val = $(this).attr('data-value');
            let input = $(this).closest('.form-row').find('.price-input');
            input.val(val);
            $(this).closest('.price-suggestions').hide().html('');

            // Trigger change to update the readable text next to input
            input.trigger('input');
        });

        $(document).on('input', '.price-input', function () {
            let _this = $(this);
            let val = _this.val().replace(/\./g, '');
            let readable = _this.parent().find('.price-readable');
            if (val != '' && !isNaN(val)) {
                readable.text(HT.convertPriceToText(parseInt(val)));
            } else {
                readable.text('');
            }
        });

        // Close suggestion box when clicking outside
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.form-row').length) {
                $('.price-suggestions').hide();
            }
        });
    }

    HT.formatNumber = (n) => {
        return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    HT.convertPriceToText = (price) => {
        if (price < 1000000) return HT.formatNumber(price) + ' đ';
        if (price < 1000000000) {
            let trieu = price / 1000000;
            return trieu.toFixed(1).replace('.0', '') + ' triệu';
        }
        let ty = price / 1000000000;
        return ty.toFixed(2).replace('.00', '').replace('.0', '') + ' tỷ';
    }

    $(document).ready(function () {
        HT.priceSuggestion();
    });

})(jQuery);
