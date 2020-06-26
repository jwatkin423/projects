@if(count($categories))
<div class="widget widget-categories">
  <h3><a role="button" style="z-index: 99;" data-toggle="collapse" href="#widget-categories-collapse" aria-expanded="true" aria-controls="widget-categories-collapse">@lang('messages.categories')</a></h3>
  <div class="collapse in" id="widget-categories-collapse" aria-expanded="true" role="tabpanel">
    <div class="widget-body">
      @include('partials.sidebar.cats', ['categories' => $categories])
    </div>
  </div>
</div>
@endif
@if((session()->has('q') && count($featuredProducts->items())) || (isset($catSearch) && $catSearch == true) && count($featuredProducts->items()))

  @php $id = "apply-filter"; @endphp
  @if (isset($catSearch) && $catSearch == true)
    {!! Form::open(['route' => ['category', $main_id, $sub_id ?? null], 'id' => 'cat-search-main-all', 'data-url' => URL::to('category'), 'method' => 'GET']) !!}
    @php $id = "cat-apply-filter"; @endphp
  @else
    {!! Form::open(['route' => 'search', 'id' => 'search-main-all', 'data-url' => URL::to('search'), 'method' => 'GET']) !!}
    {!! Form::hidden('q', $q) !!}
    {!! Form::hidden('stores', null, ['id' => 'stores-ids']) !!}
    {!! Form::hidden('brands', null, ['id' => 'brands-ids']) !!}
  @endif

  {!! Form::hidden('minPrice', null, ['id' => 'hidden_amount' ]) !!}
  {!! Form::hidden('maxPrice', null, ['id' => 'hidden_amount_2' ]) !!}

  <!-- WIDGET:BRANDS - START -->
@if ($brands)
  <div class="widget widget-checkbox">
    <h3><a role="button" data-toggle="collapse" href="#widget-brands-collapse" aria-expanded="true" aria-controls="widget-brands-collapse">{{ trans('messages.brands') }}</a></h3>
    <div class="collapse in" id="widget-brands-collapse" aria-expanded="true" role="tabpanel">
      <div class="widget-body">

        @foreach($brands as $brand)
          @php $checked = in_array($brand['id'], $checkedBrandsIds) ? 'checked' : null;  @endphp
          <div class="checkbox">
            <input class="chbx-brand" id="b-{{ $brand['id'] }}" type="checkbox" value="{{ $brand['id'] }}" {{ $checked }}>
            <label for="b-{{ $brand['id'] }}">{{ $brand['name'] }}</label>
          </div>
        @endforeach

      </div>
    </div>
  </div>
@endif
<!-- WIDGET:BRANDS - END -->

<!-- WIDGET:STORES - START -->
@if ($stores)
  <div class="widget widget-checkbox">
    <h3><a role="button" data-toggle="collapse" href="#widget-stores-collapse" aria-expanded="true" aria-controls="widget-stores-collapse">{{ trans('messages.stores') }}</a></h3>
    <div class="collapse in" id="widget-stores-collapse" aria-expanded="true" role="tabpanel">
      <div class="widget-body">

        @foreach($stores as $store)
          @php $checked = in_array($store['id'], $checkedStoresIds) ? 'checked' : null;  @endphp
          <div class="checkbox">
            <input class="chbx-store" id="s-{{ $store['id'] }}" type="checkbox" value="{{ $store['id'] }}" {{ $checked }}>
            <label for="s-{{ $store['id'] }}">{{ $store['name'] }}</label>
          </div>
        @endforeach

      </div>
    </div>
  </div>
@endif
<!-- WIDGET:STORES - END -->



<div class="widget-filter">
  <!-- WIDGET:PRICE - START -->
  <div class="widget widget-price">
    <h3><a role="button" data-toggle="collapse" href="#widget-price-collapse" aria-expanded="true" aria-controls="widget-price-collapse">{{ trans('messages.filter_by_price') }}</a></h3>
    <div class="collapse in" id="widget-price-collapse" aria-expanded="true" role="tabpanel">
      <div class="widget-body">
        <div class="price-slider">
          <div id="slider-range"></div>
            <div class="row">
              <div class="col-xs-6">
                <div class="input-group">
                  <input name="amount" type="text" class="pull-left" id="amount" readonly>
                </div>
              </div>
              <div class="col-xs-6">
                <div class="input-group">
                <input name="amount2" type="text" class="pull-right" id="amount2" readonly>
              </div>
            </div>
            </div>
        </div>
      </div>
    </div>
  </div>
  <!-- WIDGET:PRICE - END -->
  <div class="row">
    <div class="col-12 text-center">
      <button type="submit" id="{{ $id }}" class="btn btn-danger">{{ trans('messages.apply_filters') }}</button>
    </div>
  </div>
</div>
{!! Form::close() !!}
@endif
