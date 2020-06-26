<div class="modal fade" id="campaign-update-modal" tabindex="-1" role="dialog" aria-labelledby="no-trafficLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="no-trafficLabel"></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{--<form class="form form-horizontal" id="campaign-update" action="{!! route('campaigns.update') !!}">--}}
        <form class="form form-horizontal" id="campaign-update" action="#">
        {!! csrf_field() !!}
        {{--{!! Form::open(['route' => 'campaigns.update']) !!}--}}
        <!-- current value -->
        <div class="form-group">
          {!! Form::Label('Current Value(s):', null, ['class' => 'label']) !!}
          {!! Form::text('current_value', null, ['id' => 'campaign-current-value', 'class' => 'form-control', 'disabled']) !!}
        </div>
        <!-- campaign budget -->
        <div class="form-group" id = 'budget'>
          {!! Form::Label('New Max Budget:', null, ['class' => 'label']) !!}
          {!! Form::number('budget_max', null, ['id' => 'campaign-budget', 'class' => 'form-control']) !!}
        </div>
          <!-- submit button -->
        <div class="form-group">
          {!! Form::hidden('campaign_type', null, ['id' => 'campaign-type', 'class' => 'form-control']) !!}
          {!! Form::hidden('adv_key', null, ['id' => 'campaign-adv-key', 'class' => 'form-control']) !!}
        </div>

        <button class="btn btn-block btn-success" id="update-campaign-submit">Update</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>