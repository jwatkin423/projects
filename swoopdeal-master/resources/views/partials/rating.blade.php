@if($rating->hasScore())
  @if($rating->getUrl())
    <a href="{{ $rating->getUrl() }}" target="_blank">
      @endif

      <div class="product-rating">
        @for($x=1; $x <= $rating->getRawScore(); $x++)
          <i class="fa fa-star"></i>
        @endfor
        @if (strpos($rating->getRawScore(),'.'))
          <i class="fa fa-star-half-full"></i>
          @php $x++; @endphp
        @endif

        @while ($x<=5)

          <i class="fa fa-star-o"></i>
          @php $x++; @endphp
        @endwhile

      </div>

      @if($rating->getUrl())
    </a>
  @endif
@endif
