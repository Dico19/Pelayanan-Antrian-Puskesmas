<!-- ======= Top Bar ======= -->
<div id="topbar" class="d-flex align-items-center fixed-top">
    <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="contact-info d-flex align-items-center">

            <i class="bi bi-envelope"></i>
            <a href="mailto:contact@example.com" class="contact-email">
                puskesmaskaligandu@gmail.com
            </a>

            <i class="bi bi-phone ms-4"></i>
            <a href="tel:+62895404905070" class="contact-phone">
                +62 8954 0490 5070
            </a>

        </div>
    </div>
</div>

<!-- ======= Header ======= -->
<header id="header" class="fixed-top">
    <div class="container d-flex align-items-center">

        <h1 class="logo me-auto">
            <a href="/">PUSKESMAS Kaligandu</a>
        </h1>

        <nav id="navbar" class="navbar order-last order-lg-0">
            <ul>
                {{-- HOME --}}
                <li>
                    <a class="nav-link scrollto {{ request()->is('/') ? 'active' : '' }}"
                       href="/#hero">
                        <i class="bi bi-house-door me-1"></i> Home
                    </a>
                </li>

                {{-- ANTRIAN --}}
                <li>
                    <a class="nav-link scrollto {{ request()->is('antrian') ? 'active' : '' }}"
                       href="{{ url('/antrian') }}">
                        <i class="bi bi-journal-text me-1"></i> Antrian
                    </a>
                </li>

                {{-- ANTRIANKU --}}
                <li>
                    <a class="nav-link scrollto
                       {{ request()->is('antrian/cari') || request()->is('antrian/cari/*') ? 'active' : '' }}"
                       href="{{ route('antrian.cari') }}">
                        <i class="bi bi-person-badge me-1"></i> Antrianku
                    </a>
                </li>

                {{-- CONTACT --}}
                <li>
                    <a class="nav-link scrollto" href="/#contact">
                        <i class="bi bi-telephone me-1"></i> Contact
                    </a>
                </li>
            </ul>

            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav><!-- .navbar -->

        {{-- Dark/Light Toggle Button --}}
        <button id="themeToggle" type="button"
                class="btn btn-outline-secondary ms-3 d-flex align-items-center gap-1"
                style="border-radius: 20px; font-size: 14px;">
            <i id="themeIcon" class="bi bi-moon-stars"></i>
            <span id="themeText">Gelap</span>
        </button>

        {{-- DROPDOWN ADMIN HANYA MUNCUL DI HALAMAN ADMIN --}}
        @if (request()->is('admin*'))
            @auth
                @if (auth()->user()->role_id == 1)
                    <div class="dropdown ms-3">
                        <button class="btn btn-success dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                            {{ auth()->user()->name }}
                        </button>

                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="/admin/dashboard">
                                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                                </a>
                            </li>

                            <li>
                                <form action="/logout" method="post">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-1"></i>
                                        <span class="align-middle">Logout</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endif
            @endauth
        @endif

    </div>
</header><!-- End Header -->

{{-- ================== CUSTOM DARK MODE STYLE ================== --}}
<style>
    /* Topbar email & phone saat DARK MODE */
    .dark-mode #topbar .contact-info a,
    .dark-mode #topbar .contact-info i {
        color: #6bb6ff !important; /* biru muda supaya jelas */
    }

    /* Header tabel di halaman antrian saat DARK MODE */
    .dark-mode table thead th {
        background: rgba(90, 150, 255, 0.18); /* biru muda transparan */
        color: #7DB7FF;                        /* teks biru muda */
        font-weight: 600;
        border-color: rgba(125, 183, 255, 0.35);
    }

    /* Isi baris tabel saat DARK MODE */
    .dark-mode table tbody td {
        color: #e5e5e5; /* putih lembut */
        border-color: rgba(255, 255, 255, 0.12);
    }
</style>
