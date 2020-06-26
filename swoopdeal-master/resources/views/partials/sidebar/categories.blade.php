<div class="widget widget-categories">
  <h3><a role="button" data-toggle="collapse" href="#widget-categories-collapse" aria-expanded="true" aria-controls="widget-categories-collapse">@lang('messages.categories')</a></h3>
  <div class="collapse in" id="widget-categories-collapse" aria-expanded="true" role="tabpanel">
    <div class="widget-body">
      @include('partials.sidebar.cats', ['categories' => $categories])
    </div>
  </div>
</div>


<!-- WIDGET:STORES - END -->
{!! Form::close() !!}