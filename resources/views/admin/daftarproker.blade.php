@extends('layouts.main')
@section('title', 'Admin | add Proker')
@section('content')
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4 ">
                <h1 class="mt-4">Pengeluaran Program Kerja</h1>
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
                                    <a class="dropdown-item" href="{{ url('/daftarProker') }}">Proker Sekarang</a>
                                </li>
                                @foreach ($tahunList as $thn)
                                    <li>
                                        <a class="dropdown-item" href="{{ url('/daftarProker?tahun=' . $thn) }}">
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
                                    <th>Anggaran Digunakan</th>
                                    <th>Dana Kebijakan</th>
                                    <th>Tahun</th>
                                    {{-- <th>Status</th> --}}
                                    <th>Ubah</th>
                                    <th>Tambah Dana</th>
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
                                        <td>Rp {{ number_format($item->anggaran_digunakan, 2, ',', '.') }}</td>
                                        <td>Rp {{ number_format($item->tambahan_dana_kebijakan, 2, ',', '.') }}</td>
                                        <td>{{ $item->tahun }}</td>
                                        {{-- <td>{{ $item->status }}</td> --}}
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                title="Edit Program Kerja"
                                                data-bs-target="#modalEditProgramKerja{{ $item->id }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                title="Tambah Kebijakan"
                                                data-bs-target="#modalTambahKebijakan-{{ $item->id }}">
                                                <i class="bi bi-plus-square"></i>
                                            </button>
                                        </td>
                                        {{-- <td>
                                            <button class="btn btn-danger btn-sm d-flex justify-content-center"
                                                title="Hapus Program Kerja"
                                                onclick="deleteProgramKerja('/deleteproker/{{ $item->id }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td> --}}
                                        <td>
                                            <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                title="Detail Program Kerja"
                                                data-bs-target="#modalRincian{{ $item->id }}">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </td>

                                    </tr>
                                    @include('admin.edit-daftarprogram')
                                    @include('admin.add-kebijakan')
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="card p-4 bg-white mb-4 shadow-sm">
                                    <h2 class="text-sm font-semibold mb-2">Informasi Anggaran Program Kerja</h2>
                                    <p><strong>Tahun :</strong> {{ $tahunBudget ?? 'Belum ada' }}</p>
                                    <p><strong>Budget Program Kerja :</strong> Rp
                                        {{ number_format($budget_berjalan ?? 0, 0, ',', '.') }}
                                    </p>
                                    <p><strong>Alokasi Tahun Depan :</strong> Rp
                                        {{ number_format($alokasi_tahun_depan ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>


                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#modalTambahProgramKerja">
                            <i class="bi bi-plus-square"></i>&nbsp; Add Program Kerja
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        function deleteProgramKerja(url) {
            Swal.fire({
                title: 'Yakin Mau Menghapus Data Ini?',
                text: "Data yang dihapus tidak bisa kembali!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    </script>
    @include('admin.add-daftarprogram')
    @include('admin.detail-daftarprogram')
@endsection
