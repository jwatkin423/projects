$(document).ready(function () {

    // Subtract one day to date in the input fields
    $("a#minus-one").click(function (e) {
        e.preventDefault();
        var to_date = $('#toDate').val();
        var from_date = $('#fromDate').val();
        var to_new_date = minusOneDay(to_date);
        var from_new_date = minusOneDay(from_date);
        $('#fromDate').val(from_new_date);
        $('#toDate').val(to_new_date);

    });

    function minusOneDay(the_date) {
        var date_segments = the_date.split('-');
        var f;
        var new_date;

        var date_string = date_segments[1] + "/" + date_segments[2] + "/" + date_segments[0];

        var d = new Date(date_string);
        f = d.setDate(d.getDate() - 1);
        var fr = new Date(f);

        var new_day = fr.getDate();
        var new_month = fr.getMonth();
        new_month = new_month + 1;
        if (new_month <= 9) {
            new_month = '0' + new_month;
        }
        if (new_day <= 9) {
            new_day = '0' + new_day;
        }
        var new_year = fr.getFullYear();
        new_date = new_year + "-" + new_month + "-" + new_day;

        return new_date;
    }

    // Add one day to date in the input fields
    $("a#plus-one").click(function (e) {
        e.preventDefault();
        var to_date = $('#toDate').val();
        var from_date = $('#fromDate').val();
        var to_new_date = plusOneDay(to_date);
        var from_new_date = plusOneDay(from_date);
        $('#fromDate').val(from_new_date);
        $('#toDate').val(to_new_date);

    });

    function plusOneDay(the_date) {
        var date_segments = the_date.split('-');
        var f;
        var new_date;

        var date_string = date_segments[1] + "/" + date_segments[2] + "/" + date_segments[0];

        var d = new Date(date_string);
        f = d.setDate(d.getDate() + 1);
        var fr = new Date(f);

        var new_day = fr.getDate();
        if (new_day <= 9) {
            new_day = '0' + new_day;
        }
        var new_month = fr.getMonth();
        new_month = new_month + 1;
        if (new_month <= 9) {
            new_month = '0' + new_month;
        }

        var new_year = fr.getFullYear();
        new_date = new_year + "-" + new_month + "-" + new_day;

        return new_date;
    }

    // pacing dates
    $("[class^='pacing-date-']").on('click', function (e) {
        e.preventDefault();

        var $element = $(this);
        var date_type = $element.attr("data-source");
        var $first_date = $('#first_date');
        var $second_date = $('#second_date');
        var $third_date = $('#third_date');
        var current_one_date = $first_date.val();
        var current_second_date = $second_date.val();
        var current_third_date = $third_date.val();

        $.ajax({
            url: '/reports/dates',
            data: {
                'first_date': current_one_date,
                'second_date': current_second_date,
                'third_date': current_third_date
            },
            success: function (data) {
                switch (date_type) {
                    case 'pacing-date-one' :
                        $first_date.val(data.today_date_filter);
                        $second_date.val(data.yesterday_date_filter);
                        $third_date.val(data.one_week_filter);
                        break;
                    case 'pacing-date-two' :
                        $first_date.val(data.today_date_filter);
                        $second_date.val(data.one_week_filter);
                        $third_date.val(data.two_weeks_filter);
                        break;
                    case 'pacing-date-three' :
                        $first_date.val(data.today_date_filter);
                        $second_date.val(data.one_week_filter);
                        $third_date.val(data.month_prior_filter);
                        break;
                    case 'pacing-date-four' :
                        $first_date.val(data.yesterday_date_filter);
                        $second_date.val(data.day_before_filter);
                        $third_date.val(data.one_week_filter);
                        break;
                    default:
                        $first_date.val(current_from_date);
                        $second_date.val(current_to_date);
                        $third_date.val(current_to_date);
                        break;
                }
            }
        });

    });

    // breakage dates
    $("[class^='breakage-date']").on('click', function(e) {
        e.preventDefault();
        var $element = $(this);
        var date_type = $element.attr("data-source");

        var $date_from = $('#breakage-date-from');
        var $date_to = $('#breakage-date-to');

        var current_date_from = $date_from.val();
        var current_date_to = $date_to.val();

        $.ajax({
            url: '/reports/breakageDates',
            data: {
                'date_from': current_date_from,
                'date_to': current_date_to
            },
            success: function(data) {
                switch(date_type) {
                    case 'week-filter':
                        $date_from.val(data.week_ago_filter.from);
                        $date_to.val(data.week_ago_filter.to);
                        break;
                    case 'thirty-day-filter':
                        $date_from.val(data.thirty_day_filter.from);
                        $date_to.val(data.thirty_day_filter.to);
                        break;
                    case 'sixty-day-filter':
                        $date_from.val(data.sixty_day_filter.from);
                        $date_to.val(data.sixty_day_filter.to);
                        break;
                    case 'ninety-day-filter':
                        $date_from.val(data.ninety_day_filter.from);
                        $date_to.val(data.ninety_day_filter.to);
                        break;
                }
            }
        });

    });

    // trueDashboard dates
    $("[class^='truedashboard-']").on('click', function(e) {
        e.preventDefault();
        var $element = $(this);
        var date_type = $element.attr("data-source");
        var $date_from = $('#fromDate');
        var $date_to = $('#toDate');

        var current_date_from = $date_from.val();
        var current_date_to = $date_to.val();

        $.ajax({
            url: '/reports/trueDashboardDates',
            data: {
                'date_from': current_date_from,
                'date_to': current_date_to
            },
            success: function(data) {
                switch(date_type) {
                    case 'today':
                        $date_from.val(data.today_date_filter.from);
                        $date_to.val(data.today_date_filter.to);
                        break;
                    case 'yesterday':
                        $date_from.val(data.yesterday_date_filter.from);
                        $date_to.val(data.yesterday_date_filter.to);
                        break;
                    case 'last-week':
                        $date_from.val(data.last_week_date_filter.from);
                        $date_to.val(data.last_week_date_filter.to);
                        break;
                    case 'thirty-days':
                        $date_from.val(data.thirty_days_date_filter.from);
                        $date_to.val(data.thirty_days_date_filter.to);
                        break;
                    case 'monthToDate':
                        $date_from.val(data.month_to_date_date_filter.from);
                        $date_to.val(data.month_to_date_date_filter.to);
                        break;
                    case 'lastMonth':
                        $date_from.val(data.last_month_date_filter.from);
                        $date_to.val(data.last_month_date_filter.to);
                        break;
                    case 'minus-one-day':
                    case 'plus-one-day':
                        break;
                    default:
                        $date_from.val(current_date_from);
                        $date_to.val(current_date_to);
                        break;

                }
            }
        });

    });

    // general dates
    $("[class$='-date']").on('click', function (e) {
        e.preventDefault();
        var $element = $(this);
        var date_type = $element.attr("data-source");
        var $from_date = $('#fromDate');
        var $to_date = $('#toDate');

        var current_from_date = $from_date.val();
        var current_to_date = $to_date.val();

        $.ajax({
            url: '/dates',
            data: {
                'toDate': current_to_date,
                'fromDate': current_from_date
            },
            success: function (data) {

                switch (date_type) {
                    case 'today' :
                        $from_date.val(data.today_date_filter.from);
                        $to_date.val(data.today_date_filter.to);
                        break;
                    case 'yesterday' :
                        $from_date.val(data.yesterday_date_filter.from);
                        $to_date.val(data.yesterday_date_filter.to);
                        break;
                    case 'lastWeek' :
                        $from_date.val(data.last_week_date_filter.from);
                        $to_date.val(data.last_week_date_filter.to);
                        break;
                    case 'thirtyDays' :
                        $from_date.val(data.thirty_days_filter.from);
                        $to_date.val(data.thirty_days_filter.to);
                        break;
                    case 'monthToDate' :
                        $from_date.val(data.month_to_date_date_filter.from);
                        $to_date.val(data.month_to_date_date_filter.to);
                        break;
                    case 'lastMonth' :
                        $from_date.val(data.last_month_date_filter.from);
                        $to_date.val(data.last_month_date_filter.to);
                        break;
                    default:
                        $from_date.val(current_from_date);
                        $to_date.val(current_to_date);
                        break;
                }
            }
        });
    });

    // merchant dates
    $("[class$='date-merchant']").on('click', function (e) {
        e.preventDefault();
        var $element = $(this);
        var date_type = $element.attr("data-source");
        var $from_date = $('#fromDate');
        var $to_date = $('#toDate');

        var current_from_date = $from_date.val();
        var current_to_date = $to_date.val();

        $.ajax({
            url: '/dates',
            data: {
                'toDate': current_to_date,
                'fromDate': current_from_date
            },
            success: function (data) {
                switch (date_type) {
                    case 'today' :
                        $from_date.val(data.today_date_filter.from);
                        $to_date.val(data.today_date_filter.to);
                        break;
                    case 'yesterday' :
                        $from_date.val(data.yesterday_date_filter.from);
                        $to_date.val(data.yesterday_date_filter.to);
                        break;
                    case 'monthToDate' :
                        $from_date.val(data.month_to_date_date_filter.from);
                        $to_date.val(data.month_to_date_date_filter.to);
                        break;
                    case 'lastMonth' :
                        $from_date.val(data.last_month_date_filter.from);
                        $to_date.val(data.last_month_date_filter.to);
                        break;
                    default:
                        $from_date.val(current_from_date);
                        $to_date.val(current_to_date);
                        break;
                }
            }
        });
    });

});
