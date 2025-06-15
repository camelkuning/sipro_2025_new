@foreach ($programKerja as $item)
    <div class="modal fade" id="modalRincian{{ $item->id }}" tabindex="-1"
        aria-labelledby="rincianLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rincianLabel{{ $item->id }}">Detail Program Kerja -
                        {{ $item->nama_program_kerja }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Program Kerja</th>
                            <td>{{ $item->nama_program_kerja }}</td>
                        </tr>
                        <tr>
                            <th>Komisi</th>
                            <td>{{ $item->komisi_program_kerja }}</td>
                        </tr>
                        <tr>
                            <th>Ketua Panitia</th>
                            <td>{{ $item->nama_ketua_program_kerja }}</td>
                        </tr>
                        <tr>
                            <th>Majelis Pendamping</th>
                            <td>{{ $item->nama_majelis_pendamping }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Mulai</th>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->isoFormat('dddd, D MMMM YYYY') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Selesai</th>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_selesai)->isoFormat('dddd, D MMMM YYYY') }}</td>
                        </tr>

                        <tr>
                            <th>Keterangan</th>
                            <td>{{ $item->keterangan }}</td>
                        </tr>
                        <tr>
                            <th>Anggaran Digunakan</th>
                            <td>Rp {{ number_format($item->anggaran_digunakan, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Dana Kebijakan</th>
                            <td>Rp {{ number_format($item->tambahan_dana_kebijakan, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Tahun</th>
                            <td>{{ $item->tahun }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{ $item->status }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endforeach
