@extends('layouts.main')
@section('title', 'Admin | Keuangan')
@section('content')
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <div class="container-fluid px-4">
                    <h1 class="mt-4 ">Dashboard Keuangan</h1>
                    <div class="row mb-3 justify-content-start">
                        <div class="col-lg-6">
                            <div class="card mb-6">
                                <div class="card-header">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Total Penerimaan Keuangan
                                </div>
                                <div class="card-body">
                                    <canvas id="myBarChart" width="100%" height="80"></canvas>
                                </div>

                            </div>
                        </div>
                        {{-- <div class="col-lg-6">
                            <div class="card mb-6">
                                <div class="card-header">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Perbandingan Penerimaan Konveksional dan Inkonveksional
                                </div>
                                <div class="card-body">
                                    <canvas id="myBarChart2" width="100%" height="80"></canvas>
                                </div>
                               
                            </div>
                        </div> --}}
                        <div class="col-lg-4 col-md-4">
                            <div class="card bg-primary text-white mb-2">
                                <div class="card-body">Total Keseluruhan Penerimaan
                                    <h2>Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</h2>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    {{-- <a class="small text-white stretched-link" href="#">View Details</a> --}}
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Tabel Penerimaan Keuangan
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>kode_keuangan</th>
                                        <th>tanggal</th>
                                        <th>Penerimaan Konveksional</th>
                                        <th>Penerimaan Inkonveksional</th>
                                        <th>Total Penerimaan</th>
                                        <th>Jam Diisi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($keuangan as $item)
                                        <tr>
                                            <td>{{ $item->kode_keuangan }}</td>
                                            <td>{{ $item->tanggal_awal }} s/d {{ $item->tanggal_akhir }}</td>
                                            <td>Rp {{ number_format($item->konveksional ?: 0, 2, ',', '.') }}</td>
                                            <td>Rp {{ number_format($item->inkonveksional ?: 0, 2, ',', '.') }}</td>
                                            <td>Rp {{ number_format($item->total_penerimaan ?: 0, 0, ',', '.') }}</td>
                                            <td>{{ $item->waktu_input }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <a href="{{ route('download.keuangan') }}" class="btn btn-dark">
                                <i class="bi bi-download"></i>&nbsp; Download Excel
                            </a>
                        </div>
                    </div>
                </div>
        </main>
    </div>
    <input type="hidden" id="chartLabels"
        value='{{ json_encode($keuangan->map(fn($item) => $item->tanggal_awal . ' - ' . $item->tanggal_akhir)) }}'>
    <input type="hidden" id="chartData" value='{{ json_encode($keuangan->pluck('total_penerimaan')) }}'>

    <input type="hidden" id="chartLabels2"
        value='{{ json_encode($keuangan->map(fn($item) => $item->tanggal_awal . ' - ' . $item->tanggal_akhir)) }}'>
    <input type="hidden" id="chartKonveksional" value='{{ json_encode($keuangan->pluck('konveksional')) }}'>
    <input type="hidden" id="chartInkonveksional" value='{{ json_encode($keuangan->pluck('inkonveksional')) }}'>

@endsection
