@php $user = Auth::getCurrentUser() @endphp
        <!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css"
          integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" type="text/css" rel="stylesheet"/>
    <link rel="icon" type="image/png" href="/img/icons/ternicon.png">
    @yield('head-scripts')
    <title>{{ env('APP_NAME') }} | {{ !empty($page_title) ? $page_title : config('business.tagline') }}</title>
</head>
<body>
<div class="page-wrap">
    <div id="main" style="width: 100%;">
        <div class="content-area">
            @yield('content')
        </div>
    </div>
</div>
<script>
    window.page = window.page || {};
    window.page.mouse_x = 0;
    window.page.mouse_y = 0;
    window.page.auth_login_path = '{{ route('auth_login', [], false) }}';
    window.page.route_name = '{{ (request()->route()) ? request()->route()->getName() : 'null' }}';
</script>
<script src="https://cdn.socket.io/socket.io-1.4.5.js"></script>
<script src="{{ asset('js/manifest.js') }}"></script>
<script src="{{ asset('js/vendor.js') }}"></script>
@if($user)
    <script>
        window.page.user = @json(Auth::getCurrentUser());
    </script>
@endif
@yield('script-vars')
@yield('inline-scripts')
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>