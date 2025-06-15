@extends('layouts.main')
@section('title', 'User | Dashboard Keuangan')
@section('content')
<style>
        #datatablesSimple td:nth-child(5),
        /* Jumlah */
        #datatablesSimple td:nth-child(7),
        /* Saldo Awal */
        #datatablesSimple td:nth-child(8),
        /* Saldo Akhir */
        #datatablesSimple th:nth-child(5),
        /* Header Jumlah */
        #datatablesSimple th:nth-child(7),
        /* Header Saldo Awal */
        #datatablesSimple th:nth-child(8) {
            /* Header Saldo Akhir */
            text-align: right !important;
        }
    </style>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <h1>Dashboard Keuangan</h1>
                    <div class="text-end">
                        <div class="d-flex align-items-center justify-content-end">
                            <i class="far fa-calendar-alt me-2"></i>
                            <span id="tanggal">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-end">
                            <i class="far fa-clock me-2"></i>
                            <span id="jam">{{ \Carbon\Carbon::now()->format('HH:mm:ss') }}</span>
                        </div>
                    </div>
                </div>
                <hr class="my-4">

                <div class="row">
                    <!-- Total Saldo -->
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card border-0 text-white card-hover" style="background-color: #2E8B57;">
                            {{-- Sea Green --}}
                            <div class="card-body">
                                <h6 class="card-title">Total Saldo Keseluruhan</h6>
                                <h5 class="fw-bold">
                                    Rp {{ number_format($totalSaldo, 2, ',', '.') }}
                                </h5>
                            </div>
                        </div>
                    </div>

                    <!-- Total Kredit -->
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card border-0 text-white card-hover" style="background-color: #1E90FF;">
                            {{-- Dodger Blue --}}
                            <div class="card-body">
                                <h6 class="card-title">Total Kredit Keuangan Umum</h6>
                                <h5 class="fw-bold">
                                    Rp {{ number_format($totalKredit, 2, ',', '.') }}
                                </h5>
                            </div>
                        </div>
                    </div>

                    <!-- Total Debit Umum -->
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card border-0 text-white card-hover" style="background-color: #FFA500;">
                            {{-- Orange --}}
                            <div class="card-body">
                                <h6 class="card-title">Total Debit Keuangan Umum</h6>
                                <h5 class="fw-bold">
                                    Rp {{ number_format($totalDebit, 2, ',', '.') }}
                                </h5>
                            </div>
                        </div>
                    </div>

                    <!-- Total Debit Program Kerja -->
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card border-0 text-white card-hover" style="background-color: #20B2AA;">
                            {{-- Light Sea Green --}}
                            <div class="card-body">
                                <h6 class="card-title">Total Debit Program Kerja</h6>
                                <h5 class="fw-bold">
                                    Rp {{ number_format($debitProker, 2, ',', '.') }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card border-0 text-white card-hover" style="background-color: #9E9D24;">
                            {{-- Light Sea Green --}}
                            <div class="card-body">
                                <h6 class="card-title">Persentase Pembagian Ke Sinode</h6>
                                <h5 class="fw-bold text-center">
                                    {{ $sinode }}%
                                </h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card border-0 text-white card-hover" style="background-color: #607D8B;">
                            {{-- Light Sea Green --}}
                            <div class="card-body">
                                <h6 class="card-title">Persentase Pembagian Ke Klasis</h6>
                                <h5 class="fw-bold text-center">
                                    {{ $klasis }}%
                                </h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card border-0 text-white card-hover" style="background-color: #E91E63;">
                            {{-- Light Sea Green --}}
                            <div class="card-body">
                                <h6 class="card-title">Persentase Pembagian Ke Program Kerja</h6>
                                <h5 class="fw-bold text-center">
                                    {{ $program }}%
                                </h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card border-0 text-white card-hover" style="background-color: #795548;">
                            {{-- Light Sea Green --}}
                            <div class="card-body">
                                <h6 class="card-title">Persentase Pembagian Ke Belanja Rutin</h6>
                                <h5 class="fw-bold text-center">
                                    {{ $belanja }}%
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">
                <div class="row chart-container align-items-stretch">
                    <!-- Pie Chart -->
                    <div class="col-md-4 mb-3 d-flex">
                        <div class="card card-hover w-100 d-flex flex-column">
                            <div class="card-header text-dark">
                                <h5 class="mb-0">Persentase Pembagian Keuangan</h5>
                            </div>
                            <div
                                class="card-body d-flex flex-column justify-content-center align-items-center p-2 flex-grow-1">
                                <canvas id="pieChart" style="max-width: 100%; max-height: 100%;"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Grafik Proker -->
                    <div class="col-md-8 mb-3 d-flex">
                        <div class="card card-hover w-100 d-flex flex-column" style="border-radius: 12px;">
                            <div class="card-header text-dark">
                                <h5 class="mb-0">Grafik Kredit dan Debit Keuangan</h5>
                            </div>
                            <div class="card-body d-flex justify-content-center align-items-center flex-grow-1">
                                <canvas id="chartKeuangan" width="100%" height="40"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-table me-1"></i>
                            Tabel Penerimaan dan Pengeluaran Keuangan
                        </div>
                        @if (request('dari_tanggal') && request('sampai_tanggal'))
                            <p><strong>Periode : </strong> {{ request('dari_tanggal') }} - {{ request('sampai_tanggal') }}
                            </p>
                        @endif
                        {{-- //untuk menampilkan tahun yang dipilih --}}
                        <div class="dropdown">

                            <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ is_numeric($tahunDipilih) ? 'Tahun ' . $tahunDipilih : $tahunDipilih }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <a class="dropdown-item" href="{{ url('/keuangan') }}">Keuangan Sekarang</a>
                                </li>
                                @foreach ($tahunList as $thn)
                                    <li>
                                        {{-- {{ dd($thn) }} --}}
                                        <a class="dropdown-item" href="{{ url('/keuangan?tahun=' . $thn) }}">
                                            Tahun {{ $thn }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="card-body ">
                        <table id="datatablesSimple" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>akun_id</th>
                                    <th>Kode Transaksi</th>
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
                                @forelse ($dataKeuangan as $item)
                                    <tr>
                                        <td>{{ $item->akun->kode_akun ?? '-' }}</td>
                                        <td>{{ $item->kode_keuangan }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                                        <td>{{ ucfirst($item->tipe) }}</td>
                                        <td>{{ number_format($item->jumlah ?: 0, 2, ',', '.') }}</td>
                                        <td>{{ $item->keterangan }}</td>
                                        <td>{{ number_format($item->saldo_awal ?: 0, 2, ',', '.') }}</td>
                                        <td>{{ number_format($item->saldo_akhir ?: 0, 2, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Data tidak ditemukan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="card mb-4 shadow-sm" style="width: 25rem;">
                            <div class="card-header">
                                <p>Filter per tanggal</p>
                            </div>
                            <div class="card-body shadow-sm">
                                <form method="GET" action="{{ route('user.dashboard.keuangan') }}" class="mb-3"
                                    id="filterForm">
                                    <input type="hidden" name="tahun" value="{{ request('tahun') }}">

                                    <div class="d-flex flex-column flex-md-row gap-3">
                                        <div class="flex-grow-1">
                                            <label for="dari_tanggal">Dari Tanggal</label>
                                            <input type="text" name="dari_tanggal" class="form-control"
                                                id="dariTanggal" placeholder="dd/mm/yyyy" autocomplete="off"
                                                value="{{ request('dari_tanggal') }}">
                                        </div>
                                        <div class="flex-grow-1">
                                            <label for="sampai_tanggal">Sampai Tanggal</label>
                                            <input type="text" name="sampai_tanggal" class="form-control"
                                                id="sampaiTanggal" placeholder="dd/mm/yyyy" autocomplete="off"
                                                value="{{ request('sampai_tanggal') }}">
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary me-2">🔍 Tampilkan</button>
                                        <button type="button" class="btn btn-outline-secondary" id="resetTanggal">🔄
                                            Reset
                                            Tanggal</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body ">
                                <form action="{{ route('keuangan.exportpdf') }}" method="GET" class="mt-3">
                                    <input type="hidden" name="dari_tanggal" value="{{ request('dari_tanggal') }}">
                                    <input type="hidden" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}">
                                    <input type="hidden" name="tahun" value="{{ request('tahun', $tahunDipilih) }}">

                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-file-earmark-pdf"></i> Export PDF
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('filterTahun').addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });

        function updateDropdownText(text) {
            document.getElementById("dropdownMenuButton").textContent = text;
            if (dropdownButton) {
                dropdownButton.innerText = text;
            }
        }
    </script>
    <script>
        $(function() {
            $("#dariTanggal, #sampaiTanggal").datepicker({
                dateFormat: 'dd/mm/yy',
                changeMonth: true,
                changeYear: true
            });
        });
    </script>
    <script>
        document.getElementById('resetTanggal').addEventListener('click', function() {
            document.getElementById('dariTanggal').value = '';
            document.getElementById('sampaiTanggal').value = '';
            document.getElementById('filterForm').submit();
        });
    </script>
    <script>
        function updateJam() {
            const waktu = new Date();
            const jam = waktu.getHours().toString().padStart(2, '0');
            const menit = waktu.getMinutes().toString().padStart(2, '0');
            const detik = waktu.getSeconds().toString().padStart(2, '0');
            document.getElementById('jam').textContent = `${jam}:${menit}:${detik}`;
        }

        setInterval(updateJam, 1000);
        updateJam(); // jalankan langsung saat halaman dimuat
    </script>

@endsection
