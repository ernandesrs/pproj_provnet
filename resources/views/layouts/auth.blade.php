<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ config('app.name', 'Laravel') }} | @yield("title")
    </title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset("css/bootstrap-icons.css") }}">
    <link href="{{ asset('css/auth/custom.css') }}" rel="stylesheet">
</head>

<body>
    <div class="wrapp">
        <div class="d-flex justify-content-center container">
            @yield("content")
        </div>
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/auth/scripts.js') }}"></script>
</body>

</html>
