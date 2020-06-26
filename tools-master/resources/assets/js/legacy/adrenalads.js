function formatDollar(num) {
    var p = num.toFixed(2).split(".");
    return "$" + p[0].split("").reverse().reduce(function(acc, num, i, orig) {
        return  num + (i && !(i % 3) ? "," : "") + acc;
    }, "") + "." + p[1];
}

function commify(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

$(function() {
    $('.datepicker').each(function() {
        var datepicker = $(this).datepicker().on('changeDate', function(ev) {
            datepicker.hide();
        }).data('datepicker');
    });

    /* ---------- Datable ---------- */
    $('.datatable').dataTable({
        "sDom": "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-lg-12'i><'col-lg-12 center'p>>",
        "sPaginationType": "bootstrap",
        'bAutoWidth': false,
        "bPaginate" : false,
        "bInfo" : false,
        "bFilter" : false,
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page"
        }
    });

    $('.alerts-table tr[data-href]').click(function() {
        var href = $(this).data('href');
        location.href = href;
    });


    //Campaign type tabs
    (function() {
        var input = $("#campaign_type"),
            value = input.val(),
            context = $("#campaign-type-from-group");

        if( value ) {
            var selector = "a[href$=#" + value + "]";
            $(selector, context).tab("show");
        }
        $(".nav-tabs a", context).click(function(e) {
            e.preventDefault();
            $(this).tab("show");

            var type = $($(this).attr('href')).attr('id');
            input.val(type);
        });

        //Pacing factor initial value
        if ($('input[name=pacing]:checked', '#pacing-form-group').val() === 'off') {
            $("#pacing_factor-form-group").hide();
        }
        else {
            $("#pacing_factor-form-group").show();
        }

        //Pacing factor change
        $("input[type=radio]", $("#pacing-form-group")).change(function() {
            if ( $(this).val() === 'off') {
                $("#pacing_factor-form-group").hide();
            }
            else {
                $("#pacing_factor-form-group").show();
            }
        });

        //Rev types and max bid calculation
        $.each([
            ["est_rev_type", "rpc", "rev_multiplier"],
            ["budget_type", "revenue", "redirects"]
        ],function(index, value) {
            var $toggler = $("#" + value[0] + "-form-group");
            if( $toggler.length ) {
                var $first = $("#" + value[1] + "-form-group"),
                    $second = $("#" + value[2] + "-form-group"),
                    map = {},
                    hideAll = function() {
                        $first.add($second).hide();
                    };

                map[value[1]] = $first;
                map[value[2]] = $second;
                hideAll();
                $("input[type=radio]", $toggler).change(function() {
                    hideAll();
                    map[$(this).val()].show();
                });
                $("input[type=radio]:checked", $toggler).trigger('change');
            }
        });

        //Rev types and max bid calculation
        $.each([
            ["max_bid_type", "bid", "multiplier"]
        ],function(index, value) {
            var $toggler = $("#" + value[0] + "-form-group");
            if( $toggler.length ) {
                var $first = $("#" + value[1] + "-form-group"),
                    $second = $("#" + value[2] + "-form-group"),
                    map = {},
                    hideAll = function() {
                        $first.add($second).hide();
                    };

                map[value[1]] = $first;
                map[value[2]] = $second;
                hideAll();

                $("input[type=checkbox]", $toggler).change(function() {
                    if ($(this).is(':checked')) {
                        map[$(this).val()].show();
                    }
                    else {
                        map[$(this).val()].hide();
                    }
                });
                $("input[type=checkbox]:checked", $toggler).trigger('change');
            }
        });

    })();
    // Advertiser modal
    (function () {
        $("#new-advertiser-link").click(function (e) {
            e.preventDefault();

            //Try to open existing modal
            var modal = $("#new-advertiser-modal");
            if (modal.length) {
                modal.modal('show');
            } else {
                //If not, make AJAX query to get modal HTML
                var href = $(this).attr('href');
                $.ajax(href, {
                    dataType: 'html'
                }).done(function (data) {
                    $(data).modal();
                });
            }
        });
        //Save button handler
        $(document).on('click', '#new-advertiser-modal .save-button', function (e) {
            e.preventDefault();

            var values = {},
                parent = $(this).parents(".modal"),
                form = parent.find("form");
            console.log(form);
            //Get values from form
            $("input, select, textarea", form).each(function () {
                values[$(this).attr('name')] = $(this).val();
            });
            console.log(values);
            $.ajax(form.attr('action'), {
                dataType: 'json',
                type: 'POST',
                data: values
            }).done(function (data) {
                if (data.status) {
                    //Close modal
                    $("#new-advertiser-modal").modal('hide');
                    $("#new-advertiser-modal").remove();

                    //Create option and add it to select
                    var option = $("<option>", {
                        value: data.adv_code,
                        text: data.adv_name
                    }).appendTo("select[name=adv_code]")
                        .attr('selected', 'selected')
                        .siblings().removeAttr('selected');
                } else {
                    //Replace modal with new HTML
                    parent.html($(data.html).html());
                }
            });
        });
    })();
});
