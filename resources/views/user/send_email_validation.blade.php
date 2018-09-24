@extends('default')
@section('script-vars')
    <script>
        window.page.resend_link = '{{ $do_resend_email_path }}';
    </script>
@endsection
@section('content')
    <div id="user_email_verification" class="container">
        <h1>Validate Your Email Address</h1>
        <p>Thank you for registering with Ternio! An email has been sent to {{ $app_user->email }} with instructions
        on verifying your email address. Please check junk/spam mail for an email from '{{ config('business.verify_email_sender') }}'.
        Please also note that the email verification link expires in {{ config('business.token_life') }} minutes, so if it has expired, click
        the "Resend Verification Email" button below to generate another one.</p>
        <button v-cloak v-bind:disabled="is_ajaxing" v-on:click="resendEmailVerification" class="btn btn-info">
            @{{ is_ajaxing ? 'Please wait...' : 'Resend Verification Email' }}
        </button>
    </div>
@endsection