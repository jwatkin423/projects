@extends('layouts.base')

@section('content')

<div class="row">
    <div class="col-lg-7">
        <div class="box">
            <div class="box-header">
                <h2><i class="fa fa-flash"></i>API Parameters</h2>
            </div>
            <div class="box-content">
                {!! Form::model($test_api_request, ["url" => "#", "method" => "get", "class" => "form-horizontal"]) !!}
                    <fieldset class="col-lg-12">
                        <div class="form-group">
                            {!! Form::label('api_id', 'API Partner:', ["class" => "control-label"]) !!}
                            <div class="controls">
                                {!! Form::select('api_id', $test_api_request->getApiPartners(), null, ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('search', 'Search:', ["class" => "control-label"]) !!}
                            <div class="controls">
                                {!! Form::text('search', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('ip', 'IP Address:', ["class" => "control-label"]) !!}
                            <div class="controls">
                                {!! Form::text('ip', null, ['class' => 'form-control']) !!}
                                <div class="well help-block">
                                    <div class="accordion" id="accordion2">
                                        <div class="accordion-group">
                                            <div class="accordion-heading">
                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                                                    Example Country IPs:
                                                </a>
                                            </div>
                                            <div id="collapseOne" class="accordion-body collapse">
                                                <div class="accordion-inner">
                                                    <div>
                                                        <hr class="" />
                                                    </div>
                                                    <a href="#" class="test-ip">108.203.9.188 (US1 - Burbank, CA) [Nate's Home]</a><br />
                                                    <a href="#" class="test-ip">24.193.61.107 (US2 - New York, NY) [Joe's Home] (US2)</a><br />
                                                    <a href="#" class="test-ip">134.201.250.155 (US3 - Los Angeles, CA)</a><br />
                                                    <a href="#" class="test-ip">71.189.109.75 (US4 - Santa Monica, CA)</a><br />
                                                    <a href="#" class="test-ip">5.135.0.0 (France)</a><br />
                                                    <a href="#" class="test-ip">2.24.0.0 (GB)</a><br />
                                                    <a href="#" class="test-ip">2.160.0.0 (Germany - DE)</a><br />
                                                    <a href="#" class="test-ip">2.136.0.0 (Spain - ES)</a><br />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('ua', 'User Agent:', ["class" => "control-label"]) !!}
                            <div class="controls">
                                {!! Form::text('ua', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('source', 'Source (Domain):', ["class" => "control-label"]) !!}
                            <div class="controls">
                                {!! Form::text('source', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('referrer', 'Referrer:', ["class" => "control-label"]) !!}
                            <div class="controls">
                                {!! Form::url('referrer', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('min_bid', 'Min Bid:', ["class" => "control-label"]) !!}
                            <div class="controls">
                                <div class="input-group">
                                    <span class="input-group-addon add-on">$</span>
                                    {!! Form::text('min_bid', null, ["class" => "form-control"]) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('adv_key', 'Force Advertiser Key:', ["class" => "control-label"]) !!}
                            <div class="controls">
                                {!! Form::text('adv_key', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('env', 'Environment:', ["class" => "control-label"]) !!}
                            <div class="controls">
                                {!! Form::select('env', $test_api_request->getEnvironments(), null, ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" name="action" value="request" class="btn btn-primary">Request</button>
                            <button type="submit" name="action" value="string" class="btn btn-info">Just Request String</button>
                        </div>

                    </fieldset>
                {!! Form::close() !!}
            </div>
        </div>
    </div><!--/col-->

    <div class="col-lg-5 test-api-request-box">
        <div class="box">
            <div class="box-header">
                <h2><i class="fa fa-upload"></i>Request</h2>
            </div>
            <div class="box-content">
                <a href="{{ $request_url }}" target="_blank">{{ join("&<br>", explode("&", $request_url)) }}</a>
            </div>
        </div>

    @if($test_api_request->action == 'request')
    <!-- Response -->

        <div class="box">
            <div class="box-header">
                <h2><i class="fa fa-download"></i>Response ({{ $timer }} msec)</h2>
            </div>
            <div class="box-content">
                {!! $xml_out !!}
            </div>
        </div>
    </div><!--/col-->
    @endif
</div>

@stop
