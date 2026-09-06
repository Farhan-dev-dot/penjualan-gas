@php
    // Kalau halaman tidak mengirim variabel $breadcrumbs, breadcrumb dibangun
    // otomatis dari segmen URL yang sedang aktif.
    $breadcrumbs = $breadcrumbs ?? null;

    if (is_null($breadcrumbs)) {
        $breadcrumbs = [];
        $segments = request()->segments(); // contoh: ['admin', 'pelanggan', '3', 'edit']

        if (($segments[0] ?? null) === 'admin') {
            array_shift($segments);
        }

        $url = route('admin.dashboard');
        foreach ($segments as $segment) {
            if (is_numeric($segment)) {
                continue;
            }

            $url .= '/' . $segment;
            $label = Str::headline($segment); // 'data-customer' -> 'Data Customer'
            $breadcrumbs[$label] = $url;
        }
    }
@endphp

<nav aria-label="breadcrumb" class="admin-breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a>
        </li>

        @foreach ($breadcrumbs as $label => $url)
            @if ($loop->last)
                <li class="breadcrumb-item active" aria-current="page">{{ $label }}</li>
            @else
                <li class="breadcrumb-item"><a href="{{ $url }}">{{ $label }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>
