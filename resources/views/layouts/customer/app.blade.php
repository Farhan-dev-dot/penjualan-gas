<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- link fontawsome  --}}
    <script src="https://kit.fontawesome.com/f1bc26f6b9.js" crossorigin="anonymous"></script>
    <title>Bootstrap demo</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    {{-- link css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    @stack('styles')
</head>

<body>
    @include('layouts.customer.nav')

    @yield('content')
    @include('layouts.customer.footer')

    @include('layouts.customer.cart')

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
