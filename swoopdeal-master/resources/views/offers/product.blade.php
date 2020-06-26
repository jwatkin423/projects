@if(!is_array($offer) && is_object($offer))
@php
  if($offer) {

    $original_img_url = $offer->getImage();
    $imageUrl = '';

    $imageUrl =  $original_img_url;
    $get_offers = $offer->getOffers();

    if(isset($get_offers[0])) {
      $retailer_text = $get_offers[0]->getRetailerText();

      if ($retailer_logo = $get_offers[0]->hasRetailerLogo()) {
          $retailer_logo = $get_offers[0]->getRetailerLogo();
      } else {
          $retailer_logo = false;
      }
    } else {
      $retailer_logo = false;
      $retailer_text = false;
    }

  }

  $rating = [];
@endphp

<div class="product-mrgn-zero col-sm-4 col-xs-6">
  <article class="product-item">
    <div class="row">
      <div class="col-sm-3">
        <div class="product-overlay">
          <div class="product-mask"></div>
          <a href="{{ $offer->getUrl() }}" class="product-permalink" target="{{ url($product->getLinkTarget()) }}"></a>

          <img src="{{ $imageUrl }}" class="img-responsive vertical-center" alt="">

        </div>
      </div>
      <div class="col-sm-9">
        <div class="product-body">
          @if (strlen($offer->getTitle()) >= 41)
            <h3>{{ substr($offer->getTitle(), 0, 40) }}...</h3>
          @else
            <h3>{{ $offer->getTitle() }}</h3>
          @endif
          <div class="product-rating">
            @if($offer->hasRating())
              @include('partials.rating', ["rating" => $offer->getRating()])
            @else
              @if($offer->hasOffers())
                @php $ratings = $offer->getOffers();  @endphp
                @if($ratings[0]->hasRating())
                  @include('partials.rating', ["rating" => $ratings[0]->getRating()])
                @else

                @endif
              @endif

            @endif

          </div>
          <div class="row">
            <div class="col-xs-6 col-xs-offset-3">
              @if($retailer_text && $retailer_logo)
                <div class="text-center">
                  <img class="mx-auto" src="{{$retailer_logo}}" alt="{{$retailer_text}}" />
                </div>
              @else
                <p class="retailer-text">{{$retailer_text}}</p>
              @endif
            </div>
          </div>
          <span class="price">
            <ins><span class="amount">{{ money_format($Products->getLocale(), $offer->getPrice()) }}</span></ins>
          </span>

        </div>
      </div>
    </div>
  </article>
</div>
@endif