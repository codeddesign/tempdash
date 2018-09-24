<!doctype html>
<html>
<head>
    <title>Welcome to {{ $business_name }}!</title>
</head>
<body>
<h1>Welcome!</h1>
<p>You are receiving this email because {{ $current_user->full_name }} ({{ $current_user->email }}) has registered
    you under the company, {{ $current_user->company }} for {{ $business_name }}. If you have received this
    email in error, please reply to this email stating your case. Your email address must be validated to use our site.
    To complete the email verification process, please click the
    link below or navigate to the address in your browser.</p>
<a href="{{ $verification_url }}">
    {{ $verification_url }}
</a>
</body>
</html>