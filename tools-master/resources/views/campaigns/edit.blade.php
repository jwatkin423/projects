@extends('layouts.base')

@section('content')
  @if(count($errors))
    <div class="alert alert-danger">
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @if($campaign->exists)
    {!! Form::model($campaign, ['route' => 'campaigns.update'], ['class' => 'form-horizontal']) !!}
  @else
    {!! Form::model($campaign, ['route' => 'campaigns.store'], ['class' => 'form-horizontal']) !!}
  @endif
  <div class="row">
    <div class="col-lg-6 col-md-12">
      <div class="box">
        <div class="box-header">
          <h2><i class="fa fa-align-justify"></i>
            @if($campaign->exists)
              Campaign: {!! $campaign->getName() !!}
            @else
              New Campaign
            @endif
          </h2>
        </div>
        <div class="box-content">
          <fieldset class="col-lg-12">
            <div class="form-group @if($errors->has('adv_code'))has-error @endif">
              {!! Form::label('adv_code', 'Advertiser Code: ', ['class' => 'control-label']) !!}
              <div class="controls row">
                <div class="col-lg-8">
                  {!! Form::select('adv_code', $advertisers, null, [
                      'class' => 'form-control',
                      'disabled' => $campaign->exists ? 'disabled' : null
                  ]) !!}
                  {!! Form::hidden('adv_code_hidden', $campaign->adv_code) !!}
                  {!! Form::hidden('campaign_code_hidden', $campaign->campaign_code) !!}
                  @if($campaign->exists)
                    <p class="help-block">
                      OR: <a href="{!! route('advertisers.create') !!}" id="new-advertiser-link"
                             class="btn btn-success">Create New Advertiser</a>
                    </p>
                  @endif
                </div>
                @if($errors->has('adv_code'))
                  <div class="help-block col-lg-8 alert alert-danger">
                    {!! join(". ", $errors->get('adv_code')) !!}
                  </div>
                @endif
              </div>
            </div>

            <div class="form-group @if($errors->has('campaign_code'))has-error @endif">
              {!! Form::label('campaign_code', 'Campaign Code: ', ['class' => 'control-label']) !!}
              <div class="controls">
                <div class="row">
                  <div class="col-lg-8">
                    {!! Form::text('campaign_code', null, [
                        'class' => 'form-control',
                        'required' => 'required',
                        'disabled' => $campaign->exists ? 'disabled' : null
                    ]) !!}
                  </div>
                  @if($errors->has('campaign_code'))
                    <div class="help-block col-lg-8 alert alert-danger">
                      {!! join(". ", $errors->get('campaign_code')) !!}
                    </div>
                  @endif
                </div>
                <p class="help-block"> (e.g. awesomesauce) </p>
              </div>
            </div>

            <div class="form-group @if($errors->has('campaign_name'))has-error @endif">
              {!! Form::label('campaign_name', 'Campaign Name: ', ['class' => 'control-label']) !!}
              <div class="controls row">
                <div class="col-lg-8">
                  {!! Form::text('campaign_name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                </div>
                @if($errors->has('campaign_name'))
                  <div class="help-block col-lg-8 alert alert-danger">
                    {!! join(". ", $errors->get('campaign_name')) !!}
                  </div>
                @endif
              </div>
            </div>
            <div class="form-group @if($errors->has('campaign_type'))has-error @endif">
              {!! Form::label('campaign_type', 'Campaign Type: ', ['class' => 'control-label']) !!}
              <div class="controls row">
                <div class="col-lg-8">
                  {!! Form::select('campaign_type', ['domain' => 'domain', 'RON' => 'RON', 'phone' => 'phone'], null, [
                      'class' => 'form-control',
                      'disabled' => $campaign->exists ? 'disabled' : null
                  ]) !!}
                </div>
                @if($errors->has('campaign_type'))
                  <div class="help-block col-lg-8 alert alert-danger">
                    {!! join(". ", $errors->get('campaign_type')) !!}
                  </div>
                @endif
              </div>
            </div>
            <div class="form-group @if($errors->has('campaign_meta'))has-error @endif">
              {!! Form::label('campaign_meta', 'Campaign Meta: ', ['class' => 'control-label']) !!}
              <div class="controls row">
                <div class="col-lg-8">
                  {!! Form::text('campaign_meta', null, ['class' => 'form-control']) !!}
                </div>
                @if($errors->has('campaign_meta'))
                  <div class="help-block col-lg-8 alert alert-danger">
                    {!! join(". ", $errors->get('campaign_meta')) !!}
                  </div>
                @endif
              </div>
            </div>
          </fieldset>
        </div>
      </div>
    </div><!--/col-->
    <div class="col-lg-6 col-md-12">
      <div class="box">
        <div class="box-header">
          <h2><i class="fa fa-align-justify"></i> Campaign Custom Rules </h2>
        </div>
        <div class="box-content">
          <fieldset class="col-lg-12">
            <div class="form-group @if($errors->has('country_code'))has-error @endif">
              {!! Form::label('country_code', 'Country: ', ['class' => 'control-label']) !!}
              <div class="controls row">
                <div class="col-lg-8">
                  {!! Form::select('country_code', $campaign::countries(), null, ['class' => 'form-control']) !!}
                </div>
                @if($errors->has('country_code'))
                  <div class="help-block col-lg-8 alert alert-danger">
                    {!! join(". ", $errors->get('country_code')) !!}
                  </div>
                @endif
              </div>
            </div>

            <div class="form-group @if($errors->has('user_agents'))has-error @endif">
              {!! Form::label('user_agents', 'User Agents: ', ['class' => 'control-label']) !!}
              <div class="controls row">
                <div class="col-lg-8">
                  {!! Form::select('user_agents', $campaign::userAgents(), null, ['class' => 'form-control']) !!}
                </div>
                @if($errors->has('user_agents'))
                  <div class="help-block col-lg-8 alert alert-danger">
                    {!! join(". ", $errors->get('user_agents')) !!}
                  </div>
                @endif
              </div>
            </div>

            <div class="form-group @if($errors->has('destination_url'))has-error @endif">
              {!! Form::label('destination_url', 'Destination Url: ', ['class' => 'control-label']) !!}
              <div class="controls">
                <div class="row">
                  <div class="col-lg-8">
                    {!! Form::text('destination_url', null, [
                        'class' => 'form-control',
                        'required' => 'required'
                    ]) !!}
                  </div>
                  @if($errors->has('destination_url'))
                    <div class="help-block col-lg-8 alert alert-danger">
                      {!! join(". ", $errors->get('destination_url')) !!}
                    </div>
                  @endif
                </div>
                <p class="help-block">Parameters: [API_ID], [DOMAIN], [KEYWORD]</p>
              </div>
            </div>

            <div class="form-group @if($errors->has('redirect_domain'))has-error @endif">
              {!! Form::label('redirect_domain', 'Redirect Domain: ', ['class' => 'control-label']) !!}
              <div class="controls">
                <div class="row">
                  <div class="col-lg-8">
                    {!! Form::text('redirect_domain', null, [
                        'class' => 'form-control'
                    ]) !!}
                  </div>
                  @if($errors->has('redirect_domain'))
                    <div class="help-block col-lg-8 alert alert-danger">
                      {!! join(". ", $errors->get('redirect_domain')) !!}
                    </div>
                  @endif
                </div>
              </div>
            </div>

            <div class="form-group @if($errors->has('start_hour'))has-error @endif">
              {!! Form::label('start_hour', 'Start Time: ', ['class' => 'control-label']) !!}
              <div class="controls row">
                <div class="col-lg-8">
                  {!! Form::select('start_hour', $hours , null, ['class' => 'form-control']) !!}
                </div>
                @if($errors->has('start_hour'))
                  <div class="help-block col-lg-8 alert alert-danger">
                    {!! join(". ", $errors->get('start_hour')) !!}
                  </div>
                @endif
              </div>
            </div>
            <div class="form-group @if($errors->has('use_adult'))has-error @endif">
              {!! Form::label('use_adult', 'Use Adult Traffic: ', ['class' => 'control-label']) !!}
              <div class="controls row">
                <div class="col-lg-8">
                  <label class="radio">
                    {!! Form::radio('use_adult', 0) !!} No
                  </label>
                  <label class="radio">
                    {!! Form::radio('use_adult', 1) !!} Yes
                  </label>
                </div>
                @if($errors->has('use_adult'))
                  <div class="help-block col-lg-8 alert alert-danger">
                    {!! join(". ", $errors->get('use_adult')) !!}
                  </div>
                @endif
              </div>
            </div>

          </fieldset>
        </div>
      </div>
    </div><!--/col-->
  </div>
  <div class="row">
    <div class="col-lg-6 col-md-12">
      <div class="box">
        <div class="box-header">
          <h2><i class="fa fa-align-justify"></i> Budget and Revenue </h2>
        </div>
        <div class="box-content">
          <fieldset class="col-lg-12">

            <div class="form-group @if($errors->has('budget_max'))has-error @endif">
              {!! Form::label('budget_max', ' Max Budget: ', ['class' => 'control-label']) !!}
              <div class="controls row">
                <div class="input-group  col-lg-6">
                  <span id="revenue-form-group" class="input-group-addon add-on">$</span>
                  {!! Form::text('budget_max', null, ['class' => 'form-control', 'required' => 'required']) !!}
                  <span id="redirects-form-group" class="input-group-addon add-on">redirects/day</span>
                </div>
                @if($errors->has('budget_max'))
                  <div class="help-block col-lg-8">
                    {!! join(". ", $errors->get('budget_max')) !!}
                  </div>
                @endif
              </div>
            </div>

            <div id="est_rev_type-form-group" class="form-group @if($errors->has('est_rev_type'))has-error @endif">
              {!! Form::label('est_rev_type', 'Campaign Revenue: ', ['class' => 'control-label']) !!}
              <div class="controls row">
                <div class="col-lg-3">
                  <label class="radio">
                    {!! Form::radio('est_rev_type', 'rpc') !!} RPC
                  </label>
                  <label class="radio">
                    {!! Form::radio('est_rev_type', 'rev_multiplier') !!} Multiplier
                  </label>
                </div>
                @if($errors->has('est_rev_type'))
                  <div class="help-block col-lg-8 alert alert-danger">
                    {!! join(". ", $errors->get('est_rev_type')) !!}
                  </div>
                @endif
              </div>
            </div>

            <div id="rpc-form-group" class="form-group @if($errors->has('est_rpc'))has-error @endif">
              {!! Form::label('est_rpc', 'Est RPC: ', ['class' => 'control-label']) !!}
              <div class="controls row">
                <div class="input-group col-lg-3">
                  <span class="input-group-addon add-on">$</span>
                  {!! Form::text('est_rpc', null, ['class' => 'form-control']) !!}
                </div>
                @if($errors->has('est_rpc'))
                  <div class="help-block col-lg-8 alert alert-danger">
                    {!! join(". ", $errors->get('est_rpc')) !!}
                  </div>
                @endif
              </div>
            </div>

            <div id="rev_multiplier-form-group"
                 class="form-group @if($errors->has('est_rev_multiplier'))has-error @endif">
              {!! Form::label('est_rev_multiplier', 'Est Revenue Multiplier: ', ['class' => 'control-label']) !!}
              <div class="controls row">
                <div class="col-lg-3">
                  {!! Form::text('est_rev_multiplier', null, ['class' => 'form-control']) !!}
                </div>
                @if($errors->has('est_rev_multiplier'))
                  <div class="help-block col-lg-8 alert alert-danger">
                    {!! join(". ", $errors->get('est_rev_multiplier')) !!}
                  </div>
                @endif
              </div>
            </div>

          </fieldset>
        </div>
      </div>
    </div><!--/col-->
    <div class="col-lg-6 col-md-12">
      <div class="box">
        <div class="box-header">
          <h2><i class="fa fa-align-justify"></i> Bidding </h2>
        </div>
        <div class="box-content">
          <fieldset class="col-lg-12">

            <div id="max_bid_type-form-group" class="form-group @if($errors->has('max_bid_type'))has-error @endif">
              {!! Form::label('max_bid_calculation', 'Max Bid Calculation: ', ['class' => 'control-label']) !!}
              <div class="controls row">
                <div class="col-lg-3">
                  <label class="checkbox">
                    {!! Form::checkbox('max_bid_type[]', 'bid') !!} Max Bid
                  </label>
                  <label class="checkbox">
                    {!! Form::checkbox('max_bid_type[]', 'multiplier') !!} Multiplier
                  </label>
                </div>
                @if($errors->has('max_bid_type'))
                  <div class="help-block col-lg-8 alert alert-danger">
                    {!! join(". ", $errors->get('max_bid_type')) !!}
                  </div>
                @endif
              </div>
            </div>

            <div id="bid-form-group" class="form-group @if($errors->has('max_bid'))has-error @endif">
              {!! Form::label('max_bid', 'Max Bid: ', ['class' => 'control-label']) !!}
              <div class="controls row">
                <div class="input-group col-lg-3">
                  <span class="input-group-addon add-on">$</span>
                  {!! Form::text('max_bid', null, ['class' => 'form-control']) !!}
                </div>
                @if($errors->has('max_bid'))
                  <div class="help-block col-lg-8 alert alert-danger">
                    {!! join(". ", $errors->get('max_bid')) !!}
                  </div>
                @endif
              </div>
            </div>

            <div id="multiplier-form-group" class="form-group @if($errors->has('max_bid_multiplier'))has-error @endif">
              {!! Form::label('max_bid_multiplier', 'Max Bid Multiplier: ', ['class' => 'control-label']) !!}
              <div class="controls row">
                <div class="input-group col-lg-3">
                  {!! Form::text('max_bid_multiplier', number_format($campaign->max_bid_multiplier, 2), ['class' => 'form-control']) !!}
                </div>
                @if($errors->has('max_bid_multiplier'))
                  <div class="help-block col-lg-8 alert alert-danger">
                    {!! join(". ", $errors->get('max_bid_multiplier')) !!}
                  </div>
                @endif
              </div>
            </div>

            <div id="bid_key" class="form-group @if($errors->has('bid_key'))has-error @endif">
              {!! Form::label('bid_key', 'Bid Key: ', ['class' => 'control-label']) !!}
              <div class="controls row">
                <div class="col-lg-3">
                  {!! Form::text('bid_key', null, ['class' => 'form-control']) !!}
                </div>
                @if($errors->has('bid_key'))
                  <div class="help-block col-lg-8 alert alert-danger">
                    {!! join(". ", $errors->get('bid_key')) !!}
                  </div>
                @endif
              </div>
            </div>

          </fieldset>
        </div>
      </div>
    </div><!--/col-->
  </div>
  <div class="row">
    <div class="col-lg-12">
      {!! Form::submit($campaign->exists ? 'Update' : 'Save', ['class' => 'btn btn-lg btn-info btn-block']) !!}
    </div>
  </div>
  {!! Form::close() !!}
@endsection
