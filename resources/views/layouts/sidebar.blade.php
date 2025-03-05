<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Dashboard Admin</div>
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

                <hr class="my-2 border-white opacity-50">

                <div class="sb-sidenav-menu-heading">Daftar Kerja</div>
                <a class="nav-link fw-bold d-flex align-items-center" data-bs-toggle="collapse" href="#daftarKerja"
                    role="button">
                    <i class="bi bi-pencil-square me-2"></i> Daftar Kerja
                </a>
                <div class="collapse ps-4 {{ Request::is('daftarProker', 'daftarKeuangan') ? 'show' : '' }}"
                    id="daftarKerja" data-bs-parent="#layoutSidenav">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link fw-bold {{ Request::is('daftarProker') ? 'active' : '' }}"
                                href="/daftarProker">
                                <i class="bi bi-plus-square me-2"></i> Program Kerja
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold {{ Request::is('daftarKeuangan') ? 'active' : '' }}"
                                href="/daftarKeuangan">
                                <i class="bi bi-plus-square me-2"></i> Keuangan
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



                {{-- @if (Auth::check() && Auth::user()->role == 'satpam')
                <div class="sb-sidenav-menu-heading">Dashboard satpam</div>
                    <a class="nav-link" href="/satpam">
                        <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                        Daftar kunci-satpam
                    </a>

                    <a class="nav-link" href="/sirkulasi_satpam">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-rotate-right"></i></div>
                        Sirkulasi-satpam
                    </a>
                    
                @endif --}}
                {{-- @if (Auth::check() && Auth::user()->role == 'admin')
                    
                @endif --}}
            </div>
        </div>

        <div class="sb-sidenav-footer">
            <div class="small"></div>

        </div>
    </nav>
</div>
