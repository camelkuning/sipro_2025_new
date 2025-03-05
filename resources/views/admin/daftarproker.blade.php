@extends('layouts.main')
@section('title', 'Admin | add Proker')
@section('content')
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <div class="container-fluid px-4">
                <h1 class="mt-4">Dashboard</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
                <main>
                    <div class="container-fluid px-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Bar Chart Example
                                    </div>
                                    <div class="card-body"><canvas id="myBarChart" width="100%"
                                            height="50"></canvas></div>
                                    <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Daftar Kunci
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
