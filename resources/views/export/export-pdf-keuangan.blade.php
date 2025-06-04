<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan Tahun {{ $tahunDipilih }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 5px; border: 1px solid #000; text-align: left; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Keuangan Tahun {{ $tahunDipilih }}</h2>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Kode Keuangan</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Saldo Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataKeuangan as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ $item->keterangan }}</td>
                    <td>{{ $item->kode_keuangan }}</td>
                    <td>{{ ucfirst($item->tipe) }}</td>
                    <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->saldo_akhir, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <strong>Total Kredit:</strong> Rp {{ number_format($totalKredit, 0, ',', '.') }} <br>
    <strong>Total Debit:</strong> Rp {{ number_format($totalDebit, 0, ',', '.') }} <br>
    <strong>Total Proker:</strong> Rp {{ number_format($debitProker, 0, ',', '.') }} <br>
    <strong>Saldo Akhir:</strong> Rp {{ number_format($totalSaldo, 0, ',', '.') }}
</body>
</html>
