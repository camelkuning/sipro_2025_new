@extends('layouts.main')
@section('title', 'Admin | Proker')
@section('content')
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <div class="container-fluid px-4">`
                    <h1 class="mt-4">Dashboard Program Kerja</h1>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            contoh tabelnya
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-striped ">
                                <thead>
                                    <tr>
                                        <th>Id_kunci</th>
                                        <th>Nama_kunci</th>
                                        <th>Kode_kantor</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($users as $item)
                                <tr>
                                    <td>{{ $item->id_kunci }}</td>
                                    <td>{{ $item->nama_kunci }}</td>
                                    <td>{{ $item->kode_kantor }}</td>
                                    <td>{{ $item->status }}</td>
                                </tr>
                            @endforeach --}}
                                </tbody>
                            </table>
                            <div class="btn-group">
                                <a type="button" class="btn btn-dark" href="#">
                                    <i class="bi bi-key-fill"></i>&nbsp; add peminjaman
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
        </main>
    </div>
@endsection
