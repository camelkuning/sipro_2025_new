@extends('layouts.main')
@section('title', 'Admin | Keuangan')
@section('content')
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
