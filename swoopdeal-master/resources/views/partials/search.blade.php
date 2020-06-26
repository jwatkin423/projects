<div class="product-search">
  <div class="row">
    {!! Form::open(['method' => 'get',]) !!}
    <div class="col-sm-3 col-sm-offset-1">

        <div class="form-group">
          <label>Category</label>
          <select class="form-control">
            <option value="Blah"> Blah blah</option>
          </select>
        </div>
        <div class="form-group">
          <label>Sub Category 1</label>
          <select class="form-control">
            <option value="Blah"> Blah blah</option>
          </select>
        </div>
        <div class="form-group">
          <label>Sub Category 2</label>
          <select class="form-control">
            <option value="Blah"> Blah blah</option>
          </select>
        </div>

    </div>
    <div class="col-sm-3 col-sm-offset-1">
      <div class="form-group">
        <label>Maximum Price</label>
        <input type="text" class="form-control">
      </div>
      <div class="form-group">
        <label>Minimum Price</label>
        <input type="text" class="form-control">
      </div>
      <div class="form-group">
        <label>Minimum Rating</label>
        <select class="form-control">
          <option value="1"> 1 </option>
        </select>
      </div>
    </div>
    <div class="col-sm-3 col-sm-offset-1">
      <div class="form-group">
        <label>Sort By</label>
        <select class="form-control">
          <option value="1"> 1 </option>
        </select>
      </div>
      <div class="form-group">
        <br><br><br><br><br>
        <button type="submit" class="btn btn-primary pull-right">Search</button>
      </div>
    </div>
  </div>
  {!! Form::close() !!}
</div>