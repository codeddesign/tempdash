<!doctype html>
<html>
<head>
    <title>{{ $business_name }} Email Verification</title>
</head>
<body>
<h1>Email Verification</h1>
<p>Thank you for registering for {{ $business_name }}. To complete the email verification process, please click the
    link below or navigate to the address in your browser.</p>
<a href="{{ $verification_url }}">
    {{ $verification_url }}
</a>
</body>
</html>