@extends('layouts.base')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="box">
            <div class="box-header">
                <h2><i class="fa fa-align-justify"></i>Load Options</h2>
                <div class="box-icon">
                    <a href="#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                {{ Form::model($options, ['action' => ['ToolsController@postDataLoader'], 'class' => 'form-horizontal']) }}
                    <fieldset class="col-lg-12">
                        <div class="form-group">
                            {{ Form::label('env', 'Environment: ', ['class' => 'control-label']) }}
                            <div class="controls">
                                {{ Form::select('env', $environments, $values['env'], ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('data_sources', 'Data Sources: ', ['class' => 'control-label']) }}
                            <div class="controls">
                                @foreach($data_sources as $data_source => $data_source_name)
                                    <label class="checkbox inline">
                                        @if (isset($values['data_source'][$data_source])) 
                                            {{ Form::checkbox('data_sources[]', $data_source, TRUE) }}
                                        @else
                                            {{ Form::checkbox('data_sources[]', $data_source) }}
                                        @endif
                                        {{ $data_source_name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-actions">
                            {{ Form::submit('Load Data', ['class' => 'btn btn-success btn-lg btn-block']) }}
                        </div>

                    </fieldset>
                {{ Form::close() }}
            </div>
        </div>
    </div><!--/col-->
</div>

@include('tools.system_output', ['output' => $output])

@endsection
