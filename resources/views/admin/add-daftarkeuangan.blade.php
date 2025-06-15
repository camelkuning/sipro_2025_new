<div class="modal fade" id="modalTambahKeuangan" tabindex="-1" aria-labelledby="modalTambahKeuanganLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahKeuanganLabel">Tambah Data Keuangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('postDaftarKeuangan') }}" method="POST">
                    @csrf
                    <div class="mb-3" hidden>
                        <label for="kode_akun" class="form-label">Akun</label>
                        <select name="akun_id" class="form-control" required>
                            @foreach ($daftarAkun as $akun)
                                @if ($akun->tipe_akun === 'keuangan')
                                    <option value="{{ $akun->id }}">{{ $akun->nama_akun }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    {{-- {{ dd($daftarAkun) }}> --}}
                    <div class="mb-3" hidden>
                        <label for="kode_keuangan" class="form-label">Kode Keuangan</label>
                        <input type="text" class="form-control" id="kode_keuangan" name="kode_keuangan" required
                            readonly>
                    </div>

                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-5">
                                <label for="tanggal_awal" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                            </div>
                            {{-- <div class="col-md-1 text-center align-self-center">
                                <span> - </span>
                            </div>
                            <div class="col-md-5">
                                <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" required>
                            </div> --}}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="tipe" class="form-label">Tipe</label>
                        <select class="form-select" id="tipe" name="tipe" required>
                            <option value="" disabled selected>Pilih Tipe</option>
                            <option value="debit">Debit</option>
                            <option value="kredit">Kredit</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="text" class="form-control format-uang" id="jumlah" name="jumlah"
                            placeholder="Masukkan jumlah" required>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <input type="text" class="form-control" id="keterangan" name="keterangan"
                            placeholder="Masukkan keterangan" required>
                    </div>
                    <div class="mb-3" hidden>
                        <label for="keterangan" class="form-label">Urutan</label>
                        <input type="text" class="form-control" id="urutan" name="urutan"
                            placeholder="Masukkan keterangan" required readonly>
                    </div>
                    {{-- <div class="mb-3">
                        <label for="inkonveksional" class="form-label">Pendapatan Inkonvensional</label>
                        <input type="text" class="form-control format-uang" id="inkonveksional" name="inkonveksional" placeholder="Masukkan jumlah" required>
                    </div> --}}
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </div>
                    

                </form>
                {{-- {{ dd(request()->all()) }} --}}
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
        document.querySelector("form").addEventListener("submit", function() {
            document.querySelectorAll('.format-uang').forEach((input) => {
                input.value = input.value.replace(/\./g, "");
            });
        });
    });
</script>
