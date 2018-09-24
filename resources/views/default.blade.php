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
    @include('components/menu')
    <div id="main">
        <header>
            <div class="upper-header clearfix">
                <div id="layout-title-section" class="title-section">
                    <div style="display: flex;">
                        <a v-on:click="openMenu($event)" v-cloak href="" class="expand-menu" v-if="show_menu_expand">
                            <img src="/img/menu_icons/expand.svg"/>
                        </a>
                        <div>
                            <h1>{{ $user->company->name }}</h1>
                            <h2>Realtime Supply Chain Monitoring</h2>
                        </div>
                    </div>
                </div>
                <div class="profile-pic-section">
                    <a class="profile-pic" href="">
                        <img src="/img/daniel.png"/>
                    </a>
                </div>
            </div>
            <div class="lower-header clearfix">
                <div class="current-page-notice">
                    {{ $sub_title ?? '' }}
                </div>
                <div class="date-range-section">
                    <div id="layout_date_selector" class="dates">
                        <ul>
                            <li v-bind:class="{selected: selected_range === 'yesterday'}"><a v-on:click="selectRange($event, 'yesterday')" href="">Yesterday</a> <div class="disc"></div></li>
                            <li v-bind:class="{selected: selected_range === 'last7'}"><a v-on:click="selectRange($event, 'last7')" href="">Last 7</a> <div class="disc"></div></li>
                            <li v-bind:class="{selected: selected_range === 'last30'}"><a v-on:click="selectRange($event, 'last30')" href="">Last 30</a> <div class="disc"></div></li>
                            <li v-bind:class="{selected: selected_range === 'thisyear'}"><a v-on:click="selectRange($event, 'thisyear')" href="">This Year</a> <div class="disc"></div></li>
                            <li v-on:click="doActivateTimeframe($event)"><a href="">Enter Timeframe</a></li>
                        </ul>
                    </div>
                    <div v-cloak id="live_toggle" class="live-toggle">
                        <span class="label">Live</span> <img v-on:click="toggle" v-bind:src="is_live ? '/img/icons/on_switch.svg' : '/img/icons/off_switch.svg'"/>
                    </div>
                </div>
            </div>
        </header>
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