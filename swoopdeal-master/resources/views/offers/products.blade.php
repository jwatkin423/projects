@php $Products = new \App\Http\Controllers\ProductsController(1); @endphp
<div class="product-mrgn-zero">
  <div class="row grid row-no-padding" id="products">

 @if($featuredProducts)

   @if(!is_array($featuredProducts))
        @php
          $fp = $featuredProducts->toArray();
          $products = $fp['data'];
        @endphp

        @foreach($featuredProducts as $index => $product)
          @if ($index !== 'Brand' && $index !== 'Stores')
            @include('offers.product', ['offer' => $product])
          @endif
        @endforeach

   @else

    @foreach($featuredProducts as $index => $product)
      @if ($index !== 'Brand' && $index !== 'Stores')
        @include('offers.product', ['offer' => $product])
      @endif
    @endforeach
   @endif
 @else
    <h3>No results found</h3>
  @endif
  </div>
</div>
@if($pagination )
<div class="pull-right">
  <div class="pagination-container">
    {!! $featuredProducts->render() !!}
  </div>
</div>
@endif
@section('inline-js')
  $(function () {
    $("#filters-form select").change(function () {
      $(this).parents("form").submit();
    });
  });
@endsection