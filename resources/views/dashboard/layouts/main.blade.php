<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Dashboard - Antrian Online Puskesmas</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="/assetsDashboard/img/favicon.png" rel="icon">
    <link href="/assetsDashboard/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="/assetsDashboard/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assetsDashboard/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="/assetsDashboard/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="/assetsDashboard/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="/assetsDashboard/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="/assetsDashboard/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="/assetsDashboard/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="/assetsDashboard/css/style.css" rel="stylesheet">

    <!-- ⭐ Custom Admin Theme (warna dashboard baru) -->
    <link href="{{ asset('css/admin-theme.css') }}" rel="stylesheet">

    @livewireStyles
</head>

<script>
    window.addEventListener('refreshPage', () => {
        window.location.reload();
    });
</script>

<body class="@yield('body-class')">

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.dashboard') }}" class="logo d-flex align-items-center">
                <img src="/assetsDashboard/img/logo.png" alt="">
                <span class="d-none d-lg-block">Antrian Online</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

        <div class="search-bar">
            <form class="search-form d-flex align-items-center"
                  method="GET"
                  action="{{ route('admin.pasien.index') }}">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari nama / NIK..."
                       title="Masukkan kata kunci">
                <button type="submit" title="Search">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
        <!-- End Search Bar -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item d-block d-lg-none">
                    <a class="nav-link nav-icon search-bar-toggle " href="#">
                        <i class="bi bi-search"></i>
                    </a>
                </li><!-- End Search Icon-->

                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                       data-bs-toggle="dropdown">
                        <img src="/assetsDashboard/img/profile-bahlil.jpg" alt="Profile" class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ auth()->user()->name }}</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6>{{ auth()->user()->name }}</h6>
                        </li>

                        <hr class="dropdown-divider">

                        <li>
                            <form action="/logout" method="post">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <span class="align-middle">Logout</span>
                                </button>
                            </form>
                        </li>

                    </ul>
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            {{-- MENU DASHBOARD --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? '' : 'collapsed' }}"
                   href="{{ route('admin.dashboard') }}">
                    <i class="bx bxs-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- MENU DAFTAR ANTRIAN --}}
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                    <i class="bx bx-bar-chart-square"></i>
                    <span>Daftar Antrian</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>

                <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="/admin/dashboard/antrian/poliUmum">
                            <i class="bi bi-circle"></i>
                            <span>Poli Umum</span>
                            @if(($counts['umum'] ?? 0) > 0)
                                <span class="badge bg-danger ms-2">{{ $counts['umum'] }}</span>
                            @endif
                        </a>
                    </li>

                    <li>
                        <a href="/admin/dashboard/antrian/poliGigi">
                            <i class="bi bi-circle"></i>
                            <span>Poli Gigi</span>
                            @if(($counts['gigi'] ?? 0) > 0)
                                <span class="badge bg-danger ms-2">{{ $counts['gigi'] }}</span>
                            @endif
                        </a>
                    </li>

                    <li>
                        <a href="/admin/dashboard/antrian/poliTht">
                            <i class="bi bi-circle"></i>
                            <span>Poli THT</span>
                            @if(($counts['tht'] ?? 0) > 0)
                                <span class="badge bg-danger ms-2">{{ $counts['tht'] }}</span>
                            @endif
                        </a>
                    </li>

                    <li>
                        <a href="/admin/dashboard/antrian/poliLansia">
                            <i class="bi bi-circle"></i>
                            <span>Poli Lansia & Disabilitas</span>
                            @if(($counts['lansia'] ?? 0) > 0)
                                <span class="badge bg-danger ms-2">{{ $counts['lansia'] }}</span>
                            @endif
                        </a>
                    </li>

                    <li>
                        <a href="/admin/dashboard/antrian/poliBalita">
                            <i class="bi bi-circle"></i>
                            <span>Poli Balita</span>
                            @if(($counts['balita'] ?? 0) > 0)
                                <span class="badge bg-danger ms-2">{{ $counts['balita'] }}</span>
                            @endif
                        </a>
                    </li>

                    <li>
                        <a href="/admin/dashboard/antrian/poliKia">
                            <i class="bi bi-circle"></i>
                            <span>Poli KIA & KB</span>
                            @if(($counts['kia'] ?? 0) > 0)
                                <span class="badge bg-danger ms-2">{{ $counts['kia'] }}</span>
                            @endif
                        </a>
                    </li>

                    <li>
                        <a href="/admin/dashboard/antrian/poliNifas">
                            <i class="bi bi-circle"></i>
                            <span>Poli Nifas/PNC</span>
                            @if(($counts['nifas'] ?? 0) > 0)
                                <span class="badge bg-danger ms-2">{{ $counts['nifas'] }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
            </li>

            {{-- MENU LAPORAN --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.laporan.*') ? '' : 'collapsed' }}"
                   href="{{ route('admin.laporan.index') }}">
                    <i class="bx bx-task"></i>
                    <span>Laporan</span>
                </a>
            </li>

            {{-- MENU ANALITIK --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.analytics') ? '' : 'collapsed' }}"
                   href="{{ route('admin.analytics') }}">
                    <i class="bi bi-graph-up"></i>
                    <span>Analitik</span>
                </a>
            </li>

            {{-- MENU LOGOUT DI BAWAH LAPORAN --}}
            <li class="nav-item mt-3">
                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit" class="btn sidebar-logout-red w-100 text-start px-3">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </button>
                </form>
            </li>

        </ul>
    </aside><!-- End Sidebar-->

    {{-- main-class & section-class bisa dioverride per halaman --}}
    <main id="main" class="main @yield('main-class')">
        <section class="section @yield('section-class', 'dashboard')">
            @yield('content')
        </section>
    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>Dicoding</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU="
        crossorigin="anonymous"></script>

    <!-- Vendor JS Files -->
    <script src="/assetsDashboard/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="/assetsDashboard/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assetsDashboard/vendor/chart.js/chart.umd.js"></script>
    <script src="/assetsDashboard/vendor/echarts/echarts.min.js"></script>
    <script src="/assetsDashboard/vendor/quill/quill.min.js"></script>
    <script src="/assetsDashboard/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="/assetsDashboard/vendor/tinymce/tinymce.min.js"></script>
    <script src="/assetsDashboard/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="/assetsDashboard/js/main.js"></script>

    @yield('script')
    @livewireScripts

    {{-- script JS global punya admin --}}
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- supaya script dari @push('scripts') ikut ke-load --}}
    @stack('scripts')
</body>
</html>
