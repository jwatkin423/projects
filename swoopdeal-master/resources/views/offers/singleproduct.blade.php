@php

if ($product->getImage()){
  $orgImageUrl = $product->getImage();

  if (preg_match('/100x100/', $orgImageUrl)) {
    $imageUrl = preg_replace('/100x100/', '300x300', $product->getImage());
  } elseif (preg_match('/s-l80/', $product->getImage())) {
      $imageUrl = preg_replace('/s-l80/', 's-l300', $product->getImage());
  } else {
      $imageUrl = '#';
  }

}
@endphp
<div class="product-mrgn-zero col-sm-9">
  <div class="collapse" id="products-search">
    @include('partials.search')
  </div>
  <section class="content single-product">

    <article class="product-item product-single">
      <div class="row">
        <div class="col-xs-4">
          <img src="{{ $imageUrl }}" class="img-responsive" alt="{{ $title }}">
        </div>
        <div class="col-xs-8">
          <div class="product-body">
            <h3>{{ $title }}</h3>
            <div class="product-labels">
              <span class="label label-info">new</span>
              <span class="label label-danger">sale</span>
            </div>
            <span class="price">
                                <ins><span class="amount">{{ $product->getPrice() }}</span></ins>
                            </span>
            <ul class="list-unstyled product-info">
              <li><span>ID</span>{{ $product->getID() }}</li>
              <li><span>Availability</span>In Stock</li>
              <li><span>Brand</span>{{ $product->getManufacturer() }}</li>
              <li><span>UPC</span>{{ $product->getUPC() }}</li>
            </ul>
            <div class="product-form clearfix">
              <div class="row row-no-padding">


              </div>
            </div>

          </div>
        </div>
      </div>
      <div class="margin-20"></div>
      @if($product->hasOffers())
        <div class="offers">
          <h2>Offers</h2>
          <table class="table table-hover table-products">
            <tbody>
            <tr class="blacky">
              <th>Seller</th>
              <th>Price</th>
              <th>Reviews</th>
              <th>Rating</th>
              <th>Availability</th>
              <th class="see-it">&nbsp;</th>
            </tr>
            @foreach($product->getOffers() as $offer)
              @php $rating = $offer->getRating(); @endphp
              <tr>
                <td class="retailer" class="text-center">
                  @include('partials.retailer_link', ["offer" => $offer])
                </td>
                <td class="text-center">
                  <a href="{{ $offer->getUrl() }}" target="_blank">
                    {{ $offer->getFormattedPrice() }}
                  </a>
                </td>
                <td class="text-center">@if($rating->hasScore())
                    @if($rating->getUrl())
                      <a href="{{ $rating->getUrl() }}" target="_blank">
                        @endif
                        @if($rating->getReviewsCount())
                          {{ $rating->getReviewsCount() }} reviews
                        @endif
                        @endif
                        @if($rating->getUrl())
                      </a>
                    @endif
                </td>
                <td class="text-center">
                  @include('partials.rating', ["rating" => $offer->getRating()])
                </td>
                <td class="text-center">{{ ucwords($offer->getAvailable()) }}</td>
                <td>
                  <a href="{{ $offer->getUrl() }}" target="_blank" class="btn btn-block btn-primary">See It</a>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
          <div class="margin-20"></div>
        </div>
      @endif
    </article>
  </section>
</div>
<!-- ==========================
  PRODUCTS - END
=========================== -->