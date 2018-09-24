<!doctype html>
<html>
    <head>
        <title>Password Recovery For {{ config('business.business_name') }}</title>
    </head>
    <body>
        <h1>Password Recovery</h1>
        <p>You are receiving this message because this email was used to request password recovery with the corresponding account
        at {{ config('business.business_name') }}. Please use the link below to create a new password.</p>
        <a href="{{ $password_verify_url }}">
            {{ $password_verify_url }}
        </a>
    </body>
</html>