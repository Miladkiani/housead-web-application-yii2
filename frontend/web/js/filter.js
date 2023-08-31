'use strict';
$(document).ready(function () {

    function addCommas(str) {
        str = str.replace(/,/g, '');
        let objRegex = new RegExp('(-?[0-9]+)([0-9]{3})');
        while (objRegex.test(str)) {
            str = str.replace(objRegex, '$1,$2');
        }
        return str;
    }

    $(document).on("submit", "form[data-pjax-form]", function (e) {
        $.pjax.submit(e, {
            "push": true,
            "replace": false,
            "timeout": 1000,
            "scrollTo": false,
            "container": "#pjax_list_house"
        });
    });


    $(document).on("click", ".sidebar input[type='checkbox']", function () {
        $("#filter_form").submit();
    });

    $(document).on("click", ".sidebar input[type='radio']", function () {
        $("#filter_form").submit();
    });

    $('.house-overview .sidebar .filter-group-title').click(function () {
        if ($(this).hasClass('open')) {
            $(this).removeClass('open');
            $(this).siblings().removeClass('show');
        } else {
            $(this).addClass('open');
            $(this).siblings().addClass('show');
        }

    });

    $('.sidebar .dropdown').change(function () {
        $('.sidebar .filter-group-item-list-neighborhood').html('<div class="d-flex justify-content-center">\n' +
            '  <div class="spinner-grow spinner-grow-sm text-success m-2 " role="status">\n' +
            '    <span class="sr-only">Loading...</span>\n' +
            '  </div>\n' +
            '</div>');
        let cityId = $(this).val();
        $.post(
            yiiOptions.neighborhoodUrl,
            {
                id: cityId,
            },
            function (data) {
                let placeholder = $('.sidebar .filter-group-item-list-neighborhood');
                if ($.trim(data)) {
                    placeholder.html(
                        '<input type = "hidden" name = "HouseSearch[neighborhood_id]" value = "" >' +
                        '<div class="form-group highlight-addon field-housesearch-neighborhood_id"> ' +
                        '<div id = "housesearch-neighborhood_id" ></div>' +
                        '<div class="help-block invalid-feedback"></div>' +
                        '</div>');
                    let object = JSON.parse(data);
                    $.each(object, function (index, item) {
                        let input = '<div class="body1 form-check" > ' +
                            '<input type = "checkbox" id = "housesearch-neighborhood-id--' + item.id + '" class="form-check-input" name = "HouseSearch[neighborhood_id][]"' +
                            ' value = "' + item.id + '" data-index = "0"  > ' +
                            '<label class="form-check-label" for="housesearch-neighborhood-id--' + item.id + '" >' + item.name + '</label >' +
                            '</div>';
                        $('#housesearch-neighborhood_id').append(input);
                    });
                } else {
                    placeholder.html('<div class="alert alert-warning" role="alert">محله ای پیدا نشد</div>');
                }
            }
        )
    });

    function show_price_range(value) {
        let rent_lease = $('.sidebar .rent-lease');
        let sell_lease = $('.sidebar .sell-lease');
        if (value === '22') {
            if (!sell_lease.hasClass('show')) {
                sell_lease.addClass('show');
            }
            if (rent_lease.hasClass('show')) {
                rent_lease.removeClass('show');
            }
        } else if (value === '33') {
            if (!rent_lease.hasClass('show')) {
                rent_lease.addClass('show');
            }
            if (sell_lease.hasClass('show')) {
                sell_lease.removeClass('show');
            }
        }
    }

    let lease_type_radio_checked = $("input[name='HouseSearch[lease_type]']:checked");
    show_price_range(lease_type_radio_checked.val());

    $("input[name='HouseSearch[lease_type]']").on('change',function () {
        $('.sidebar .house-price').val('');
        show_price_range($(this).val());
    });


    $.get(
        yiiOptions.rangeUrl,
        function (data) {
            $('.range-slider-prepayment').slider({
                    min: data.min_prepayment,
                    max: data.max_prepayment,
                    values: [data.min_prepayment, data.max_prepayment],
                    range: true,
                    stop: function (event, ui) {
                        let min = ui.values[0];
                        let max = ui.values[1];
                        $('.range-label-min-prepayment')
                            .text(addCommas(min.toString()));
                        $('.range-label-max-prepayment')
                            .text(addCommas(max.toString()));
                        $('#housesearch-min_prepayment').val(min);
                        $('#housesearch-max_prepayment').val(max);
                        $("#filter_form").submit();
                    }
                }
            );

            $('.range-slider-rent').slider({
                    min: data.min_rent,
                    max: data.max_rent,
                    values: [data.min_rent, data.max_rent],
                    range: true,
                    stop: function (event, ui) {
                        let min = ui.values[0];
                        let max = ui.values[1];
                        $('.range-label-min-rent')
                            .text(addCommas(min.toString()));
                        $('.range-label-max-rent')
                            .text(addCommas(max.toString()));
                        $('#housesearch-min_rent').val(min);
                        $('#housesearch-max_rent').val(max);
                        $("#filter_form").submit();
                    }
                }
            );

            $('.range-slider-sell').slider({
                    min: data.min_sell,
                    max: data.max_sell,
                    values: [data.min_sell, data.max_sell],
                    range: true,
                    stop: function (event, ui) {
                        let min = ui.values[0];
                        let max = ui.values[1];
                        $('.range-label-min-sell')
                            .text(addCommas(min.toString()));
                        $('.range-label-max-sell')
                            .text(addCommas(max.toString()));
                        $('#housesearch-min_sell').val(min);
                        $('#housesearch-max_sell').val(max);
                        $("#filter_form").submit();
                    }
                }
            );
        }
    );

});