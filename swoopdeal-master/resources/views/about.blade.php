@extends('layouts.base')

@section('content')
            <div class="default-style about col-sm-9">
                <h2>{{ trans('messages.about_us') }}</h2>
                <div class="row">
                    <div class="col-sm-5 col-md-6">
                        <p>{{ trans('messages.about_paragraph_1') }}</p>
                        <p>{{ trans('messages.about_paragraph_2') }}</p>

                    </div>
                    <div class="col-sm-7 col-md-6">
                        <img class="img-about" src="{{ asset('images/slide-3.jpg') }}" class="img-responsive" alt="">
                    </div>
                    <div class="col-xs-12 col-sm-12">
                        <p>{{ trans('messages.about_paragraph_3') }}</p>
                        <p>{{ trans('messages.about_paragraph_4') }}</p>
                        <p><a href="mailto:contact@swoopdeal.com">contact@swoopdeal.com</a></p>
                        <p>{{ trans('messages.about_paragraph_5') }}</p>
                        <p><a href="mailto:mail@swoopdeal.com">mail@swoopdeal.com.</a></p>
                        <p>{{ trans('messages.about_paragraph_6') }}</p>
                        <p>{{ trans('messages.about_paragraph_7') }}</p>
                    </div>
                </div><!-- row -->

                <div class="icon-nav row">
                    <div class="col-xs-4 col-sm-4"><a href="{{ URL::to('/') }}"><i class="fa fa-home"></i>{{ trans('messages.home') }}</a></div>

                    <div class="col-xs-4 col-sm-4"><a href="{{ URL::route('privacy') }}"><i class="fa fa-lock"></i>{{ trans('messages.privacy_policy') }}</a></div>
                    <div class="col-xs-4 col-sm-4"><a href="{{ URL::route('terms') }}"><i class="fa fa-book"></i>{{ trans('messages.terms_of_service') }}</a></div>
                </div>
            </div>
@endsection