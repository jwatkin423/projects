@extends('layouts.base')

@section('content')

<div class="row">
    <div class="col-lg-6">
        <div class="box">
            <div class="box-header">
                <h2><i class="fa fa-calendar"></i>Enter IP Address</h2>
                <div class="box-icon">
                    <a href="#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <form class="form-horizontal" action="#" method="get">
                    <fieldset class="col-lg-12">
                        <div class="form-group">
                            <label class="control-label" for="ip">IP Address:</label>
                            <div class="controls">
                                <input type="text" class="form-control" id="ip" name="ip" value="{{ $ip }}">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Lookup</button>
                    </fieldset>
                </form>
            </div>
        </div>
    </div><!--/col-->

    <div class="col-lg-6">
        <div class="box">
            <div class="box-header">
                <h2><i class="fa fa-calendar"></i>Geolocation Information</h2>
                <div class="box-icon">
                    <a href="#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <strong>City:</strong> {{ $city }}<br />
                <strong>State:</strong> {{ $state }}<br />
                <strong>Country:</strong> {{ $country_code }}<br />
                <hr class="line" />
                <strong>Latitude:</strong> {{ $latitude }} <br />
                <strong>Longitude:</strong> {{ $longitude }}
            </div>
        </div>
    </div><!--/col-->
</div>
@endsection
