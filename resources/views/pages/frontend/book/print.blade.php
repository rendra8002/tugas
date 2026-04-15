<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        /* Pengaturan Kertas */
        @page {
            margin: 0;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }

        /* Tabel Utama sebagai Wrapper (Kunci agar Center & Simetris) */
        .main-table {
            width: 100%;
            height: 100%;
            border: none;
        }

        .content-cell {
            padding-top: 50px;
            vertical-align: top;
            align: center;
        }

        /* Kotak Struk */
        .container {
            width: 520px;
            border: 1px solid #333;
            padding: 40px;
            margin-left: auto;
            margin-right: auto;
            text-align: left;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        h2 {
            margin: 0;
            text-transform: uppercase;
            font-size: 20px;
        }

        .status-box {
            display: inline-block;
            padding: 5px 12px;
            border: 1px solid #333;
            font-weight: bold;
            margin-top: 10px;
            font-size: 11px;
        }

        /* Tabel Data */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            text-align: left;
            width: 40%;
            padding: 12px 8px;
            border-bottom: 1px solid #eee;
            background-color: #f9f9f9;
            font-size: 12px;
            color: #666;
        }

        .data-table td {
            padding: 12px 8px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
            color: #333;
        }

        .total-denda {
            font-weight: bold;
            color: #d9534f;
        }

        .no-denda {
            font-weight: bold;
            color: #5cb85c;
        }

        .lunas-label {
            font-size: 10px;
            color: #5cb85c;
            font-style: italic;
            margin-left: 4px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #888;
        }
    </style>
</head>

<body>

    <table class="main-table">
        <tr>
            <td class="content-cell" align="center">

                <div class="container">
                    <div class="header">
                        <h2>BUKTI TRANSAKSI</h2>
                        <div style="font-size: 12px; color: #777; margin-top: 5px;">
                            ID: #TRX-{{ sprintf('%05d', $peminjaman->id) }}
                        </div>
                        <div class="status-box">STATUS: {{ strtoupper($peminjaman->status) }}</div>
                    </div>

                    <table class="data-table">
                        <tr>
                            <th>Nama Anggota</th>
                            <td><strong>{{ $peminjaman->user->name }}</strong></td>
                        </tr>
                        <tr>
                            <th>Judul Buku</th>
                            <td>{{ $peminjaman->book->title }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pinjam</th>
                            <td>{{ $peminjaman->tanggal_pinjam ? date('d F Y', strtotime($peminjaman->tanggal_pinjam)) : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Jatuh Tempo</th>
                            <td>{{ $peminjaman->jatuh_tempo ? date('d F Y', strtotime($peminjaman->jatuh_tempo)) : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Kembali</th>
                            <td>{{ $peminjaman->tanggal_kembali ? date('d F Y', strtotime($peminjaman->tanggal_kembali)) : 'Belum Kembali' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Total Denda</th>
                            <td>
                                @php
                                    // Ambil angka asli database dan paksa jadi positif
                                    $rawDenda = $peminjaman->getRawOriginal('total_denda') ?? 0;
                                    $fixDenda = abs($rawDenda);
                                @endphp

                                @if ($fixDenda > 0)
                                    <span class="total-denda">Rp {{ number_format($fixDenda, 0, ',', '.') }}</span>
                                    <span class="lunas-label">(Lunas)</span>
                                @else
                                    <span class="no-denda">Rp 0</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <div class="footer">
                        <p>Terima kasih telah menggunakan layanan Perpustakaan.</p>
                        <p style="font-size: 9px; margin-top: 10px;">Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
                    </div>
                </div>

            </td>
        </tr>
    </table>

</body>

</html>
