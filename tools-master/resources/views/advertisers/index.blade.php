@extends('layouts.base')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header">
                <h2><i class="fa fa-align-justify"></i>{{{ $title }}}</h2>
                <div class="box-icon">
                    <a href="#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered bootstrap-datatable datatable">
                        <thead>
                            <tr>
                                <th>Adv Code</th>
                                <th>Adv Name</th>
                                <th>Adv Tag1</th>
                                <th>Adv Tag2</th>
                                <th>Adv Type</th>
                                <th>Status</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($advertisers as $adv)
                        @php
                            $statuses = [
                                    'active' => 'btn-success',
                                    'inactive' => 'btn-default'
                                ];
                        @endphp
                            <tr>
                                <td>{{ $adv['adv_code'] }}</td>
                                <td>{{ $adv['adv_name'] }}</td>
                                <td>{{ $adv['adv_tag1'] }}</td>
                                <td>{{ $adv['adv_tag2'] }}</td>
                                <td>{{ $adv['adv_type'] }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn {{ $statuses[$adv->status] }} dropdown-toggle" data-toggle="dropdown">
                                            {{ ucfirst($adv->status) }} <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu" style="min-width:250px">
                                            <li>

                                                <a href="{{ route('advertisers-status', ['advertiser' => $adv->id, 'status' => 'active']) }}">
                                                    <i class="fa fa-play"></i> Change status to "Active"
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('advertisers-status', ['advertiser' => $adv->id, 'status' => 'inactive']) }}">
                                                    <i class="fa fa-power-off"></i> Change status to "Inactive"
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td><a class="btn btn-primary" href="{{ route('advertisers.edit', $adv['id']) }}">Edit</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('advertisers.create') }}" class="btn btn-lg btn-success btn-block">Create new Advertiser</a>
            </div>
        </div>
        @if(!$with_inactive)
        <a href="{{ route('advertisers.index', ['with_inactive' => true]) }}" class="btn btn-lg btn-block">Show Inactive Advertisers</a>
        @else
        <a href="{{ route('advertisers.index') }}" class="btn btn-lg btn-block">Hide Inactive Advertisers</a>
        @endif

    </div><!--/col-->
</div>

@endsection
