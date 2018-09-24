@extends('no-menu-default')
@section('script-vars')
    <script>
        window.page.login_url = '{{ $login_url }}';
        window.page.whence = '{{ $whence }}';
    </script>
@endsection
@section('content')
    <div id="auth" class="screen-height">
        <div class="info-section">
            <div class="content">
                <h2 class="slogan">Real-time Advertising Ecosystem</h2>
                <p class="about-section">
                    Ternio has built the world's fastest blockchain - completely decentralized, auditable
                    and onchain - to prevent fraud, instantly pay publishers and provide transparency in
                    the programmatic digital advertising ecosystem.
                </p>
                <div class="dividers">
                    <div class="divider one"></div>
                    <div class="divider two"></div>
                    <div class="divider three"></div>
                </div>
                <h5 class="site-title">
                    <a href="ternio.io">www.ternio.io</a>
                </h5>
                <div class="social-media-icons clearfix">
                    <ul>
                        <li><a target="_blank" href="https://www.facebook.com/terniotoken"><img src="/img/icons/facebook.svg"/></a></li>
                        <li><a target="_blank" href="https://twitter.com/Terniotoken"><img src="/img/icons/twitter.svg"/></a></li>
                        <li><a target="_blank" href="https://www.youtube.com/channel/UCqNyT4SjTzsJ1FF2dpUI_TA"><img src="/img/icons/youtube.svg"/></a></li>
                        <li><a target="_blank" href="https://www.linkedin.com/company/ternio-token"><img src="/img/icons/linkedin.svg"/></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="form-section">
            <div class="form-title-area">
                <div class="logo-section">
                    <a href="/">
                        <img class="logo" src="/img/ternio-logo.svg"/>
                    </a>
                    <h2>Log Into Your Account</h2>
                </div>
                <form v-on:submit="handleSubmitLogin" class="login-form">
                    <div class="form-group required">
                        <label>Email ID</label>
                        <input v-model="email" type="text" placeholder="yourname@company.com"/>
                        <ul v-cloak v-if="errors['email']" class="invalid-form-feedback">
                            <li v-for="error in errors['email']">@{{ error }}</li>
                        </ul>
                    </div>
                    <div class="form-group required">
                        <label>Password</label>
                        <input v-model="password" type="password"/>
                        <ul v-cloak v-if="errors['password']" class="invalid-form-feedback">
                            <li v-for="error in errors['password']">@{{ error }}</li>
                        </ul>
                    </div>
                    <div class="remember-me-section clearfix">
                        <div class="remember-me-group">
                            <input v-model="remember" type="checkbox"/>
                            <label>Keep me signed in</label>
                        </div>
                        <a href="{{ route('auth_recovery_password', [], false) }}" class="forgot-password">Forgot Password?</a>
                    </div>
                    <button v-bind:disabled="is_ajaxing" v-text="is_ajaxing ? 'Please wait...' : 'Login'" class="confirm-button" type="submit"></button>
                </form>
            </div>
            <div class="copyright-notice">
                {{ date('Y') }} Copyright &copy; <span class="company">TERNIO</span>
            </div>
        </div>
    </div>
@endsection