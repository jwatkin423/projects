@extends('layouts.base')

@section('content')
    <div class="row">
        <div class="col-12 alerts-row">
            @include('alerts.important_summary')
        </div>
    </div>

    <div class="row">

        <div class="col-lg-8">
            @include('partials.stats')
        </div>
        <div class="col-lg-4 col-xs-12">
            @include('partials.date-form')
        </div>
    </div><!--/row-->

    <!-- API Partner Table -->
    <div class="row">
        @include('partials.api_partner_table')
    </div> <!-- /row (API PARTNER TABLE) -->

    <div class="row">
        @include('partials.adv_perf_table')
        @include('partials.adv_perf_table_ru')
    </div> <!-- /row -->

    <div class="row">
        <div class="col-lg-12">
            <label class="switch switch-primary float-right">
                <input type="checkbox" id="toggle-views" class="switch-input" checked>
                <span class="switch-label" data-on="On" data-off="Off"></span>
                <span class="switch-handle"></span>
            </label>
            <h6 class="float-right">Toggle Legacy</h6>
        </div>
    </div>

@endsection

@section('inline-js-main')
{{--    <script>--}}
        $(function () {
            let initToggleState = false;
            var set_session_url = "{!! route('legacy-view-set') !!}";
            var show_legacy = "{{ $show_legacy }}";
            $.ajaxSetup({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            toggle();

            $("#toggle-views").on("click", function () {
                var checked = $(this).prop('checked');
                if (checked) {
                    $.ajax({
                        url: set_session_url,
                        type: 'post',
                        dataType: 'json',
                        data: {set_session: 'start'},
                        success: function (data) {
                            console.log(data);
                            $('#legacy').show();
                            $('#rollup').hide();
                        }
                    });
                    console.log('line 81 ' + checked);
                } else {
                    $.ajax({
                        url: set_session_url,
                        type: 'post',
                        dataType: 'json',
                        data: {set_session: 'end'},
                        success: function (data) {
                            console.log('This should be working now');
                            console.log('rollup  should be showing now');
                            $('#legacy').hide();
                            $('#rollup').show();
                        }
                    });

                    console.log('roll up should show here ');
                }
            });

            function toggle() {
                if (!initToggleState && !show_legacy) {
                    $('#legacy').hide();
                    $('#rollup').show();
                    $("#toggle-views").attr('checked', false);
                } else if(!initToggleState && show_legacy) {
                    $('#legacy').show();
                    $('#rollup').hide();
                    $("#toggle-views").attr('checked', true);
                }
            }
        });

@endsection
