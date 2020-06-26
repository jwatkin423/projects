@extends('layouts.base')


@section('content')

  <div class="col-lg-12">
    <div class="box">
      <div class="box-header">
        <h2><i class="fa fa-align-justify"></i>{{{ $title }}}</h2>
        <div class="box-icon">
          <a href="#" class="btn-minimize"><i class="fa fa-chevron-up"></i></a>
        </div>
      </div>
      <div class="box-content">

    <table class="table table-hover table-striped table-responsive">
      <thead>
      <tr>
        <th>Server Name</th>
        <th>Motherboard</th>
        <th>CPU(s)</th>
        <th>RAM</th>
        <th>HDD</th>
        <th>R.A.I.D.</th>
        <th>RU Size</th>
        <th>Purchase Date</th>
        <th>Price</th>
        <th>Warranty</th>
        <th>Server NIC 1 IP</th>
        <th>Server NIC 2 IP</th>
        <th>IPMI</th>
        <th>Voltage</th>
        <th>Amps</th>
      </tr>
      </thead>
      <tbody>
      @foreach($servers as $server)
        {{ d($server) }}
        @include('servers.server_row', $server)
      @endforeach
      </tbody>
    </table>

  </div>

@endsection