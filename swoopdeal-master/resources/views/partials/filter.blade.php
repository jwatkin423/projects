<form method="get" id="filters-form" class="form-inline order-by">
    <div class="form-control pull-right">
      @if($options->hasSortOptions())
        <div class="pull-right"><b><label for="sortby">Sort By:</label></b>
          <select id="sortby" name="sortby">
            @foreach($options->getSortOptions() as $key=>$name)
              <option @if($options->getSortBy() == $key) selected="selected" @endif value="{{ $key }}">
                {{ $name }}
              </option>
            @endforeach
          </select>
        </div>
      @endif
    </div>
    <div class="change-order pull-left">
      <div class="sort"><b><label for="sortby">Show:</label></b>
        <select class="form-control" name="rpp" id="rpp">
          @foreach($options->getRppOptions() as $rpp)
            <option @if($options->getRpp() == $rpp) selected="selected" @endif value="{{ $rpp }}">
              {{ $rpp }}
            </option>
          @endforeach
        </select>
      </div>
    </div>
    @if($options->hasQ())
      {!! Form::hidden('q', $options->getQ()) !!}
    @endif
</form>

