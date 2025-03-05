@extends('layouts.main')
@section('title', 'Admin | Add User')
@section('content')
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Daftar User</h1>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Daftar User / Anggota
                    </div>
                    <div class="card-body">
                        <table id="table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Lengkap</th>
                                    <th>Komisi / Unsur</th>
                                    <th>Username</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $data)
                                    <tr>
                                        <td>{{ $data->id }}</td>
                                        <td>{{ $data->nama_lengkap }}</td>
                                        <td>{{ $data->komisi }}</td>
                                        <td>{{ $data->username }}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm btnEditUser" data-bs-toggle="modal"
                                                data-bs-target="#modalEditUser" data-id="{{ $data->id }}">
                                                <i class="bi bi-pencil-square"></i> Ubah
                                            </button>
                                            <button class="btn btn-danger btn-sm btnHapusUser" data-id="{{ $data->id }}"
                                                onclick="deleteUser('/deleteUser/' + {{ $data->id }})">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                            data-bs-target="#modalTambahUser">
                            <i class="bi bi-plus-square"></i>&nbsp; Add User
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        function deleteUser(url) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
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

    @include('admin.add-daftaruser')
    @include('admin.edit-daftaruser')
@endsection
