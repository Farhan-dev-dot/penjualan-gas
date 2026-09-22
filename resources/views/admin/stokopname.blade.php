@extends('layouts.admin.app')

@section('title', 'Stok Opname')

@section('content')
    <x-breadcrumb />

    <div class="container-fluid px-0">
        <div class="content-card">
            <div class="content-card-header">
                <h6 class="content-card-title">Stok Opname <small>Periksa dan sesuaikan stok fisik dengan stok sistem</small>
                </h6>
            </div>

            @if (session('success'))
                <div class="alert alert-success mx-4 mt-3 mb-0">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger mx-4 mt-3 mb-0">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('admin.stok-opname') }}" method="GET" class="table-toolbar">
                <div class="input-group search-box">
                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="kode_produk" class="form-control" placeholder="Cari kode produk..."
                        value="{{ request('kode_produk') }}">
                </div>
                <button type="submit" class="btn-filter"><i class="fa-solid fa-search"></i> Cari Produk</button>
            </form>

            @if ($produk)
                <form action="{{ route('admin.stok-opname.store') }}" method="POST" id="formStockOpname"
                    class="p-4 border-top" onsubmit="confirmStockOpname(event)">
                    @csrf
                    <input type="hidden" name="id_produk" value="{{ $produk->id_produk }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-12">
                            <h6 class="mb-0">{{ $produk->jenis_gas }} <span
                                    class="text-muted">({{ $produk->kode_produk }})</span></h6>
                        </div>
                        @foreach (['isi' => 'Stok Isi', 'kosong' => 'Stok Kosong', 'pinjam' => 'Stok Pinjam', 'rusak' => 'Stok Rusak'] as $key => $label)
                            <div class="col-6 col-lg-3">
                                <label class="form-label">{{ $label }} - Sistem</label>
                                <input id="stok_{{ $key }}_sistem" class="form-control"
                                    value="{{ $produk->{'stok_' . $key} }}" readonly>
                                <label class="form-label mt-2">Fisik {{ $label }}</label>
                                <input type="number" min="0" name="stok_{{ $key }}_fisik"
                                    id="stok_{{ $key }}_fisik" class="form-control" required>
                                <label class="form-label mt-2">Selisih</label>
                                <input name="selisih_{{ $key }}" id="selisih_{{ $key }}"
                                    class="form-control" readonly>
                            </div>
                        @endforeach
                        <div class="col-12"><label class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-4"><label class="form-label">Tanggal Opname</label><input type="date"
                                name="tanggal_opname" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                        <div class="col-md-4 form-check ms-3"><input type="checkbox" name="penyesuaian" value="1"
                                id="penyesuaian" class="form-check-input"><label for="penyesuaian"
                                class="form-check-label">Sesuaikan stok produk dengan stok fisik</label></div>
                        <div class="col-12"><button class="btn btn-primary" type="submit"><i
                                    class="fa-solid fa-floppy-disk me-1"></i> Simpan Opname</button></div>
                    </div>
                </form>
            @elseif (request('kode_produk'))
                <div class="alert alert-warning m-4">Produk dengan kode tersebut tidak ditemukan.</div>
            @endif

            <div class="table-responsive">
                <table class="table w-100 mb-0">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Tanggal</th>
                            <th>Selisih Isi</th>
                            <th>Selisih Kosong</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($opnames as $opname)
                            <tr>
                                <td>{{ $opname->produk?->jenis_gas ?? '-' }}</td>
                                <td>{{ $opname->tanggal_opname?->format('d M Y') }}</td>
                                <td>{{ $opname->selisih_isi }}</td>
                                <td>{{ $opname->selisih_kosong }}</td>
                                <td class="text-start">
                                    <div class="row-actions justify-content-start">
                                        <button type="button" class="btn-icon" title="Detail" data-bs-toggle="modal"
                                            data-bs-target="#ModalDetailOpname"
                                            data-produk="{{ $opname->produk?->jenis_gas ?? '-' }}"
                                            data-tanggal="{{ $opname->tanggal_opname?->format('d M Y H:i') }}"
                                            data-keterangan="{{ $opname->keterangan ?? '-' }}"
                                            data-isi-sistem="{{ $opname->stok_isi_sistem }}"
                                            data-isi-fisik="{{ $opname->stok_isi_fisik }}"
                                            data-isi-selisih="{{ $opname->selisih_isi }}"
                                            data-kosong-sistem="{{ $opname->stok_kosong_sistem }}"
                                            data-kosong-fisik="{{ $opname->stok_kosong_fisik }}"
                                            data-kosong-selisih="{{ $opname->selisih_kosong }}"
                                            data-pinjam-sistem="{{ $opname->stok_pinjam_sistem }}"
                                            data-pinjam-fisik="{{ $opname->stok_pinjam_fisik }}"
                                            data-pinjam-selisih="{{ $opname->selisih_pinjam }}"
                                            data-rusak-sistem="{{ $opname->stok_rusak_sistem }}"
                                            data-rusak-fisik="{{ $opname->stok_rusak_fisik }}"
                                            data-rusak-selisih="{{ $opname->selisih_rusak }}">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>

                                        <form id="delete-form-{{ $opname->id_stok_opname }}"
                                            action="{{ route('admin.stok-opname.destroy', $opname) }}" method="POST"
                                            class="d-inline form-delete"
                                            onsubmit="return confirm('Yakin ingin menghapus data stok opname ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-icon btn-icon-danger"
                                                onclick="deleteBarang({{ $opname->id_stok_opname }})" title="Hapus">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="table-empty"><i class="fa-solid fa-clipboard-check"></i>
                                        <p>Belum ada data stok opname</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="table-footer">{{ $opnames->links() }}</div>
        </div>
    </div>

    <div class="modal fade cm-modal" id="ModalDetailOpname" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content cm-content">
                <div class="modal-header cm-header">
                    <div class="cm-header-left">
                        <div class="cm-avatar"><i class="fa-solid fa-clipboard-check"></i></div>
                        <div>
                            <h6 class="cm-title">Detail Stok Opname</h6>
                        </div>
                    </div>
                    <button type="button" class="btn-close cm-btn-close-x" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>
                <div class="modal-body cm-body">
                    <div class="cm-field"><span class="cm-field-label">Produk</span><strong id="d-produk">-</strong>
                    </div>
                    <div class="cm-field"><span class="cm-field-label">Tanggal</span><strong id="d-tanggal">-</strong>
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table table-bordered align-middle mb-0 text-center">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th>Sistem</th>
                                    <th>Fisik</th>
                                    <th>Selisih</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (['isi' => 'Stok Isi', 'kosong' => 'Stok Kosong', 'pinjam' => 'Stok Pinjam', 'rusak' => 'Stok Rusak'] as $key => $label)
                                    <tr>
                                        <td class="text-start">{{ $label }}</td>
                                        <td id="d-{{ $key }}-sistem">-</td>
                                        <td id="d-{{ $key }}-fisik">-</td>
                                        <td id="d-{{ $key }}-selisih">-</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="cm-field cm-field-last mt-3"><span class="cm-field-label">Keterangan</span><span
                            id="d-keterangan">-</span></div>
                </div>
                <div class="modal-footer cm-footer"><button type="button" class="btn btn-secondary cm-btn-close"
                        data-bs-dismiss="modal">Tutup</button></div>
            </div>
        </div>
    </div>

    @include('admin.components.scriptsstokopname')
@endsection
