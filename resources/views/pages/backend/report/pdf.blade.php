<!DOCTYPE html>
<html>

<head>
    <title>Laporan Perpustakaan - {{ ucfirst($period) }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #333;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 2px solid #333;
            display: inline-block;
        }

        .footer {
            text-align: right;
            margin-top: 30px;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>LAPORAN TRANSAKSI PERPUSTAKAAN</h2>
        <p>Periode: {{ ucfirst($period) }} | Dicetak pada: {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="section-title">A. Riwayat Peminjaman & Pengembalian</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Peminjam</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->book->title }}</td>
                    <td>{{ $item->tanggal_pinjam ? \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') : '-' }}
                    </td>
                    <td>{{ $item->jatuh_tempo ? \Carbon\Carbon::parse($item->jatuh_tempo)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') : '-' }}
                    </td>
                    <td>{{ ucfirst($item->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">B. Riwayat Denda</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Anggota</th>
                <th>Buku</th>
                <th>Total Denda</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $totalDenda = 0; @endphp
            @foreach ($fines as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->book->title }}</td>
                    <td>Rp {{ number_format($item->total_denda, 0, ',', '.') }}</td>
                    <td>Lunas</td>
                </tr>
                @php $totalDenda += $item->total_denda; @endphp
            @endforeach
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="3" style="text-align: right;">Total Keseluruhan Denda:</td>
                <td colspan="2">Rp {{ number_format($totalDenda, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak otomatis oleh Sistem Manajemen Perpustakaan</p>
    </div>
</body>

</html>
