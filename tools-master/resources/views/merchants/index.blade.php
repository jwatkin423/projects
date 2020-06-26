@extends('layouts.base')

@if($report_type == 'campaign')
  @include('merchants.campaign')
@else
  @include('merchants.merchants')
@endif
