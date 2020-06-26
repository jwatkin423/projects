@foreach($files as $file)
  <li class="list-group-item">{!! link_to_route('excel-file', $file, ['file' => $file, 'path' => $path]) !!}</li>
@endforeach