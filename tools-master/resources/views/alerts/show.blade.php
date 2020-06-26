@extends('layouts.base')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="box">
            <div class="box-header">
                <h2><i class="fa fa-exclamation-triangle"></i>System Alert</h2>
            </div>
            <div class="box-content">
                <dl class="with-margin">
                    <dt>Alert ID:</dt>
                    <dd>{{ $alert->alert_id }}</dd>

                    <dt>Alert created at:</dt>
                    <dd>{{ $alert->created_at }}</dd>

                    <dt>Alert updated at:</dt>
                    <dd>{{ $alert->updated_at }}</dd>

                    <dt>Subject:</dt>
                    <dd>{{ $alert->subject }}</dd>

                    <dt>Body:</dt>
                    <dd>{!! nl2br($alert->body) !!}</dd>

                    <dt>Status:</dt>
                    <dd>
                        <br />
                        <span class="label label-{{ $alert->status_label }} label-big">
                            {{ strtoupper($alert->status) }}
                        </span>
                    </dd>
                </dl>
            </div> <!-- /box-content -->
        </div> <!-- /box -->
    </div> <!-- /col -->

    <div class="col-lg-4">
        <div class="box">
            <div class="box-header">
                <h2><i class="fa fa-exclamation-triangle"></i>Update Alert Status</h2>
            </div>

            <div class="box-content">
                {!! Form::open(['action' => ['AlertsController@postChangeStatus', $alert->alert_id]]) !!}
                {!! Form::hidden('back_url', $back_url) !!}
                <div id="alert-update-buttons">
                     @php $labels = $alert::statusLabels() @endphp
                    <button name="status" value="ignore" class="btn btn-{{ $labels['ignore'] }} btn-block">IGNORE</button><br />
                    <button name="status" value="minor" class="btn btn-{{ $labels['minor'] }} btn-block">MINOR</button><br />
                    <button name="status" value="info" class="btn btn-{{ $labels['info'] }} btn-block">INFO</button><br />
                    <button name="status" value="warning" class="btn btn-{{ $labels['warning'] }} btn-block">WARNING</button><br />
                    <button name="status" value="important" class="btn btn-{{ $labels['important'] }} btn-block">IMPORTANT</button><br />
                    <button name="status" value="resolved" class="btn btn-{{ $labels['resolved'] }} btn-block">RESOLVED</button><br />
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div><!--/col-->
</div>

<div class="row">
    <div class="col-lg-12">
        <a href="{{ $back_url }}" class="btn btn-lg btn-info btn-block">&lt;&lt; Back to System Alerts</a>
    </div><!--/col-->
</div>

@endsection
