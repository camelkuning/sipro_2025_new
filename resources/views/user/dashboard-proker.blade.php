@extends('layouts.main')
@section('title', 'User | Dashboard Proker')
@section('content')
<style>
        #datatablesSimple td:nth-child(7),
        /* anggaran digunakan */
        #datatablesSimple td:nth-child(8),
        /* tambah kebijakan */
        #datatablesSimple th:nth-child(7),
        /* Header anggaran digunakan */
        #datatablesSimple th:nth-child(8) {
            /* Header tambah kebijakan */
            text-align: right !important;
        }
    </style>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <h1>Dashboard Program Kerja</h1>
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
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card border-0 text-white card-hover" style="background-color: #2E8B57;">
                            {{-- Light Sea Green --}}
                            <div class="card-body">
                                <h6 class="card-title">Jumlah Proker Aktif {{ $tahunIni }}</h6>
                                <h5 class="fw-bold text-center">{{ $jumlahAktif }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card border-0 text-white card-hover" style="background-color: #2196F3;">
                            {{-- Light Sea Green --}}
                            <div class="card-body">
                                <h6 class="card-title">Jumlah Budget Proker</h6>
                                <h5 class="fw-bold text-center value" akhi="{{ $budget_berjalan ?? 0 }}">
                                    Rp 0
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card border-0 text-white card-hover" style="background-color: #FF9800;">
                            {{-- Light Sea Green --}}
                            <div class="card-body">
                                <h6 class="card-title">Total Anggaran Digunakan</h6>
                                <h5 class="fw-bold text-center value" akhi="{{ $jumlahDigunakan ?? 0 }}">
                                    Rp 0
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card border-0 text-white card-hover" style="background-color: #20B2AA;">
                            {{-- Light Sea Green --}}
                            <div class="card-body">
                                <h6 class="card-title">Total Dana Kebijakan</h6>
                                <h5 class="fw-bold text-center value" akhi="{{ $jumlahKebijakan ?? 0 }}">
                                    Rp 0
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-4">
                <div class="col-md-12 mb-3">
                    <div class="card equal-height " style="border-radius: 12px;">
                        <div class="card-header text-dark">
                            <h5 class="mb-0">Grafik Program Kerja dan Anggaran Digunakan</h5>
                        </div>
                        <div class="card-body d-flex justify-content-center align-items-center">
                            <canvas id="chartProkerAnggaran" height="100"></canvas>
                        </div>
                    </div>
                </div>
                <hr class="my-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-table me-1"></i>
                            Daftar Pengeluaran Program Kerja
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ is_numeric($tahunDipilih) ? 'Tahun ' . $tahunDipilih : $tahunDipilih }}

                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <a class="dropdown-item" href="{{ url('/proker') }}">Proker Sekarang</a>
                                </li>
                                @foreach ($tahunList as $thn)
                                    <li>
                                        <a class="dropdown-item" href="{{ url('/proker?tahun=' . $thn) }}">
                                            Tahun {{ $thn }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>


                    <div class="card-body shadow-sm table-responsive">
                        <table id="datatablesSimple" class="table">
                            <thead>
                                <tr>
                                    <th>akun_id</th>
                                    <th>Kode program kerja</th>
                                    <th>Program Kerja</th>
                                    <th>Komisi</th>
                                    <th>Ketua Panitia</th>
                                    {{-- <th>Majelis Pendamping</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th> --}}
                                    <th>Keterangan</th>
                                    <th>Anggaran Digunakan (Rp)</th>
                                    <th>Dana Kebijakan (Rp)</th>
                                    <th>Tahun</th>
                                    {{-- <th>Status</th> --}}
                                    <th>Detail</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($programKerja as $item)
                                    <!-- Mengambil data program kerja -->
                                    <tr>
                                        <td>{{ $item->akun->kode_akun ?? '-' }}</td>
                                        <td>{{ $item->kode_program_kerja }}</td>
                                        <td>{{ $item->nama_program_kerja }}</td>
                                        <td>{{ $item->komisi_program_kerja }}</td>
                                        <td>{{ $item->nama_ketua_program_kerja }}</td>
                                        {{-- <td>{{ $item->nama_majelis_pendamping }}</td> --}}
                                        {{-- <td>{{ $item->tanggal_mulai }}</td> --}}
                                        {{-- <td>{{ $item->tanggal_selesai }}</td> --}}
                                        <td>{{ $item->keterangan }}</td>
                                        <td>{{ number_format($item->anggaran_digunakan, 2, ',', '.') }}</td>
                                        <td>{{ number_format($item->tambahan_dana_kebijakan, 2, ',', '.') }}</td>
                                        <td>{{ $item->tahun }}</td>
                                        {{-- <td>{{ $item->status }}</td> --}}
                                        <td>
                                            <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                title="Detail Program Kerja"
                                                data-bs-target="#modalRincian{{ $item->id }}">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr class="my-2">
                <button class="btn btn-success rounded-circle shadow position-fixed floating-btn"
                    style="bottom: 20px; right: 20px; width: 60px; height: 60px; z-index: 1050;" data-bs-toggle="modal"
                    data-bs-target="#modalKalender">
                    <i class="bi bi-calendar3" style="font-size: 1.5rem;"></i>
                </button>
            </div>
        </main>
    </div>
    @push('scripts')
        <script>
            // Tutup modal detail saat modal kalender dibuka
            $('#modalDetailProker').on('show.bs.modal', function() {
                $('#modalKalender').modal('hide');
            });
        </script>
        <script>
            console.log('FullCalendar available:', typeof FullCalendar !== 'undefined');
        </script>
        <script src="{{ asset('js/calendar.js') }}"></script>
        <script>
            var eventsData = @json($events);
        </script>

        <script>
            const counters = document.querySelectorAll('.value');
            const speed = 500; // Kecepatan animasi total dalam milidetik

            counters.forEach(counter => {
                const target = +counter.getAttribute('akhi');
                let count = 0;
                const increment = Math.ceil(target / speed);

                const updateCount = () => {
                    count += increment;
                    if (count < target) {
                        counter.innerText = 'Rp ' + count.toLocaleString('id-ID');
                        setTimeout(updateCount, 1);
                    } else {
                        counter.innerText = 'Rp ' + target.toLocaleString('id-ID');
                    }
                };

                updateCount();
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
    @endpush
    @include('admin.detail-calendar')
    @include('admin.modal-kalender')
    @include('admin.detail-daftarprogram')
@endsection
