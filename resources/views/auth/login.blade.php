<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Login - Viadolorosa</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}" sizes="32x32">

</head>

<body
    style="background: url('{{ asset('assets/img/background.jpg') }}') no-repeat center center fixed; background-size: cover;">
    <div class="bg-overlay">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    {{-- <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Shalom, Selamat Datang</h3>
                                </div> --}}
                                    <div class="card-header text-center">
                                        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo Gereja"
                                            class="logo-img">
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ url('/postlogin') }}" method="POST">
                                            @csrf
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputUsername" type="text"
                                                    name="username" placeholder="Masukkan username" required />
                                                <label for="inputUsername">Username</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputPassword" type="password"
                                                    name="password" placeholder="Password" required />
                                                <label for="inputPassword">Password</label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" id="inputRememberPassword"
                                                    type="checkbox" name="remember" />
                                                <label class="form-check-label" for="inputRememberPassword">Remember
                                                    Password</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-center mt-4 mb-0">
                                                <button type="submit" class="btn btn-primary">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; GKI VIA DOLOROSA 2025</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        @if (session('success'))
            Toast.fire({
                icon: "success",
                title: "{{ session('success') }}"
            });
        @endif

        @if (session('error'))
            Toast.fire({
                icon: "error",
                title: "Gagal!",
                text: "{{ session('error') }}"
            });
        @endif

        @if ($errors->any())
            Toast.fire({
                icon: "error",
                title: "Gagal!",
                text: "{{ implode(', ', $errors->all()) }}"
            });
        @endif
    });
</script>


</html>
