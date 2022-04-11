@php
$isModel = $isModel ?? false;
$messageType = null;
$message = null;
@endphp

@if (!$isModel)
    @if ($message = session('success'))
        @php
            $messageType = 'alert-success';
        @endphp
    @elseif ($message = session('info'))
        @php
            $messageType = 'alert-info';
        @endphp
    @elseif ($message = session('warning'))
        @php
            $messageType = 'alert-warning';
        @endphp
    @elseif ($message = session('error'))
        @php
            $messageType = 'alert-danger';
        @endphp
    @endif

    @if ($message)
        @php
            $message = (object) (is_string($message) ? ['message' => $message] : $message);
        @endphp
    @endif
@endif

@if ($isModel)
    <div class="alert alert-dismissible jsAlert" role="alert">
        <div class="d-flex">
            <h4 class="alert-heading"></h4>

            <button type="button" class="close jsBtnClose ml-auto">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <p class="mb-0 alert-message"></p>
    </div>
@elseif($message)
    <div class="alert alert-dismissible {{ $message->fixed ? 'fixed-alert' : null }} {{ $messageType }} jsAlert" role="alert">
        <div class="d-flex">
            @if (!empty($message->title))
                <h4 class="alert-heading">
                    {{ $message->title }}
                </h4>
            @endif

            <button type="button" class="close jsBtnClose ml-auto">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <p class="mb-0 alert-message">
            {{ $message->message }}
        </p>
    </div>
@endif
