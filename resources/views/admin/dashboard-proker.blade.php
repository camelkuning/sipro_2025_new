@extends('layouts.main')
@section('title', 'Admin | Proker')
@section('content')
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
                    <!-- Jumlah Proker Aktif -->
                    <div class="col-md-6 col-xl-4 mb-4">
                        <div class="card shadow border-0 text-white card-hover"
                            style="background: #2E8B57; border-radius: 1rem;">
                            <div class="card-body">
                                <h6 class="mb-0">Jumlah Proker Aktif {{ $tahunIni }}</h6>
                                <h2 class="fw-bold text-center">{{ $jumlahAktif }}</h2>
                            </div>
                        </div>
                    </div>

                    <!-- Jumlah Budget Proker -->
                    <div class="col-md-6 col-xl-4 mb-4">
                        <div class="card shadow border-0 text-white card-hover"
                            style="background: #2196F3; border-radius: 1rem;">
                            <div class="card-body">
                                <h6 class="mb-0">Jumlah Budget Proker</h6>
                                <h2 class="fw-bold text-center value" akhi="{{ $budget_berjalan ?? 0 }}">
                                    Rp 0
                                </h2>
                            </div>
                        </div>
                    </div>

                    <!-- Anggaran Digunakan -->
                    <div class="col-md-6 col-xl-4 mb-4">
                        <div class="card shadow border-0 text-white card-hover"
                            style="background: #FF9800; border-radius: 1rem;">
                            <div class="card-body">
                                <h6 class="mb-0">Anggaran Digunakan</h6>
                                <h2 class="fw-bold text-center value" akhi="{{ $jumlahDigunakan ?? 0 }}">
                                    Rp 0
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                        <div class="card equal-height " style="border-radius: 12px;">
                            <div class="card-header text-white" style="background-color: #2E8B57;">
                                <h5 class="mb-0">Grafik Keuangan</h5>
                            </div>
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <canvas id="chartProkerAnggaran" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                

                <hr class="my-2">
                <div class="row mt-4 gy-4">
                    <!-- Kolom Jadwal -->
                    
                    <!-- Tombol Mengambang -->
                    <button class="btn btn-success rounded-circle shadow position-fixed"
                        style="bottom: 20px; right: 20px; width: 60px; height: 60px; z-index: 1050;" data-bs-toggle="modal"
                        data-bs-target="#modalKalender">
                        <i class="bi bi-calendar3" style="font-size: 1.5rem;"></i>
                    </button>

                </div>

        </main>
    </div>
    @push('scripts')
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
@endsection
