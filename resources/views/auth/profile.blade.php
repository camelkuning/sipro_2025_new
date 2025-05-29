<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">
                    Profil {{ ucfirst(Auth::user()->role) }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <h6 class="mb-3">Daftar Pengguna:</h6>
                @foreach ($users as $user)
                    @if ($user->role == Auth::user()->role)
                        <div class="border rounded p-2 mb-2">
                            <p><strong>Nama:</strong> {{ $user->nama_lengkap }}</p>
                            <p><strong>Komisi:</strong> {{ $user->komisi }}</p>
                            <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                            <p><strong>Username:</strong> {{ $user->username }}</p>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
