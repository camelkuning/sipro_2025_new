<div class="modal fade" id="modalEditUser" tabindex="-1" aria-labelledby="modalEditUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditUserLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id" name="id"> 

                    <div class="mb-3">
                        <label for="edit_nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="edit_nama_lengkap" name="nama_lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_komisi" class="form-label">Komisi / Unsur</label>
                        <input type="text" class="form-control" id="edit_komisi" name="komisi" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Role</label>
                        <select class="form-control" id="edit_role" name="role" required>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="edit_username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Password (Opsional)</label>
                        <input type="password" class="form-control" id="edit_password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="edit_password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="edit_password_confirmation" name="password_confirmation">
                        <small id="editPasswordError" class="text-danger d-none">Password yang anda masukan tidak cocok!</small>
                    </div>
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        var id_user;
        $('.btnEditUser').on('click', function() {
            id_user = $(this).data('id');
            $('form').attr('action', '/updateUser/' + id_user);
            $.ajax({
                url: '/admin/user/' + id_user,
                method: 'GET',
                success: function(data) {
                    $('#edit_id').val(data.id);
                    $('#edit_nama_lengkap').val(data.nama_lengkap);
                    $('#edit_komisi').val(data.komisi);
                    $('#edit_role').val(data.role);
                    $('#edit_username').val(data.username);
                }
            });
            
        });
    });
</script>
