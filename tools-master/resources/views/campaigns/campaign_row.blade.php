@php


  // budget

  $budget = $campaign->formatted_budget;
  $campaign_code = $campaign->campaign_code;
  $adv_code = $campaign->adv_code;

  $adv_key = $adv_code . "_" . $campaign_code;
  $campaign_field_id_budget = "{$adv_key}_{$campaign_code}_budget";
  $campaign_field_id_budget_update = "{$adv_key}_{$campaign_code}_budget_update";
  $campaign_field_id_budget_form = "{$adv_key}_{$campaign_code}_form";

@endphp

<tr>
  <td>{{ $campaign['adv_code'] }}</td>
  <td>{{ $campaign['campaign_code'] }}</td>
  <td>{{ $campaign['campaign_name'] }}</td>
  <td>{{ $campaign['country_code'] }}</td>
  <td>{{ $budget }}</td>
  <td>
    <div class="input-group mb-3">
      {!! Form::text('budget_max', null, ['class' => "form-control", 'aria-label' => "Recipient's username", 'aria-describedby' => "basic-addon2", 'id' => $campaign_field_id_budget] ) !!}
      <div class="input-group-append">
        <a href="#" class="btn btn-outline-secondary" type="button" id="{{ $campaign_field_id_budget_update }}">Update</a>
      </div>
      {!! Form::hidden('adv_key', $adv_key); !!}
    </div>
  </td>
  <td>
    <div class="btn-group">
      <button type="button" class="btn {{ $statuses[$campaign->status] }} dropdown-toggle" data-toggle="dropdown">
        {{ ucfirst($campaign->status) }} <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu" style="min-width:250px">
        <li>
          <a href="{{ route('campaign-status', ['campaign' => $campaign->id, 'status' => 'active']) }}">
            <i class="fa fa-play"></i> Change status to "Active"
          </a>
        </li>
        <li>
          <a href="{{ route('campaign-status', ['campaign' => $campaign->id, 'status' => 'paused']) }}">
            <i class="fa fa-pause"></i> Change status to "Paused"
          </a>
        </li>
      </ul>
    </div>
  </td>
</tr>
