<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-labelledby="modalTambahUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahUserLabel">Tambah User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('postDaftarUser') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Nama Lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label for="komisi" class="form-label">Komisi / Unsur</label>
                        <select class="form-control" id="komisi" name="komisi" required>
                            <option value="">-- Pilih Komisi / Unsur --</option>
                            <option value="Persekutuan Anak Muda">Persekutuan Anak Muda</option>
                            <option value="Persekutuan Anak dan Remaja">Persekutuan Anak dan Remaja</option>
                            <option value="Persekutuan Kaum Bapa">Persekutuan Kaum Bapa</option>
                            <option value="Persekutuan Wanita">Persekutuan Wanita</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password |</label>
                        <small class="text-danger"> harus berisikan minimal 6 karakter</small>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Passowrd" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="password_confirmation"
                            name="password_confirmation" placeholder="Konfirmasi Passowrd" required>
                        <small id="passwordError" class="text-danger d-none">Password yang anda masukan tidak
                            cocok!</small>
                    </div>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center">
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
