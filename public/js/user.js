
document.getElementById('btnTambahUser').addEventListener('click', function() {
    fetch('/add-daftaruser')
        .then(response => response.text())
        .then(html => {
            document.getElementById('modalContainer').innerHTML = html;
            let modal = new bootstrap.Modal(document.getElementById('modalTambahUser'));
            modal.show();
        });
});

document.addEventListener('submit', function(event) {
    if (event.target.id === 'formTambahUser') {
        event.preventDefault(); // Stop form dari reload

        let formData = new FormData(event.target);

        // Cek password konfirmasi
        let password = formData.get('password');
        let passwordConfirm = formData.get('password_confirmation');
        if (password !== passwordConfirm) {
            document.getElementById('passwordError').classList.remove('d-none');
            return;
        }

        let postDaftarUserUrl = document.querySelector('meta[name="route-post-daftaruser"]').getAttribute('content');
                fetch(postDaftarUserUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });

                    Toast.fire({
                        icon: "success",
                        title: "User berhasil ditambahkan!"
                    });

                    setTimeout(() => {
                        location.reload(); // Refresh halaman setelah toast selesai
                    }, 1000);

                } else {
                    Swal.fire({
                        title: "Gagal!",
                        text: "Terjadi kesalahan, coba lagi!",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            })
            .catch(error => {
                console.log(error);
                Swal.fire({
                    title: "Error!",
                    text: "Terjadi kesalahan sistem!",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            });
    }
});
