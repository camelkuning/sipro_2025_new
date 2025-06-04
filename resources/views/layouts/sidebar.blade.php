<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                @if (Auth::check() && Auth::user()->role == 'admin')
                    <div class="sb-sidenav-menu-heading">Dashboard</div>
                    <a class="nav-link fw-bold d-flex align-items-center" data-bs-toggle="collapse" href="#menuDashboard"
                        role="button">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                    <div class="collapse ps-4 {{ Request::is('prokerAdmin', 'keuanganAdmin') ? 'show' : '' }}"
                        id="menuDashboard" data-bs-parent="#layoutSidenav">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link fw-bold {{ Request::is('prokerAdmin') ? 'active' : '' }}"
                                    href="/prokerAdmin">
                                    <i class="bi bi-pie-chart me-2"></i> Program Kerja
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-bold {{ Request::is('keuanganAdmin') ? 'active' : '' }}"
                                    href="/keuanganAdmin">
                                    <i class="bi bi-cash me-2"></i> Keuangan
                                </a>
                            </li>
                        </ul>
                    </div>
                @elseif (Auth::check() && Auth::user()->role == 'user')
                    <div class="sb-sidenav-menu-heading">Dashboard</div>
                    <a class="nav-link fw-bold d-flex align-items-center" data-bs-toggle="collapse"
                        href="#menuDashboard" role="button">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                    <div class="collapse ps-4 {{ Request::is('proker', 'keuangan') ? 'show' : '' }}" id="menuDashboard"
                        data-bs-parent="#layoutSidenav">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link fw-bold {{ Request::is('proker') ? 'active' : '' }}" href="/proker">
                                    <i class="bi bi-pie-chart me-2"></i> Program Kerja
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-bold {{ Request::is('keuangan') ? 'active' : '' }}"
                                    href="/keuangan">
                                    <i class="bi bi-cash me-2"></i> Keuangan
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif
                @if (Auth::check() && Auth::user()->role == 'admin')
                    <hr class="my-2 border-white opacity-50">
                    <div class="sb-sidenav-menu-heading">Daftar Pengeluaran</div>
                    <a class="nav-link fw-bold d-flex align-items-center" data-bs-toggle="collapse" href="#daftarKerja"
                        role="button">
                        <i class="bi bi-list-task me-2"></i> Daftar Pengeluaran
                    </a>
                    <div class="collapse ps-4 {{ Request::is('daftarProker', 'daftarKeuangan', 'acuan-pembagian') ? 'show' : '' }}"
                        id="daftarKerja" data-bs-parent="#layoutSidenav">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link fw-bold {{ Request::is('daftarProker') ? 'active' : '' }}"
                                    href="/daftarProker">
                                    <i class="bi bi-plus-square me-2"></i>Pengeluaran Program Kerja
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-bold {{ Request::is('daftarKeuangan') ? 'active' : '' }}"
                                    href="/daftarKeuangan">
                                    <i class="bi bi-plus-square me-2"></i>Pengeluaran Keuangan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-bold {{ Request::is('acuan-pembagian') ? 'active' : '' }}"
                                    href="/acuan-pembagian">
                                    <i class="bi bi-percent me-2"></i>Kontrol Sistem
                                </a>
                            </li>
                        </ul>
                    </div>
                    <hr class="my-2 border-white opacity-50">

                    <div class="sb-sidenav-menu-heading">Daftar User</div>
                    <a class="nav-link fw-bold d-flex align-items-center" data-bs-toggle="collapse" href="#daftarUser"
                        role="button">
                        <i class="bi bi-people me-2"></i> User
                    </a>
                    <div class="collapse ps-4 {{ Request::is('daftarUser') ? 'show' : '' }}" id="daftarUser"
                        data-bs-parent="#layoutSidenav">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link fw-bold {{ Request::is('daftarUser') ? 'active' : '' }}"
                                    href="/daftarUser">
                                    <i class="bi bi-person-lines-fill me-2"></i> Daftar User
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif

            </div>
        </div>

        <div class="sb-sidenav-footer">
            <div class="small"></div>

        </div>
    </nav>
</div>
