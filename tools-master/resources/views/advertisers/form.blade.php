<div class="form-group @if($errors->has('adv_code'))has-error @endif">
    {!! Form::label('adv_code', 'Advertiser Code: ', ['class' => 'control-label']) !!}
    <div class="controls">
        <div class="row">

                {!! Form::text('adv_code', null, [
                    'class' => 'form-control',
                    'required' => 'required'
                ]) !!}

            @if($errors->has('adv_code'))
            <div class="help-block col-lg-8">
                {!! join(". ", $errors->get('adv_code')) !!}
            </div>
            @endif
        </div>
        <p class="help-block"> Use alphanumeric characters only.  Do <strong>NOT</strong> use any underscores or other special characters. </p>
    </div>
</div>

<div class="form-group @if($errors->has('adv_name'))has-error @endif">
    {!! Form::label('adv_name', 'Advertiser Name: ', ['class' => 'control-label']) !!}
    <div class="controls row">

            {!! Form::text('adv_name', null, [
                'class' => 'form-control',
                'required' => 'required'
            ]) !!}

        @if($errors->has('adv_name'))
        <div class="help-block col-lg-8">
            {!! join(". ", $errors->get('adv_name')) !!}
        </div>
        @endif
    </div>
</div>

<div class="form-group @if($errors->has('adv_tag1'))has-error @endif">
    {!! Form::label('adv_tag1', 'Advertiser Tag 1: ', ['class' => 'control-label']) !!}
    <div class="controls row">

        {!! Form::text('adv_tag1', null, [
            'class' => 'form-control'
        ]) !!}

        @if($errors->has('adv_tag1'))
            <div class="help-block col-lg-8">
                {!! join(". ", $errors->get('adv_tag1')) !!}
            </div>
        @endif
    </div>
</div>

<div class="form-group @if($errors->has('adv_tag2'))has-error @endif">
    {!! Form::label('adv_tag2', 'Advertiser Tag 2: ', ['class' => 'control-label']) !!}
    <div class="controls row">

        {!! Form::text('adv_tag2', null, [
            'class' => 'form-control'
        ]) !!}

        @if($errors->has('adv_tag2'))
            <div class="help-block col-lg-8">
                {!! join(". ", $errors->get('adv_tag2')) !!}
            </div>
        @endif
    </div>
</div>

<div class="form-group @if($errors->has('adv_type'))has-error @endif">
    {!! Form::label('adv_type', 'Adv Type: ', ['class' => 'control-label']) !!}
    <div class="controls row">
            {!! Form::select('adv_type', $Advertiser->advTypes(), null, ['class' => 'form-control']) !!}
        @if($errors->has('adv_type'))
            <div class="help-block col-lg-8">
                {!! join(". ", $errors->get('adv_type')) !!}
            </div>
        @endif
    </div>
</div>

<div class="form-group @if($errors->has('adv_email'))has-error @endif">
    {!! Form::label('adv_email', 'Advertiser E-mail(s):', ['class' => 'control-label']) !!}
    <div class="controls">
        <div class="row">

                {!! Form::textarea('adv_email', null, [
                    'class' => 'form-control'
                ]) !!}

            @if($errors->has('adv_email'))
            <div class="help-block col-lg-8">
                {!! join(". ", $errors->get('adv_email')) !!}
            </div>
            @endif
        </div>
        <p class="help-block"> Separate multiple email addresses with a newline. </p>
    </div>
</div>

<div class="form-group @if($errors->has('daily_report'))has-error @endif">
    {!! Form::label('daily_report', 'Daily Report: ', ['class' => 'control-label']) !!}
    <div class="controls row">
            {!! Form::select('daily_report', $Advertiser::dailyReports(), null, ['class' => 'form-control']) !!}
        @if($errors->has('daily_report'))
        <div class="help-block col-lg-8">
            {!! join(". ", $errors->get('daily_report')) !!}
        </div>
        @endif
    </div>
</div>
