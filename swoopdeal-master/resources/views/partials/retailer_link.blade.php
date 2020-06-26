<a href="{{ $offer->getUrl() }}" target="_blank">
    @if($offer->hasRetailerLogo())
        <img src="{{ $offer->getRetailerLogo() }}" alt="{{ $offer->getRetailerText() }}"
             title="{{ $offer->getRetailerText() }}"/>
    @else
        {{ $offer->getRetailerText() }}
    @endif
</a>
