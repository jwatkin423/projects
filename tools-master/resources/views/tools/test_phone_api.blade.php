@extends('layouts.base')

@section('content')
<div class="row">
    <div class="col-lg-7">
        <div class="box">
            <div class="box-header">
                <h2><i class="fa fa-flash"></i>API Parameters</h2>
            </div>
            <div class="box-content">
                {!! Form::model($test_phone_api, ["url" => "#", "method" => "get", "class" => "form-horizontal"]) !!}
                <fieldset class="col-lg-12">
                    <div class="form-group">
                        {!! Form::label('api_id', 'API Partner:', ["class" => "control-label"]) !!}
                        <div class="controls">
                            {!! Form::select('api_id', $test_phone_api->getApiPartners(), null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('search', 'Search:', ["class" => "control-label"]) !!}
                        <div class="controls">
                            {!! Form::text('search', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('Source', 'Source:', ["class" => "control-label"]) !!}
                        <div class="controls">
                            {!! Form::text('Source', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('category', 'Category:', ["class" => "control-label"]) !!}
                        <div class="controls">
                            {!! Form::text('category', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>


                    <div class="form-group">
                        {!! Form::label('location', 'Location (zip):', ["class" => "control-label"]) !!}
                        <div class="controls">
                            {!! Form::text('location', $location, ['class' => 'form-control']) !!}
                        </div>
                    </div>


                    <div class="form-group">
                        {!! Form::label('env', 'Environment:', ["class" => "control-label"]) !!}
                        <div class="controls">
                            {!! Form::select('env', $test_phone_api->getEnvironments(), null, ['class' => 'form-control']) !!}
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

    @if($test_phone_api->action == 'request')
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
