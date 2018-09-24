<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css"
          integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" type="text/css" rel="stylesheet"/>
    <link rel="icon" type="image/png" href="/img/icons/ternicon.png">
    <title>{{ env('APP_NAME') }} | {{ !empty($page_title) ? $page_title : config('business.tagline') }}</title>
</head>
<body class="auth">
    <div class="content-area">
        @yield('content')
    </div>
<script>
    window.page = window.page || {};
    window.page.auth_login_path = '{{ route('auth_login', [], false) }}';
    window.page.route_name = '{{ (request()->route()) ? request()->route()->getName() : 'null' }}';
</script>
<script src="{{ asset('js/manifest.js') }}"></script>
<script src="{{ asset('js/vendor.js') }}"></script>
@yield('script-vars')
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>