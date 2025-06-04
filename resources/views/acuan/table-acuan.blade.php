@extends('layouts.main')
@section('title', 'Admin | Kontrol Sistem')
@section('content')
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Kontrol Sistem</h1>
                <hr class="my-4">
                <div class="card mb-4 shadow-sm col-md-4">
                    <div class="card-body shadow-sm">
                        <h5 class="card-title">Arsip Semua Tabel</h5>
                        <hr class="my-4">

                        <form method="POST" action="{{ route('aproveKeuangan') }}" id="formSimpanKeuangan">
                            @csrf
                            {{-- @if ($sudah12Bulan) --}}
                                <button type="button" class="btn btn-success mt-3"
                                    id="btnKonfirmasiSimpan">Arsipkan</button>
                            {{-- @else
                                <button type="button" class="btn btn-secondary mt-3" disabled
                                    title="Data belum lengkap 12 bulan. Tidak bisa arsipkan.">Arsipkan</button>
                            @endif --}}
                        </form>

                    </div>
                </div>
                <hr class="my-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        Form Update Acuan Pembagian
                    </div>
                    <div class="card-body shadow-sm">
                        <form method="POST" action="{{ route('acuan.update') }}">
                            @csrf
                            <table class="table table-bordered shadow-sm">
                                <thead>
                                    <tr>
                                        <th>akun_id</th>
                                        <th>Kategori</th>
                                        <th>Persentase (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalPersentase = 0;
                                    @endphp

                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $item->akun->kode_akun ?? '-' }}</td>
                                            <td>{{ $item->kategori }}</td>
                                            <td>
                                                <input type="number" name="persentase[{{ $item->id }}]"
                                                    value="{{ $item->persentase }}" class="form-control" min="0"
                                                    max="100">
                                            </td>
                                        </tr>

                                        @php
                                            $totalPersentase += $item->persentase;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>

                            {{-- Menampilkan total persentase dengan warna sesuai kondisi --}}
                            <div class="mt-3">
                                @if ($totalPersentase == 100)
                                    <strong class="text-dark">Total Persentase: {{ $totalPersentase }}% ✅</strong>
                                @else
                                    <strong class="text-danger">Total Persentase: {{ $totalPersentase }}% ❌ (Periksa
                                        lagi)</strong>
                                @endif
                            </div>

                            <button class="btn btn-success mt-3">Simpan</button>
                        </form>
                    </div>
                </div>
                <hr class="my-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-table me-1"></i>
                            Daftar Akun
                        </div>
                    </div>
                    <div class="card-body shadow-sm table-responsive">
                        <table id="datatablesSimple" class="table table-bordered shadow-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode Akun</th>
                                    <th>Nama Akun</th>
                                    <th>Tipe Akun</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($akun as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->kode_akun }}</td>
                                        <td>{{ $item->nama_akun }}</td>
                                        <td>{{ $item->tipe_akun }}</td>
                                        <td>{{ $item->keterangan }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Data tidak ditemukan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#modalTambahAkun">
                            <i class="bi bi-plus-square"></i>&nbsp; Add Akun
                        </button>
                    </div>
                </div>
        </main>
    </div>
    <script>
        document.getElementById('btnKonfirmasiSimpan').addEventListener('click', function() {
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Seluruh data keuangan dan program kerja akan disimpan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754', // btn-success
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formSimpanKeuangan').submit();
                }
            });
        });
    </script>
    @include('admin.add-akun')
@endsection
