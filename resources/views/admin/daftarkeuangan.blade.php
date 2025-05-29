@extends('layouts.main')
@section('title', 'Admin | Add Keuangan')
@section('content')
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4 ">Tabel Daftar Keuangan</h1>
                <hr class="my-4">
                <div class="card mb-4 shadow-sm" style="width: 30rem;">
                    <div class="card-header">
                        <p>Filter per tanggal</p>
                    </div>
                    <div class="card-body shadow-sm">
                        <form method="GET" action="{{ route('admin.daftarkeuangan') }}" class="mb-3" id="filterForm">
                            <div class="d-flex flex-column flex-md-row gap-3">
                                <div class="flex-grow-1">
                                    <label for="dari_tanggal">Dari Tanggal</label>
                                    <input type="text" name="dari_tanggal" class="form-control" id="dariTanggal"
                                        placeholder="dd/mm/yyyy" autocomplete="off" value="{{ request('dari_tanggal') }}">
                                </div>
                                <div class="flex-grow-1">
                                    <label for="sampai_tanggal">Sampai Tanggal</label>
                                    <input type="text" name="sampai_tanggal" class="form-control" id="sampaiTanggal"
                                        placeholder="dd/mm/yyyy" autocomplete="off" value="{{ request('sampai_tanggal') }}">
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary me-2">🔍 Tampilkan</button>
                                <button type="button" class="btn btn-outline-secondary" id="resetTanggal">🔄 Reset
                                    Tanggal</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-table me-1"></i>
                            Tabel Penerimaan dan Pengeluaran Keuangan
                        </div>
                        @if (request('dari_tanggal') && request('sampai_tanggal'))
                            <p><strong>Periode:</strong> {{ request('dari_tanggal') }} - {{ request('sampai_tanggal') }}</p>
                        @endif
                        {{-- //untuk menampilkan tahun yang dipilih --}}
                        <div class="dropdown">
                            <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ is_numeric($tahunDipilih) ? 'Tahun ' . $tahunDipilih : $tahunDipilih }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <a class="dropdown-item" href="{{ url('/daftarKeuangan') }}">Keuangan Sekarang</a>
                                </li>
                                @foreach ($tahunList as $thn)
                                    <li>
                                        <a class="dropdown-item" href="{{ url('/daftarKeuangan?tahun=' . $thn) }}">
                                            Tahun {{ $thn }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        {{-- <form id="filterForm" action="{{ url('/daftarKeuangan') }}" method="GET">
                            <select name="tahun" id="filterTahun" class="form-select">
                                <option value="">Pilih Tahun</option>
                                @foreach ($tahunList as $thn)
                                    <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>
                                        Tahun {{ $thn }}
                                    </option>
                                @endforeach
                            </select>
                                
                        </form>  --}}
                    </div>
                    <div class="card-body shadow-sm">
                        @if (count($bulanBelumDiproses) > 0)
                            <div class="mb-4">
                                {{-- <h5>Proses Pembagian Bulanan yang Belum Diproses</h5> --}}
                                @foreach ($bulanBelumDiproses as $bln)
                                    @php
                                        $logPembagian = \App\Models\LogPembagianBulanan::where('tahun', $bln->tahun)
                                            ->where('bulan', $bln->bulan)
                                            ->first();
                                    @endphp

                                    @if (!$logPembagian || $logPembagian->status != 'selesai')
                                        <!-- Hanya tampilkan tombol jika status belum selesai -->
                                        <form action="{{ route('proses.pembagian.bulanan') }}" method="POST"
                                            class="d-inline-block mb-2 me-2">
                                            @csrf
                                            <input type="hidden" name="tahun" value="{{ $bln->tahun }}">
                                            <input type="hidden" name="bulan" value="{{ $bln->bulan }}">
                                            <button type="submit" class="btn btn-warning">
                                                Proses Bulan
                                                {{ \Carbon\Carbon::create($bln->tahun, $bln->bulan)->translatedFormat('F Y') }}
                                            </button>
                                        </form>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        {{-- <form method="GET" action="{{ route('admin.daftarkeuangan') }}">
                        <select name="akun_id" onchange="this.form.submit()">
                            <option value="">Semua Akun</option>
                            @foreach ($daftarAkun as $akun)
                                <option value="{{ $akun->akun_id }}" {{ request('akun_id') == $akun->akun_id ? 'selected' : '' }}>
                                    {{ $akun->nama_akun }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit">Filter</button>
                    </form> --}}

                        <table id="datatablesSimple" class="table table-bordered shadow-sm">
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
                                    <th>Saldo Awal</th>
                                    <th>Saldo Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- <p>Total data: {{ dd($dataKeuangan) }}</p> --}}
                                @forelse ($dataKeuangan as $item)
                                {{-- {{ dd($dataKeuangan); }} --}}
                                    <tr>
                                        <td>{{ $item->akun->kode_akun ?? '-' }}</td>
                                        <td>{{ $item->kode_keuangan }}</td>
                                        <td>{{ $item->tanggal }}</td>
                                        <td>{{ ucfirst($item->tipe) }}</td>
                                        <td>{{ number_format($item->jumlah ?: 0, 2, ',', '.') }}</td>
                                        <td>{{ $item->keterangan }}</td>
                                        <td>Rp {{ number_format($item->saldo_awal ?: 0, 2, ',', '.') }}</td>
                                        <td>Rp {{ number_format($item->saldo_akhir ?: 0, 2, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Data tidak ditemukan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        @php
                            $sinode = $acuan['Sinode']->persentase ?? 0;
                            $klasis = $acuan['Klasis']->persentase ?? 0;
                            $proker = $acuan['Program Kerja']->persentase ?? 0;
                            $belanja = $acuan['Belanja Rutin Gereja']->persentase ?? 0;
                        @endphp

                        {{-- <div class="container">
                                <div class="row align-items-start">
                                    <div class="col">
                                        <p>{{ $sinode }}%</p>
                                    </div>
                                    <div class="col">
                                        <p>{{ $klasis }}%</p>
                                    </div>
                                    <div class="col">
                                        <p>{{ $proker }}%</p>
                                    </div>
                                    <div class="col">
                                        <p>{{ $belanja }}%</p>
                                    </div>
                                </div>
                            </div> --}}

                        <p><strong>Total Saldo Keseluruhan :</strong> Rp {{ number_format($totalSaldo, 2, ',', '.') }}
                        </p>
                        <p><strong>Total Kredit :</strong> Rp {{ number_format($totalKredit, 2, ',', '.') }}</p>
                        <p><strong>Total Debit Keuangan Umum :</strong> Rp
                            {{ number_format($totalDebit, 2, ',', '.') }}</p>
                        <p><strong>Total Debit Keuangan Program Kerja :</strong> Rp
                            {{ number_format($debitProker, 2, ',', '.') }}</p>

                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#modalTambahKeuangan">
                            <i class="bi bi-plus-square"></i>&nbsp; Add Keuangan
                        </button>
                        <a href="{{ route('download.keuangan') }}" class="btn btn-dark">
                            <i class="bi bi-download"></i>&nbsp; Download Keuangan
                        </a>
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
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterTahun = document.getElementById('filterTahun');
            if (filterTahun) {
                filterTahun.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            }
        });
    </script> --}}
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
    @include('admin.add-daftarkeuangan')
@endsection
