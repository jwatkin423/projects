$(function () {

    var storesChecked = [];
    var brandsChecked = [];

    brands();
    stores();

    function brands() {
        $('input:checkbox.chbx-brand:checked').each(function () {
            var tempBrand = $(this).val() + ';';
            var checkedString = '';
            var $brandsIds = $('#brands-ids');
            $brandsIds.val();
            // ensure that there are duplicates
            if (brandsChecked.indexOf(tempBrand) < 0) {
                brandsChecked.push(tempBrand);
            }
            // looped through each one that is checked
            $.each(brandsChecked, function (i, v) {
                checkedString += v;
            });
            // add to val
            $brandsIds.val(checkedString);
        });
    }

    function removeBrand(brand) {
        var index = brandsChecked.indexOf(brand + ";");
        var $brandsIds = $('#brands-ids');
        var checkedString = '';

        brandsChecked.splice(index, 1);
        $brandsIds.val(null);

        if (brandsChecked.length > 0) {
            $.each(brandsChecked, function (i, v) {
                checkedString += v;
            });
            // add to val
            $brandsIds.val(checkedString);
        }
    }

    $('.chbx-brand').change(function () {
        var ischecked = $(this).is(':checked');
        var tempBrand = $(this).val();
        if (ischecked) {
            brands();
        } else {
            removeBrand(tempBrand);
        }

    });

    function stores() {
        $('input:checkbox.chbx-store:checked').each(function () {
            var tempStore = $(this).val() + ';';
            var checkedString = '';
            var $storesIds = $('#stores-ids');
            $storesIds.val();
            // ensure that there aren't duplicates
            if (storesChecked.indexOf(tempStore) < 0) {
                storesChecked.push(tempStore);
            }
            // looped through each one that is checked
            $.each(storesChecked, function (i, v) {
                checkedString += v;
            });
            // add to val
            $storesIds.val(checkedString);
        });
    }

    function removeStore(store) {
        var index = storesChecked.indexOf(store + ";");
        var $storesIds = $('#stores-ids');
        var checkedString = '';

        storesChecked.splice(index, 1);
        $storesIds.val(null);

        if (storesChecked.length > 0) {
            $.each(storesChecked, function (i, v) {
                checkedString += v;
            });
            // add to val
            $storesIds.val(checkedString);
        }
    }

    $('.chbx-store').change(function () {
        var ischecked = $(this).is(':checked');
        var tempStore = $(this).val();

        if (ischecked) {
            stores();
        } else {
            removeStore(tempStore);
        }
    });

    // submit the form
    $('#search-main-all').submit(function () {
        submitForm();
    });

    // non-category filter
    $('#apply-filter').click(function () {
        submitForm();
    });

    // category filter
    $('#cat-apply-filter').click(function() {
        catSubmitForm();
    });

    // submit form function
    function submitForm() {
        $('#hidden_amount').val(minPrice);
        $('#hidden_amount_2').val(maxPrice);

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize()
        });
    }

    // submit form function
    function catSubmitForm() {
        $('#hidden_amount').val(minPrice);
        $('#hidden_amount_2').val(maxPrice);

        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize()
        });
    }

    // price range slider
    var priceSlider = document.getElementById('slider-range');
    var priceValues = [
        document.getElementById('amount'),
        document.getElementById('amount2')
    ];

    var min_price = parseInt(minPrice);
    var max_price = parseInt(maxPrice);

    function setMinMaxPrice() {
        if (min_price) {
            $('#hidden_amount').val(min_price);
        }

        if (max_price) {
            $('#hidden_amount_2').val(max_price);
        }
    }

    setMinMaxPrice();

    noUiSlider.create(priceSlider, {
        step: 5,
        start: [sel_min_price, sel_max_price],
        connect: true,
        range: {
            'min': min_price,
            'max': max_price
        },
        // Default formatting options
        format:  wNumb({
            decimals: 0,
            prefix: pre
        })
    });

    priceSlider.noUiSlider.on('update', function (values, handle) {
        if (handle === 0) {
            var minVal = parseInt(values[0].replace(/[^0-9]+/g, ''));
            $('#amount').val(minVal)
        }

        if (handle === 1) {
            var maxVal = parseInt(values[1].replace(/[^0-9]+/g, ''));
            $('#amount2').val(maxVal)
        }
    });


});