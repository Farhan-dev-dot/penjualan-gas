<nav class="navbar navbar-expand sticky-top">
    <div class="container-fluid">

        {{-- Tombol toggle sidebar (selalu tampil, di semua ukuran layar) --}}
        <button class="btn btn-toggle me-3" type="button" onclick="toggleSidebar()" aria-label="Toggle sidebar">
            <i class="fa-solid fa-bars fs-5"></i>
        </button>


        <div class="ms-auto d-flex align-items-center gap-2">



            {{-- User dropdown --}}
            <div class="dropdown">
                @php($admin = auth('admin')->user())
                <button class="user-chip" type="button" data-bs-toggle="dropdown">
                    <span class="user-avatar">{{ strtoupper(substr($admin->name, 0, 2)) }}</span>
                    <span class="d-none d-md-block text-start">
                        <span class="user-name d-block">{{ $admin->name }}</span>
                        <span class="user-role">{{ ucfirst($admin->role ?? 'Administrator') }}</span>
                    </span>
                    <i class="fa-solid fa-chevron-down small text-muted d-none d-md-inline"></i>
                </button>
            </div>
        </div>
    </div>
</nav>
