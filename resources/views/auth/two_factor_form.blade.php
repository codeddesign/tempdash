@extends('no-menu-default')
@section('script-vars')
    <script>
        window.page.whence = '{{ $whence }}';
        window.page.resend_code_link = '{{ route('do_auth_resend_two_factor_code', ['id' => $id], false) }}'
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
            <div class="form-title-area two-factor-verification">
                <div class="logo-section">
                    <a href="/">
                        <img class="logo" src="/img/ternio-logo.svg"/>
                    </a>
                    <h2>Verify Your Identity</h2>
                </div>
                <form class="two-factor-form" v-on:submit="handleSubmitTwoFactor">
                    <div class="form-group">
                        <p class="two-factor-help">A 7-digit numeric code was sent as a text message to your mobile phone, please enter that code below to verify your identity.</p>
                        <label>Verification Code</label>
                        <input v-model="two_factor_verification_code" class="form-control two-factor-input" type="text" name="verification_code"/>
                        <ul v-cloak v-if="errors['two_factor_verification_code']" class="invalid-form-feedback">
                            <li v-for="error in errors['two_factor_verification_code']">@{{ error }}</li>
                        </ul>
                    </div>
                    <div class="go-back-login">
                        <a href="{{ route('auth_login', [], false) . ($whence ? '?whence=' . urlencode($whence) : '') }}">Go Back To Login</a>
                    </div>
                    <div class="form-group button-section">
                        <button class="confirm-button" v-bind:disabled="is_ajaxing || !two_factor_verification_code"
                                type="submit" v-text="is_ajaxing ? 'Please wait...' : 'Validate Code'"></button>

                        <button v-on:click="sendTwoFactorCodeAgain($event)" class="send-again-button" v-bind:disabled="is_ajaxing"
                                 v-text="is_ajaxing ? 'Please wait...' : 'Send Again'"></button>

                        <div v-cloak v-if="success_message" class="success">
                            <a v-on:click="closeSuccessMessage($event)" href="">
                                <i class="fa fa-times-circle"></i>
                            </a>
                            <span v-text="success_message"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="copyright-notice">
                {{ date('Y') }} Copyright &copy; <span class="company">TERNIO</span>
            </div>
        </div>
    </div>
@endsection