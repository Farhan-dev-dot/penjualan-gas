<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Stok Depot Gas BDR</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #000;
        }

        .header-wrap {
            position: relative;
            min-height: 95px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header-info {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            font-weight: bold;
            font-size: 15px;
            line-height: 1.9;
            white-space: nowrap;
        }

        .title-block {
            text-align: center;
        }

        .title-main {
            font-weight: 800;
            font-size: 30px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .title-sub {
            font-weight: 700;
            font-size: 20px;
            text-transform: uppercase;
            line-height: 1.3;
        }

        table.kartu-stok {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }

        table.kartu-stok th,
        table.kartu-stok td {
            border: 1px solid #000;
            text-align: center;
            vertical-align: middle;
            padding: 2px 3px;
            font-size: 12px;
            font-weight: 700;
        }

        table.kartu-stok thead th {
            font-size: 13px;
        }

        .col-tgl {
            width: 80px;
        }

        .col-small {
            width: 45px;
        }

        .col-ket {
            width: auto;
        }

        .empty-row td {
            height: 24px;
        }

        .group-heading {
            font-size: 14px;
            font-weight: 800;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>



    <div class="container-fluid px-4">

        <!-- Header Info -->
        <div class="header-wrap mb-3">
            <div class="header-info">
                Nama&nbsp;&nbsp;&nbsp;&nbsp;: {{ $headerDetail?->nama_penerima ?? '-' }}<br>
                Alamat&nbsp;&nbsp;: {{ $headerDetail?->alamat_penerima ?? '-' }}<br>
                No. Tlp. : {{ $headerDetail?->telepon_penerima ?? '-' }}
            </div>
            <div class="title-block">
                <div class="title-main">Kartu Stok Depot Gas BDR</div>
                <div class="title-sub">Laporan Stok Tabung</div>
                <div class="title-sub">Depot Gas BDR</div>
            </div>
        </div>

        <!-- Table -->
        <table class="kartu-stok mt-3">
            <thead>
                <tr>
                    <th rowspan="4" class="col-tgl">Tgl</th>
                    <th colspan="{{ $produks->count() }}" class="group-heading">Tabung Isi</th>
                    <th colspan="{{ $produks->count() }}" class="group-heading">Tabung Kosong</th>
                    <th colspan="{{ $produks->count() }}" class="group-heading">Stok</th>
                    <th rowspan="4" class="col-ket">Keterangan</th>
                </tr>
                <tr>
                    @foreach ($produks as $produk)
                        <th rowspan="2" class="col-small">
                            {{ $produk->jenis_gas }}<br>{{ $produk->berat }} {{ $produk->satuan }}
                        </th>
                    @endforeach
                    @foreach ($produks as $produk)
                        <th rowspan="2" class="col-small">
                            {{ $produk->jenis_gas }}<br>{{ $produk->berat }} {{ $produk->satuan }}
                        </th>
                    @endforeach
                    @foreach ($produks as $produk)
                        <th rowspan="2" class="col-small">
                            {{ $produk->jenis_gas }}<br>{{ $produk->berat }} {{ $produk->satuan }}
                        </th>
                    @endforeach
                </tr>
                <tr></tr>
            </thead>
            <tbody id="tbody-rows">
                @foreach ($kartuStoks as $item)
                    @php
                        $transaksi = $item->barangTransaksi;
                        $isMasuk = in_array($transaksi?->jenis_transaksi, ['masuk', 'retur', 'pengembalian'], true);
                        $faktorMutasi = $isMasuk ? 1 : -1;
                    @endphp
                    <tr>
                        <td>{{ $item->tanggal_transaksi?->format('d/m/Y') }}</td>
                        @foreach ($produks as $produk)
                            <td>
                                {{ $transaksi?->id_produk === $produk->id_produk ? $transaksi->stok_isi * $faktorMutasi : 0 }}
                            </td>
                        @endforeach
                        @foreach ($produks as $produk)
                            <td>
                                {{ $transaksi?->id_produk === $produk->id_produk ? $transaksi->stok_kosong * $faktorMutasi : 0 }}
                            </td>
                        @endforeach
                        @foreach ($produks as $produk)
                            <td>
                                {{ $transaksi?->id_produk === $produk->id_produk ? $item->stok_isi_sesudah : 0 }}
                            </td>
                        @endforeach
                        <td>{{ $transaksi?->keterangan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <script>
        const tbody = document.getElementById('tbody-rows');
        const totalCols = 1 + {{ $produks->count() * 3 }} + 1; // Tgl + kolom produk + Keterangan
        const totalRows = 22;
        const existingRows = tbody.rows.length;
        for (let r = existingRows; r < totalRows; r++) {
            const tr = document.createElement('tr');
            tr.className = 'empty-row';
            for (let c = 0; c < totalCols; c++) {
                const td = document.createElement('td');
                td.innerHTML = '&nbsp;';
                tr.appendChild(td);
            }
            tbody.appendChild(tr);
        }

        window.addEventListener('load', function() {
            window.setTimeout(function() {
                window.print();
            }, 250);
        });
    </script>

</body>

</html>
