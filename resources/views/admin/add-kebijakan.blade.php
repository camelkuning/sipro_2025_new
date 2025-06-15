<div class="modal fade" id="modalTambahKebijakan-{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('programkerja.tambahDanaKebijakan', $item->kode_program_kerja) }}" method="POST"
            class="form-kebijakan">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Dana Kebijakan - {{ $item->nama_program_kerja }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tambahan_dana_kebijakan" class="form-label">Jumlah Tambahan Dana</label>
                        <input type="text" placeholder="Masukan Tambahan Dana" name="tambahan_dana_kebijakan"
                            class="form-control format-uang" required>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan (opsional)</label>
                        <textarea name="keterangan" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
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
