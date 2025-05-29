<div class="modal fade" id="modalTambahProgramKerja" tabindex="-1" aria-labelledby="modalTambahProgramKerjaLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahProgramKerjaLabel">Tambah Program Kerja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('programkerja.store') }}" method="POST">
                    @csrf
                    <div class="mb-3" >
                        <label for="kode_akun" class="form-label">Akun</label>
                        <select name="akun_id" class="form-control" required>
                            @foreach ($daftarAkun as $akun)
                                @if ($akun->tipe_akun === 'proker')
                                    <option value="{{ $akun->id }}">{{ $akun->nama_akun }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3" hidden>
                        <label for="kode_program_kerja" class="form-label">Kode Program Kerja</label>
                        {{-- <input type="text" class="form-control" id="kode_program_kerja" name="kode_program_kerja" required readonly> --}}
                    </div>
                    <div class="mb-3">
                        <label for="nama_program_kerja" class="form-label">Nama Program Kerja</label>
                        <input type="text" class="form-control" id="nama_program_kerja" name="nama_program_kerja"
                            placeholder="Masukkan nama program kerja" required>
                    </div>
                    <div class="mb-3">
                        <label for="komisi_program_kerja" class="form-label">Komisi</label>
                        <select class="form-select" id="komisi_program_kerja" name="komisi_program_kerja" required>
                            <option value="" disabled selected>Pilih Komisi</option>
                            <option value="Persekutuan Anak Muda">Persekutuan Anak Muda</option>
                            <option value="Persekutuan Anak dan Remaja">Persekutuan Anak dan Remaja</option>
                            <option value="Persekutuan Kaum Bapak">Persekutuan Kaum Bapak</option>
                            <option value="Persekutuan Wanita">Persekutuan Wanita</option>
                            <option value="Majelis Jemaat">Majelis Jemaat</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nama_ketua_program_kerja" class="form-label">Nama Ketua Program Kerja</label>
                        <input type="text" class="form-control" id="nama_ketua_program_kerja"
                            name="nama_ketua_program_kerja" placeholder="Masukkan nama ketua program kerja" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_majelis_pendamping" class="form-label">Nama Majelis Pendamping</label>
                        <input type="text" class="form-control" id="nama_majelis_pendamping"
                            name="nama_majelis_pendamping" placeholder="Masukkan nama majelis pendamping" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <input type="text" class="form-control" id="keterangan" name="keterangan"
                            placeholder="Masukkan keterangan program kerja" required>
                    </div>
                    <div class="mb-3">
                        <label for="anggaran_digunakan" class="form-label">Anggaran Digunakan</label>
                        <input type="text" class="form-control format-uang" id="anggaran_digunakan"
                            name="anggaran_digunakan" placeholder="Masukkan anggaran digunakan" required>
                    </div>
                    <div class="mb-3" hidden>
                        <label for="tambahan_dana_kebijakan" class="form-label">Tambahan Dana Kebijakan</label>
                        <input type="text" class="form-control format-uang" id="tambahan_dana_kebijakan"
                            name="tambahan_dana_kebijakan" placeholder="Masukkan tambahan dana kebijakan" required
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label for="tahun" class="form-label">Tahun</label>
                        <input type="text" class="form-control" id="tahun" name="tahun"
                            placeholder="Masukkan tahun" required>
                    </div>
                    <div class="mb-3" >
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="" disabled selected>Pilih Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="diarsipkan">Diarsipkan</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Simpan</button>
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
