<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- link fontawsome  --}}
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">
    <title>Bootstrap demo</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    {{-- link css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    @stack('styles')
</head>

<body>
    @include('layouts.customer.nav')

    @yield('content')
    @include('layouts.customer.cart')
    @include('layouts.customer.footer')

    {{-- Markup keranjang tetap di body, bukan di head. --}}

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/refillstate.js') }}"></script>
    <script src="{{ asset('assets/js/cart-script.js') }}"></script>


</body>

</html>
