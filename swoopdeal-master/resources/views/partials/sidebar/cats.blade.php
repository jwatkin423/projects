@if(!session('ps_categories'))
<ul class="list-unstyled" id="categories" role="tablist" aria-multiselectable="true">
  @foreach($categories as $category)
        <li class="panel"><a class="collapsed" role="button" data-toggle="collapse" data-parent="#categories" href="#{{ $category['id'] }}" aria-expanded="false" aria-controls="{{ $category['id'] }}">{{ $category['title'] }}<span>[{{ count($category['children']) }}]</span></a>
          <ul id="{{ $category['id'] }}" class="list-unstyled panel-collapse collapse" role="menu">
            @if(count($category['children']) > 0)
              @foreach($category['children'] as $child)
                <li><a href="{!! route('category', ['id' => $category['id'], 'sub_id' => $child['id']]) !!}">{{ $child['title'] }}</a></li>
              @endforeach
            @endif
          </ul>
    </li>
  @endforeach
</ul>

@else
<style>
  .widget-categories .widget-body > ul > li > a:before {
    content: "" !important;
  }

  .widget-categories .widget-body > ul > li > a {
    color: #888888;
  }
</style>

<ul class="list-unstyled" id="categories" style="list-style-type: none;">
  @foreach($categories as $category)
    <li ><a href="{!! route('category', ['id' => $category['id'], 'sub_id' => $category['id']]) !!}">{{ $category['title'] }}</a></li>
  @endforeach
</ul>
@endif