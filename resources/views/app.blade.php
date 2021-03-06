<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" value="{{ csrf_token() }}" />

<title>{{ env('APP_NAME') }}</title>

<!-- Styles -->
<link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>

<body>
    @if (Auth::check())
        <script>
            window.Laravel = {!! json_encode([
    'isAuthenticated' => true,
    'user' => Auth::user(),
]) !!}
        </script>
    @else
        <script>
            window.Laravel = {!! json_encode([
    'isAuthenticated' => false,
]) !!}
        </script>
    @endif
    <div id="app">
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>
