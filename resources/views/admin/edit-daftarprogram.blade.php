<div class="modal fade" id="modalEditProgramKerja{{ $item->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditProgramKerjaLabel{{ $item->id }}">Edit Program Kerja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('programkerja.update', $item->id)  }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nama_program_kerja" class="form-label">Nama Program Kerja</label>
                        <input type="text" class="form-control" name="nama_program_kerja"
                            value="{{ $item->nama_program_kerja }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="komisi_program_kerja" class="form-label">Komisi</label>
                        <select class="form-select" name="komisi_program_kerja" required>
                            <option value="" disabled>Pilih Komisi</option>
                            @php
                                $komisiOptions = [
                                    'Persekutuan Anak Muda',
                                    'Persekutuan Anak dan Remaja',
                                    'Persekutuan Kaum Bapak',
                                    'Persekutuan Wanita',
                                    'Majelis Jemaat',
                                ];
                            @endphp
                            @foreach ($komisiOptions as $komisi)
                                <option value="{{ $komisi }}"
                                    {{ $item->komisi_program_kerja == $komisi ? 'selected' : '' }}>
                                    {{ $komisi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="nama_ketua_program_kerja" class="form-label">Nama Ketua Program Kerja</label>
                        <input type="text" class="form-control" name="nama_ketua_program_kerja"
                            value="{{ $item->nama_ketua_program_kerja }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="nama_majelis_pendamping" class="form-label">Nama Majelis Pendamping</label>
                        <input type="text" class="form-control" name="nama_majelis_pendamping"
                            value="{{ $item->nama_majelis_pendamping }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="tanggal_mulai"
                            value="{{ $item->tanggal_mulai }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" name="tanggal_selesai"
                            value="{{ $item->tanggal_selesai }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <input type="text" class="form-control" name="keterangan" value="{{ $item->keterangan }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="anggaran_digunakan" class="form-label">Anggaran Digunakan</label>
                        <input type="text" class="form-control format-uang" name="anggaran_digunakan"
                            value="{{ $item->anggaran_digunakan }}" required>
                    </div>

                    <div class="mb-3" hidden>
                        <label for="tambahan_dana_kebijakan" class="form-label">Tambahan Dana Kebijakan</label>
                        <input type="text" class="form-control format-uang" name="tambahan_dana_kebijakan"
                            value="{{ $item->tambahan_dana_kebijakan }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="tahun" class="form-label">Tahun</label>
                        <input type="text" class="form-control" name="tahun" value="{{ $item->tahun }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" name="status" required>
                            <option value="aktif" {{ $item->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ $item->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-dark">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const formatRupiah = (angka) => {
            return angka.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        };

        document.querySelectorAll('.format-uang').forEach((input) => {
            input.addEventListener('input', function() {
                this.value = formatRupiah(this.value);
            });
        });

        // Hapus titik sebelum dikirim ke Laravel
        document.querySelectorAll("form").forEach(form => {
            form.addEventListener("submit", function() {
                form.querySelectorAll('.format-uang').forEach((input) => {
                    input.value = input.value.replace(/\./g, "");
                });
            });
        });
    });
</script>
