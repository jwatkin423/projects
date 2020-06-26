@extends('layouts.base')

@section('body')

<div class="row">
    <div class="col-lg-6">
        <div class="box">
            <div class="box-header">
                <h2><i class="fa fa-align-justify"></i>Restart Options</h2>
                <div class="box-icon">
                    <a href="#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                {{ Form::model($options, ['action' => ['ToolsController@postServerRestart'], 'class' => 'form-horizontal']) }}
                    <fieldset class="col-lg-12">
                        <div class="form-group">
                            {{ Form::label('server', 'Server: ', ['class' => 'control-label']) }}
                            <div class="controls">
                                {{ Form::select('server', $servers, null, ['class' => 'form-control']) }}
                            </div>
                        </div>


                        <div class="form-group">
                            {{ Form::label('comment', 'Comment: ', ['class' => 'control-label']) }}
                            <div class="controls">
                                {{ Form::text('comment', $values['comment'], ['class' => 'form-control', 'required' => 'required']) }}
                            </div>
                        </div>

                        <div class="form-actions">
                            {{ Form::submit('Restart', ['class' => 'btn btn-primary']) }}
                        </div>

                    </fieldset>
                {{ Form::close() }}
            </div>
        </div>
    </div><!--/col-->
</div>

@include('tools.system_output', ['output' => $output])

@stop
