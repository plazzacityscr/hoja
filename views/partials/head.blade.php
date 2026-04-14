<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!-- Security Headers -->
<meta http-equiv="X-Content-Type-Options" content="nosniff">
<meta http-equiv="Referrer-Policy" content="strict-origin-when-cross-origin">
<meta http-equiv="Permissions-Policy" content="camera=(), microphone=(), geolocation=()">

<!-- Favicon -->
<link rel="icon" href="{{ asset('assets/favicon.svg') }}" type="image/svg+xml">
<link rel="icon" href="{{ asset('assets/favicon-32x32.svg') }}" type="image/svg+xml" sizes="32x32">
<link rel="icon" href="{{ asset('assets/favicon-16x16.svg') }}" type="image/svg+xml" sizes="16x16">
<link rel="icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">

<!-- Apple Touch Icon -->
<link rel="apple-touch-icon" href="{{ asset('assets/apple-touch-icon.svg') }}" sizes="180x180">

<!-- Theme colors -->
<meta name="theme-color" content="rgb(42, 63, 84)">
<meta name="msapplication-TileColor" content="rgb(42, 63, 84)">

<!-- Title -->
<title>@yield('title', 'Hoja - Dashboard')</title>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<!-- Gentelella CSS -->
<link href="{{ asset('assets/css/gentelella.css') }}?v={{ time() }}" rel="stylesheet">

<!-- Test CSS -->
<link href="{{ asset('assets/css/test.css') }}?v={{ time() }}" rel="stylesheet">

<!-- Additional CSS -->
@stack('styles')
