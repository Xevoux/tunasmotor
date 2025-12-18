<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tunas Motor - Sparepart Motor Berkualitas')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/tmlogo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/tmlogo.png') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    @stack('styles')
</head>
<body>
    @yield('content')
    
    <!-- Scroll Top & Contact Buttons -->
    @include('layouts.utilities.scroll')
    @include('layouts.utilities.contact')
    
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>

