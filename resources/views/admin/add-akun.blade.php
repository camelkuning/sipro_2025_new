<div class="modal fade" id="modalTambahAkun" tabindex="-1" aria-labelledby="modalTambahAkunLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahAkunLabel">Tambah Program Kerja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('akun.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="kode_akun" class="form-label">Kode Akun</label>
                        <input type="text" class="form-control" id="kode_akun" name="kode_akun">
                    </div>
                    <div class="mb-3">
                        <label for="nama_akun" class="form-label">Nama Akun</label>
                        <input type="text" class="form-control" id="nama_akun" name="nama_akun"
                            placeholder="Masukkan nama akun" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipe_akun" class="form-label">Tipe</label>
                        <select class="form-select" id="tipe_akun" name="tipe_akun" required>
                            <option value="" disabled selected>Pilih Tipe Akun</option>
                            <option value="proker">Program Kerja</option>
                            <option value="keuangan">Keuangan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <input type="text" class="form-control" id="keterangan" name="keterangan"
                            placeholder="Masukkan keterangan program kerja" required>
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
