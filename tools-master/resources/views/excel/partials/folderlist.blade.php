@foreach($folders as $folder)
  <li class="list-group-item">{!! link_to_route('folder-contents', $folder, [$folder]) !!}</li>
@endforeach