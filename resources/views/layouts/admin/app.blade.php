<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    {{-- link fontawsome  --}}
    <script src="https://kit.fontawesome.com/f1bc26f6b9.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @push('styles')
        {{-- link css --}}
        <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    @endpush
</head>
@stack('styles')

<body>

    {{-- Backdrop, hanya aktif saat sidebar terbuka di layar kecil --}}
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <div class="d-flex">

        {{-- Sidebar --}}
        <div class="sidebar" id="sidebar" aria-labelledby="sidebarLabel">

            <div class="sidebar-brand">
                <div class="brand-icon">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo">
                </div>
                <div class="flex-grow-1">
                    <span class="brand-text">
                        <span class="brand-line1">BBB</span>
                        <span class="brand-line2">PT Berkat Bidara Berwibawa</span>
                    </span>
                </div>
                <button type="button" class="btn-close btn-close-white d-lg-none" onclick="closeSidebar()"
                    aria-label="Close"></button>
            </div>

            <div class="sidebar-body">
                @include('layouts.admin.sidebar')
            </div>
        </div>

        {{-- Konten utama --}}
        <div class="main-content flex-grow-1" id="mainContent">

            @include('layouts.admin.nav')

            <main class="p-4">
                @yield('content')
            </main>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const backdrop = document.getElementById('sidebarBackdrop');

        function openSidebar() {
            sidebar.classList.add('show');
            mainContent.classList.add('sidebar-open');
            if (window.innerWidth < 992) {
                backdrop.classList.add('show');
            }
        }

        function closeSidebar() {
            sidebar.classList.remove('show');
            mainContent.classList.remove('sidebar-open');
            backdrop.classList.remove('show');
        }

        function toggleSidebar() {
            sidebar.classList.contains('show') ? closeSidebar() : openSidebar();
        }

        function handleResize() {
            if (window.innerWidth >= 992) {
                openSidebar();
            } else {
                closeSidebar();
            }
        }

        window.addEventListener('DOMContentLoaded', handleResize);
        window.addEventListener('resize', handleResize);
        backdrop.addEventListener('click', closeSidebar);
    </script>

    @yield('scripts')
</body>

</html>
