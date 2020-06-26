@if(session()->has('message') || session()->has('error'))

        @if(session()->has('message'))
            @php
                $message = session()->get('message');
                $message = array("type" => "success", "message" => $message);
            @endphp
        @endif
        @if(Session()->has('error'))
            @php
                $message = session()->get('error');
                $message = array("type" => "error", "message" => $message);
            @endphp
        @endif
        @php
            $types = [
                "success" => "success",
                "error" => "danger"
            ];
            $type = $types[$message['type']];

            session()->forget('message');
            session()->forget('error');
            session()->flush();

        @endphp
    <div class="alert alert-{{ $type }}">
        <strong>{{ ucfirst($message['type']).'!' }}</strong> {{ $message['message'] }}
    </div>
@endif
