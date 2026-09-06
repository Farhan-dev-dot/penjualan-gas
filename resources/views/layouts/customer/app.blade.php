<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- link fontawsome  --}}
    <script src="https://kit.fontawesome.com/f1bc26f6b9.js" crossorigin="anonymous"></script>
    @push('styles')
        {{-- link css --}}
        <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    @endpush
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
@stack('styles')

<body>
    @include('layouts.customer.nav')

    @yield('content')
    @include('layouts.customer.footer')

    @include('layouts.customer.cart')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>
