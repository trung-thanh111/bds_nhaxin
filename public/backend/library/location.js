(function($) {
    "use strict";

    window.HT = window.HT || {};
    var HT = window.HT;

    if (HT.locationEventsRegistered) return;

    HT.getLocation = function () {
        $(document).on('change', '.location', function (e) {
            if (e.namespace === 'select2') return;

            var _this = $(this);
            var currentVal = _this.val();
            var lastVal = _this.data('last-ajax-val');

            if (lastVal === currentVal && currentVal != '0') return;

            var target = _this.attr('data-target');
            var source = _this.attr('data-source') || 'db';

            HT.resetDependentDropdowns(target);
            _this.data('last-ajax-val', currentVal);

            if (currentVal != '0' && currentVal != '') {
                HT.sendDataTogetLocation({
                    data: { location_id: currentVal },
                    target: target,
                    source: source,
                });
            }
        });
        
        HT.locationEventsRegistered = true;
    };

    HT.resetDependentDropdowns = function (target) {
        if (target === 'old_districts') {
            var defaultOption = '<option value="0">[Chọn Phường/Xã]</option>';
            $('.old_wards').html(defaultOption);
            if ($.fn.select2) $('.old_wards').trigger('change.select2');
        }
    };

    HT.sendDataTogetLocation = function (option) {
        $.ajax({
            url: '/ajax/location/getLocation',
            type: 'GET',
            data: {
                'data[location_id]': option.data.location_id,
                'target': option.target,
                'source': option.source || 'db'
            },
            dataType: 'json',
            success: function (res) {
                var $target = $('.' + option.target);
                $target.html(res.html);

                var valToSet = null;
                if (option.target === 'wards') valToSet = window.ward_code;
                if (option.target === 'old_districts') valToSet = window.old_district_code;
                if (option.target === 'old_wards') valToSet = window.old_ward_code;

                if (valToSet && valToSet != '0') {
                    $target.val(valToSet);
                }

                if ($.fn.select2) {
                    $target.trigger('change.select2');
                }
                
                if ($target.hasClass('location')) {
                    var actualVal = $target.val();
                    if (actualVal && actualVal != '0') {
                        setTimeout(function() {
                            $target.trigger('change');
                        }, 50);
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('[Location] Error:', textStatus, errorThrown);
            }
        });
    };

    HT.loadCity = function () {
        setTimeout(function () {
            if (window.province_code && window.province_code != '0') {
                $('[name=province_code]').trigger('change');
            }
            if (window.old_province_code && window.old_province_code != '0') {
                $('[name=old_province_code]').trigger('change');
            }
        }, 500);
    };

    $(document).ready(function() {
        HT.getLocation();
        HT.loadCity();
    });

})(jQuery);