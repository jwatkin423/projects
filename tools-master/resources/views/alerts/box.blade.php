<div class="box">
    {{--{{ ddd($alerts) }}--}}
    @if(count($alerts) > 0)
    <div class="box-header">
        <h2><i class="fa fa-exclamation-triangle"></i>{{ $box_title }}</h2>
    </div>
    <div class="box-content">
        <div class="table-responsive">
            <table class="table table-striped table-hover alerts-table bootstrap-datatable datatable">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Timestamp</th>
                        <th>Subject</th>
                        <th>Alert ID</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($alerts as $alert)
                    <tr data-href="{{ route('alerts-show', [$alert->alert_id]) }}">
                        <td ><span class="label label-{{ $alert->status_label }}">{{ strtoupper($alert->status) }}</span></td>
                        <td>{{ $alert->date_orig }}</td>
                        <td>{{ $alert->subject }}</td>
                        <td>{!! link_to_route('alerts-show', $alert->printable_alert_id, [$alert->alert_id]) !!}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @if($box_title != 'Important Alerts')
        <div class="row">
            <div class="col-12">
                <div class="pull-right">
                    {{ $links }}
                </div>
            </div>
        </div>
        @endif
        @if($box_title == 'Important Alerts')
            <a href="{!! route('ignore-alerts') !!}" class="btn btn-info btn-block">Set All Important Alerts to "Ignore"</a>
        @endif
    </div>
    @endif
</div>