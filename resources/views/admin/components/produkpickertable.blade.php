<table class="table w-100 mb-0">
    <thead>
        <tr>
            <th>Kode Produk</th>
            <th>Jenis Gas</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($produks as $produkItem)
            <tr class="produk-picker-row" style="cursor:pointer" data-id-produk="{{ $produkItem->id_produk }}"
                data-kode-produk="{{ $produkItem->kode_produk }}" data-jenis-gas="{{ $produkItem->jenis_gas }}">
                <td>{{ $produkItem->kode_produk }}</td>
                <td>{{ $produkItem->jenis_gas }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2">
                    <div class="table-empty"><i class="fa-solid fa-box-open"></i>
                        <p>Produk tidak ditemukan</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="table-footer">{{ $produks->links() }}</div>
