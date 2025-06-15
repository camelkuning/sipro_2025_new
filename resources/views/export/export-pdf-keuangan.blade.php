<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan Tahun {{ $tahunDipilih }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px;
            border: 1px solid #000;
            text-align: left;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>Laporan Keuangan Tahun {{ $tahunDipilih }}</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>
                    Tipe
                    <select id="filter-tipe" style="width: auto; float: right;">
                        <option value="">Semua</option>
                        <option value="debit">Debit</option>
                        <option value="kredit">Kredit</option>
                    </select>
                </th>
                <th>Jumlah (Rp)</th>
                <th>Keterangan</th>
                <th>Saldo Awal (Rp)</th>
                <th>Saldo Akhir (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataKeuangan as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                    <td>{{ ucfirst($item->tipe) }}</td>
                    <td>{{ number_format($item->jumlah ?: 0, 2, ',', '.') }}</td>
                    <td>{{ $item->keterangan }}</td>
                    <td>{{ number_format($item->saldo_awal ?: 0, 2, ',', '.') }}</td>
                    <td>{{ number_format($item->saldo_akhir ?: 0, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>
    <strong>Total Kredit Keuangan Umum :</strong> Rp {{ number_format($totalKredit, 0, ',', '.') }} <br>
    <strong>Total Debit Keuangan Umum:</strong> Rp {{ number_format($totalDebit, 0, ',', '.') }} <br>
    <strong>Total Debit Program Kerja :</strong> Rp {{ number_format($debitProker, 0, ',', '.') }} <br>
    <strong>Saldo Akhir :</strong> Rp {{ number_format($totalSaldo, 0, ',', '.') }}
</body>

</html>
