<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan / Bon Pengiriman - Depot Gas BDR</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        @page {
            size: A4 landscape;
            margin: 6mm 8mm;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #1a2472;
        }

        .navy {
            color: #1a2472;
        }

        .page-wrap {
            width: 100%;
        }

        .copy-sheet {
            border: 2px solid #1a2472;
            padding: 8px 14px;
            margin-bottom: 4px;
        }

        .copy-white {
            background: #ffffff;
        }

        .copy-yellow {
            background: #fff176;
        }

        .copy-label {
            position: absolute;
            top: 2px;
            right: 10px;
            font-size: 9px;
            font-weight: 700;
            font-style: italic;
            color: #555;
        }

        .copy-relative {
            position: relative;
        }

        /* logo */
        .logo-box {
            width: 70px;
        }

        .logo-box svg {
            width: 62px;
            height: auto;
        }

        .brand-title {
            font-weight: 900;
            font-size: 34px;
            letter-spacing: 1px;
            line-height: 1;
            margin-bottom: 3px;
            text-align: center;
        }

        .brand-sub1 {
            font-weight: 800;
            font-size: 13px;
            letter-spacing: 0.3px;
            text-align: center;
        }

        .brand-sub2 {
            font-weight: 700;
            font-size: 12.5px;
            text-align: center;
        }

        .brand-addr {
            font-weight: 700;
            font-size: 11.5px;
            text-align: center;
        }

        .kepada-box {
            font-weight: 700;
            font-size: 10.5px;
            line-height: 1.6;
            text-align: left;
        }

        .kepada-box .kepada-title {
            text-align: center;
        }

        .kepada-box .dotline {
            border-bottom: 1px dotted #1a2472;
            display: inline-block;
            min-width: 90px;
            height: 12px;
        }

        .kepada-box .dotline-full {
            border-bottom: 1px dotted #1a2472;
            display: block;
            width: 100%;
            height: 13px;
        }

        .sj-title-row {
            font-weight: 800;
            font-size: 13px;
            border-top: 2.5px solid #1a2472;
            border-bottom: 1px solid #1a2472;
            padding: 3px 0;
            margin-top: 3px;
        }

        .sj-title-row .sub {
            font-weight: 600;
            font-size: 10.5px;
        }

        table.sj-table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }

        table.sj-table th,
        table.sj-table td {
            border: 1px solid #1a2472;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
            font-weight: 700;
            padding: 1px 3px;
        }

        table.sj-table thead th {
            font-size: 10px;
            padding: 2px;
        }

        .col-no {
            width: 4%;
        }

        .col-botol {
            width: 8%;
        }

        .col-banyak {
            width: 8%;
        }

        .col-jenis {
            width: 26%;
        }

        .col-harga {
            width: 14%;
        }

        .col-jumlah {
            width: 16%;
        }

        .sj-table tbody td {
            height: 15px;
        }

        .terms {
            font-size: 8px;
            font-style: italic;
            line-height: 1.35;
        }

        .terms .judul {
            font-weight: 800;
        }

        .terms ol {
            padding-left: 14px;
            margin-bottom: 2px;
        }

        .bank-box {
            border: 1.5px solid #1a2472;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 800;
            padding: 3px 6px;
        }

        .rekap {
            font-size: 10px;
            font-weight: 800;
        }

        .rekap-line {
            border-bottom: 1px solid #1a2472;
            display: inline-block;
            width: 100%;
            height: 13px;
        }

        .ttd {
            font-size: 9px;
            font-weight: 700;
        }

        .ttd .garis {
            border-bottom: 1px dotted #1a2472;
            display: inline-block;
            width: 75%;
            height: 24px;
        }

        .ttd.text-start .garis {
            margin-left: 0;
        }

        .ttd.text-end .garis {
            margin-right: 0;
        }

        .cut-line {
            position: relative;
            text-align: center;
            border-top: 1.5px dashed #444;
            margin: 3px 0 3px 0;
            height: 5px;
        }

        .cut-line .scissor {
            position: absolute;
            top: -9px;
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            padding: 0 8px;
            font-size: 13px;
            font-weight: 700;
            color: #444;
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


    <div class="page-wrap px-2" id="template-root">

        <!-- ============ COPY 1 (PUTIH) ============ -->
        <div class="copy-sheet copy-white copy-relative copy-slot" data-copy="1">


            <div class="row align-items-start">
                <div class="col-auto logo-box">
                    <svg viewBox="0 0 60 130" xmlns="http://www.w3.org/2000/svg">
                        <rect x="24" y="2" width="12" height="10" fill="#1a2472" />
                        <path d="M18 12 h24 v14 a10 10 0 0 1 -6 9 v6 h-12 v-6 a10 10 0 0 1 -6 -9 z" fill="none"
                            stroke="#1a2472" stroke-width="3" />
                        <rect x="10" y="41" width="40" height="70" rx="4" fill="none" stroke="#1a2472"
                            stroke-width="3" />
                        <rect x="4" y="105" width="52" height="10" fill="#1a2472" />
                        <text x="30" y="82" font-family="Arial" font-weight="900" font-size="22" fill="#1a2472"
                            text-anchor="middle">BDR</text>
                    </svg>
                </div>
                <div class="col">
                    <div class="brand-title navy">DEPOT GAS BDR</div>
                    <div class="brand-sub1 navy">MENYEDIAKAN BERMACAM-MACAM GAS INDUSTRI DAN MEDICAL</div>
                    <div class="brand-sub2 navy">Oxygen (O²), Argon (AR), Nitrogen (N²), Acetylene (C²H²), Dll</div>
                    <div class="brand-addr navy mt-1">
                        Jl. Raya Cilincing No. 01 Rt. 01/04 Kel. Cilincing - Jakarta Utara<br>
                        Telp. 0812 8215 4310, 0822 6099 2336
                    </div>
                </div>
                <div class="col-auto kepada-box" style="width:200px;">
                    <div>{{ now()->format('d/m/Y') }}</div>
                    <div class="kepada-title mt-1"><strong>Kepada Yth.</strong></div>
                    <div>{{ $detail?->nama_penerima ?? '-' }}</div>
                    <div>{{ $detail?->alamat_penerima ?? '-' }}</div>
                </div>
            </div>

            <div class="sj-title-row d-flex justify-content-between navy">
                <div>SURAT JALAN / BON PENGIRIMAN <span class="sub">&nbsp; Harap diterima kiriman kami : Isi /
                        Kosong</span></div>
                <div>No. : {{ $pembelian->kode_penjualan }}</div>
            </div>

            <table class="sj-table mt-1">
                <thead>
                    <tr>
                        <th rowspan="2" class="col-no">No.</th>
                        <th colspan="2">Nomor Botol</th>
                        <th rowspan="2" class="col-banyak">Banyaknya</th>
                        <th rowspan="2" class="col-jenis">Jenis Gas</th>
                        <th rowspan="2" class="col-harga">Harga Satuan</th>
                        <th rowspan="2" class="col-jumlah">J u m l a h</th>
                    </tr>
                    <tr>
                        <th class="col-botol">Isi</th>
                        <th class="col-botol">Kosong</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksis as $index => $transaksi)
                        @php
                            $detailItem = $pembelian->details->firstWhere('id_produk', $transaksi->id_produk);
                            $hargaSatuan = $detailItem?->subtotal && $detailItem?->jumlah
                                ? $detailItem->subtotal / $detailItem->jumlah
                                : 0;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $transaksi->stok_isi ?: '-' }}</td>
                            <td>{{ $transaksi->stok_kosong ?: '-' }}</td>
                            <td>{{ $transaksi->stok_isi + $transaksi->stok_kosong + $transaksi->stok_pinjam }}</td>
                            <td>{{ $transaksi->produk?->jenis_gas ?? '-' }} {{ $transaksi->produk?->berat }}
                                {{ $transaksi->produk?->satuan }}</td>
                            <td>Rp {{ number_format($hargaSatuan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($detailItem?->subtotal ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    @for ($index = $transaksis->count(); $index < 4; $index++)
                        <tr>
                            <td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td>
                        </tr>
                    @endfor
                </tbody>
            </table>

            <div class="row mt-1">
                <div class="col-6 terms navy">
                    <div><strong>Sisa Pinjaman Botol Kosong</strong></div>
                    <div>
                        Mulai Sewa: {{ $detail?->mulai_sewa?->format('d/m/Y') ?? '-' }}
                        s/d {{ $detail?->akhir_sewa?->format('d/m/Y') ?? '-' }}
                        = {{ $detail?->jumlah ?? 0 }} Botol
                    </div>
                    <div class="judul">Perhatian !!! Kami selaku pembeli berjanji</div>
                    <ol>
                        <li>Botol2 lewat dari 1 bulan belum kembali kami anggap hilang dan harus mengganti untuk tiap
                            botol Acetylene Rp. 1.500.000,- (Satu Juta Lima Ratus Ribu Rupiah) Oxygen Rp. 1.500.000,-
                            (Satu Juta Lima Ratus Ribu Rupiah)</li>
                        <li>Lama botol pinjaman 15 hari. Lewat dari 15 hari botol tersebut akan diambil atau denda Rp.
                            150.000,- (Seratus Lima Puluh Ribu Rupiah)</li>
                        <li>Mengganti Rp. 350.000,- (Tiga Ratus Lima Puluh Ribu Rupiah) untuk kran yang rusak/penyok
                        </li>
                        <li>Klaim bocor 1 x 24 jam</li>
                        <li>Uang Jaminan diambil setelah 1 minggu tabung kembali</li>
                        <li>Uang jaminan dipotong 150.000,- perbulan untuk biaya sewa</li>
                    </ol>
                    <div class="mt-1">Diterima oleh,</div>
                </div>

                <div class="col-3">
                    <div class="rekap navy">
                        <div class="row mb-1">
                            <div class="col-5">Jumlah Rp.</div>
                            <div class="col-7">{{ number_format($transaksis->sum(function ($transaksi) use ($pembelian) {
                                return $pembelian->details->firstWhere('id_produk', $transaksi->id_produk)?->subtotal ?? 0;
                            }), 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-3 d-flex align-items-end justify-content-center">
                    <div class="navy" style="font-size:10px; font-weight:700;">Hormat kami,</div>
                </div>
            </div>

            <!-- Baris tanda tangan: 3 slot merata selebar halaman -->
            <div class="row ttd-row mt-4">
                <div class="col-4 ttd navy text-start">
                    <span class="garis"></span><br>
                    ( .......................... )<br>
                    <strong>Tanda Tangan &amp; Nama Jelas</strong>
                </div>
                <div class="col-4 ttd navy text-center">
                    <span class="garis"></span><br>
                    ( .......................... )
                </div>
                <div class="col-4 ttd navy text-end">
                    <span class="garis"></span><br>
                    ( .......................... )
                </div>
            </div>
        </div>

        <div class="cut-line"><span class="scissor">✂ - - - - - - - - - - - - - - - - GUNTING DI SINI - - - - - - - - -
                - - - - - - - ✂</span></div>

        <!-- ============ COPY 2 (KUNING) - duplikat via JS ============ -->
        <div class="copy-sheet copy-yellow copy-relative copy-slot" data-copy="2" id="copy2-placeholder"></div>

    </div>

    <script>
        // Duplikasi isi lembar 1 ke lembar 2, lalu ganti label & warna
        const source = document.querySelector('.copy-slot[data-copy="1"]');
        const target = document.getElementById('copy2-placeholder');
        target.innerHTML = source.innerHTML;

        window.addEventListener('load', function() {
            window.setTimeout(function() {
                window.print();
            }, 250);
        });
    </script>

</body>

</html>
