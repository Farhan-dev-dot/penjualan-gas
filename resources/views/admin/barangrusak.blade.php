@extends('layouts.admin.app')

@section('title', 'Barang Rusak')
@section('page-title', 'Barang Rusak')

@section('content')
    <x-breadcrumb />

    <div class="container-fluid px-0">
        <section class="data-barang-rusak">
            <div class="content-card">
                <div class="content-card-header">
                    <h6 class="content-card-title">
                        Data Barang Rusak
                        <small>Kelola jumlah produk yang rusak atau tidak layak digunakan</small>
                    </h6>
                    <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#ModalBarangRusak">
                        <i class="fa-solid fa-plus"></i> Tambah Barang Rusak
                    </button>
                </div>

                @if (session('success'))
                    <div class="alert alert-success mx-4 mt-3 mb-0">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.barang-rusak') }}" method="GET" class="table-toolbar">
                    <div class="input-group search-box">
                        <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari produk..."
                            value="{{ request('search') }}">
                    </div>
                    <button type="submit" class="btn-filter"><i class="fa-solid fa-filter"></i> Filter</button>
                    @if (request('search'))
                        <a href="{{ route('admin.barang-rusak') }}" class="btn-filter btn-reset">
                            <i class="fa-solid fa-rotate-left"></i> Reset
                        </a>
                    @endif
                </form>

                <div class="table-responsive">
                    <table class="table w-100 mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Kode</th>
                                <th class="text-center">Jumlah Rusak</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($produks as $produk)
                                <tr>
                                    <td class="cell-primary">{{ $produk->jenis_gas }} ({{ $produk->berat }}
                                        {{ $produk->satuan }})</td>
                                    <td class="cell-muted">{{ $produk->kode_produk }}</td>
                                    <td class="text-center"><span
                                            class="stok-badge stok-rusak">{{ $produk->stok_rusak }}</span></td>
                                    <td class="text-end">
                                        <button type="button" class="btn-icon btn-icon-danger"
                                            title="Kembalikan barang rusak" data-bs-toggle="modal"
                                            data-bs-target="#ModalHapusBarangRusak"
                                            data-id-produk="{{ $produk->id_produk }}"
                                            data-nama-produk="{{ $produk->jenis_gas }} ({{ $produk->kode_produk }})"
                                            data-stok-rusak="{{ $produk->stok_rusak }}"
                                            {{ $produk->stok_rusak < 1 ? 'disabled' : '' }}>
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="table-empty"><i class="fa-solid fa-box-open"></i>
                                            <p>Belum ada data produk</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <span class="table-info">Menampilkan {{ $produks->firstItem() ?? 0 }}–{{ $produks->lastItem() ?? 0 }}
                        dari {{ $produks->total() }} data</span>
                    {{ $produks->links() }}
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="ModalBarangRusak" tabindex="-1" aria-labelledby="BarangRusakModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.barang-rusak.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="BarangRusakModalLabel">Tambah Barang Rusak</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="id_produk" class="form-label">Produk</label>
                            <select name="id_produk" id="id_produk" class="form-select" required>
                                <option value="">Pilih produk</option>
                                @foreach ($produks as $produk)
                                    <option value="{{ $produk->id_produk }}">{{ $produk->jenis_gas }}
                                        ({{ $produk->kode_produk }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Nama Petugas</label>
                            <input type="text" class="form-control" value="{{ auth('admin')->user()?->name ?? '-' }}"
                                readonly>
                        </div>
                        <div class="mt-3">
                            <label for="kondisi" class="form-label">Kondisi tabung yang rusak</label>
                            <select name="kondisi" id="kondisi" class="form-select" required>
                                <option value="">Pilih kondisi</option>
                                <option value="stok_isi">Tabung berisi rusak/bocor (Stok Isi)</option>
                                <option value="stok_kosong">Tabung kosong rusak (Stok Kosong)</option>
                                <option value="stok_pinjam">Tabung pinjaman customer rusak (Stok Pinjam)</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <label for="jumlah" class="form-label">Jumlah Rusak</label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control" min="1"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i>
                            Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade cm-modal" id="ModalHapusBarangRusak" tabindex="-1"
        aria-labelledby="HapusBarangRusakModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content cm-content">
                <form id="formHapusBarangRusak" action="{{ route('admin.barang-rusak.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id_produk" id="hapus-id-produk">

                    <div class="cm-header">
                        <div class="cm-header-left">
                            <div class="cm-avatar bg-danger"><i class="fa-solid fa-trash"></i></div>
                            <div>
                                <h5 class="cm-title" id="HapusBarangRusakModalLabel">Hapus Barang Rusak</h5>
                                <span class="cm-subtitle">Kembalikan sebagai stok kosong</span>
                            </div>
                        </div>
                        <button type="button" class="btn-close cm-btn-close-x" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                    </div>

                    <div class="cm-body">
                        <div class="alert alert-warning py-2 mb-3">
                            Produk: <strong id="hapus-nama-produk">-</strong>
                        </div>
                        <div>
                            <label for="hapus-jumlah" class="form-label">Jumlah dikembalikan ke stok kosong</label>
                            <input type="number" name="jumlah" id="hapus-jumlah" class="form-control" min="1"
                                value="1" required>
                            <small class="text-muted">Maksimal barang rusak produk ini: <span
                                    id="hapus-stok-rusak">0</span></small>
                        </div>
                    </div>

                    <div class="cm-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash me-1"></i>
                            Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('ModalHapusBarangRusak');
                if (!modal) return;

                modal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    document.getElementById('hapus-id-produk').value = button.dataset.idProduk;
                    document.getElementById('hapus-nama-produk').textContent = button.dataset.namaProduk;
                    document.getElementById('hapus-stok-rusak').textContent = button.dataset.stokRusak;
                    document.getElementById('hapus-jumlah').max = button.dataset.stokRusak;
                });
            });
        </script>
    @endpush
@endsection
