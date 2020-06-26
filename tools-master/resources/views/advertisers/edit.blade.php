@extends('layouts.base')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header">
                <h2><i class="fa fa-align-justify"></i> {{ $title }} </h2>
                <div class="box-icon">
                    <a href="#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                @if($Advertiser->exists)
                    {!! Form::model($Advertiser, ['route' => 'advertisers.update'], ['class' => 'form-horizontal']) !!}
                @else
                    {!! Form::model($Advertiser, ['route' => 'advertisers.store'], ['class' => 'form-horizontal']) !!}
                @endif
                <fieldset class="col-lg-12">
                    @include('advertisers.form')
                    <div class="form-actions">
                        {!! Form::submit($Advertiser->exists ? 'Update' : 'Save', ['class' => 'btn btn-lg btn-info btn-block']) !!}
                    </div>
                </fieldset>
            {!! Form::close() !!}
            </div>
        </div>
    </div><!--/col-->
</div>
@endsection