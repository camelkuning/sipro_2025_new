@extends('layouts.main')
@section('title', 'Admin | Proker')
@section('content')
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Dashboard Program Kerja</h1>
                <hr class="my-4">
                <div class="row mt-3 gy-4">
                    <!-- Proker Aktif -->
                    <div class="col-xl-4 col-md-6">
                        <div class="text-white p-4 shadow-sm" style="background:  #537D5D; border-radius: 1rem;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Jumlah Proker Aktif {{ $tahunIni }}</h6>
                                <div class="bg-white rounded-circle d-flex justify-content-center align-items-center"
                                    style="width: 45px; height: 45px;">
                                    <i class="fas fa-tasks text-success fs-5"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h2 class="fw-bold text-center">{{ $jumlahAktif }}</h2>
                            </div>
                        </div>
                    </div>

                    <!-- Budget Proker -->
                    <div class="col-xl-4 col-md-6">
                        <div class="text-white p-4 shadow-sm" style="background: #F5C45E; border-radius: 1rem;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Jumlah Budget Proker</h6>
                                <div class="bg-white rounded-circle d-flex justify-content-center align-items-center"
                                    style="width: 45px; height: 45px;">
                                    <i class="fas fa-money-bill-wave text-warning fs-5"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h2 class="fw-bold text-center">Rp {{ number_format($budget_berjalan ?? 0, 0, ',', '.') }}
                                </h2>
                            </div>
                        </div>
                    </div>

                    <!-- Anggaran Digunakan -->
                    <div class="col-xl-4 col-md-6">
                        <div class="text-white p-4 shadow-sm" style="background: #9d4edd; border-radius: 1rem;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Anggaran Digunakan</h6>
                                <div class="bg-white rounded-circle d-flex justify-content-center align-items-center"
                                    style="width: 45px; height: 45px;">
                                    <i class="fas fa-chart-line text-danger fs-5"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <h2 class="fw-bold text-center">Rp {{ number_format($jumlahDigunakan ?? 0, 0, ',', '.') }}
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-4">
                <div class="row mt-4 gy-4">
                    <!-- Kolom Jadwal -->
                    <div class="col-md-4">
                        <div class="card shadow mb-2" style="border-radius: 12px;">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">Jadwal Program Kerja</h5>
                            </div>
                            <div class="card-body" style="padding: 20px;">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Chart -->
                    <div class="col-md-8">
                        <div class="card shadow mb-2" style="border-radius: 12px;">
                            <div class="card-header  text-white">
                                <h5 class="mb-0">Grafik Proker</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="chartKeuangan" width="100%" height="40"></canvas>
                            </div>
                        </div>
                    </div>
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
    @endpush
    @include('admin.detail-calendar')
@endsection
