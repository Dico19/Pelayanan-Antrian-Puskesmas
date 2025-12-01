<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Sistem Antrian Online Puskesmas</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/animate.css/animate.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

  <!-- Custom Theme Puskesmas -->
  <link href="{{ asset('css/puskesmas-theme.css') }}" rel="stylesheet">

  <!-- ✅ AOS CSS (ANIMATION ON SCROLL) -->
  <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

  {{-- ✅ CSS KHUSUS KOTAK PILIH POLI & TANGGAL --}}
  <style>
    /* =======================
       PREMIUM PILIH POLI
       ======================= */

    .poli-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        padding: 10px 5px 5px;
    }

    .poli-card {
        border: none;
        outline: none;
        background: linear-gradient(145deg, #2563eb, #1d4ed8);
        color: #f9fafb;
        border-radius: 18px;
        padding: 14px 10px;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.4);
        transition: transform 0.2s ease, box-shadow 0.2s ease,
                    background 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .poli-card::after {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top left,
                    rgba(255, 255, 255, 0.18), transparent 55%);
        opacity: 0.7;
        pointer-events: none;
    }

    .poli-card:hover {
        background: linear-gradient(145deg, #1e40af, #1d4ed8);
        transform: translateY(-4px);
        box-shadow: 0 12px 26px rgba(15, 23, 42, 0.6);
    }

    .poli-icon {
        width: 56px;
        height: 56px;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
        position: relative;
        z-index: 1;
    }

    .poli-icon i {
        font-size: 24px;
    }

    .poli-label {
        text-align: center;
        font-weight: 700;
        font-size: 13px;
        letter-spacing: 0.6px;
        line-height: 1.2;
        text-transform: uppercase;
        text-shadow: 0 1px 2px rgba(15, 23, 42, 0.9);
        position: relative;
        z-index: 1;
    }

    .poli-subtitle {
        font-size: 14px;
        color: #cbd5f5;
    }

    @media (max-width: 576px) {
        .poli-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .poli-card {
            padding: 12px 8px;
        }

        .poli-icon {
            width: 52px;
            height: 52px;
        }

        .poli-label {
            font-size: 12px;
        }
    }

    /* =======================
   KARTU TANGGAL (6 HARI)
   ======================= */

.tanggal-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 12px;
}

/* DEFAULT (LIGHT MODE) */
.tanggal-card {
    border-radius: 14px;
    padding: 10px 8px;
    text-align: center;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    min-height: 110px;

    border: 1px solid rgba(148, 163, 184, 0.55);
    background: #ffffff;
    color: #111827;
    box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08);
    transition:
        transform 0.15s ease,
        box-shadow 0.15s ease,
        background 0.15s ease,
        border-color 0.15s ease;
}

.tanggal-card .hari {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 4px;
}

.tanggal-card .tanggal {
    font-size: 26px;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 2px;
}

.tanggal-card .bulan {
    font-size: 12px;
    font-weight: 600;
}

.tanggal-card .jumlah {
    font-size: 12px;
    margin-top: 4px;
    padding-top: 3px;
    border-top: 1px solid rgba(148, 163, 184, 0.5);
    width: 100%;
}

.tanggal-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(15, 23, 42, 0.18);
    border-color: #2563eb;
}

/* TANGGAL TERPILIH (LIGHT MODE) */
.tanggal-card.active {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #ffffff !important;
    border-color: transparent;
    box-shadow: 0 10px 24px rgba(37, 99, 235, 0.45);
    transform: translateY(-3px);
}

.tanggal-card.active .hari,
.tanggal-card.active .tanggal,
.tanggal-card.active .bulan,
.tanggal-card.active .jumlah {
    color: #ffffff !important;
}

/* HARI MINGGU / LIBUR */
.tanggal-card.disabled {
    background: #f97373;
    color: #111827;
    cursor: not-allowed;
    box-shadow: none;
    border-color: #f97373;
}

.tanggal-card.disabled .jumlah,
.tanggal-card.disabled .bulan,
.tanggal-card.disabled .hari,
.tanggal-card.disabled .tanggal {
    opacity: 0.95;
}

.tanggal-card .text-libur {
    font-weight: 700;
}

/* =======================
   DARK MODE OVERRIDES
   ======================= */

/* kartu default di dark mode */
html.dark-mode .tanggal-card {
    background: #020617 !important;   /* hampir hitam tapi masih beda sama body */
    color: #e5e7eb !important;
    border-color: #1f2937 !important;
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.7) !important;
}

html.dark-mode .tanggal-card .hari,
html.dark-mode .tanggal-card .tanggal,
html.dark-mode .tanggal-card .bulan,
html.dark-mode .tanggal-card .jumlah {
    color: #e5e7eb !important;
    border-top-color: rgba(148, 163, 184, 0.4) !important;
}

/* hover di dark mode */
html.dark-mode .tanggal-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.9) !important;
    border-color: #38bdf8 !important;
}

/* tanggal TERPILIH di dark mode – biru terang */
html.dark-mode .tanggal-card.active {
    background: linear-gradient(135deg, #38bdf8, #2563eb) !important;
    color: #f9fafb !important;
    border-color: transparent !important;
    box-shadow: 0 12px 32px rgba(37, 99, 235, 0.8) !important;
    transform: translateY(-3px);
}

html.dark-mode .tanggal-card.active .hari,
html.dark-mode .tanggal-card.active .tanggal,
html.dark-mode .tanggal-card.active .bulan,
html.dark-mode .tanggal-card.active .jumlah {
    color: #f9fafb !important;
}

/* hari libur di dark mode */
html.dark-mode .tanggal-card.disabled {
    background: #7f1d1d !important;
    border-color: #b91c1c !important;
    color: #fecaca !important;
    box-shadow: none !important;
}

html.dark-mode .tanggal-card.disabled .jumlah,
html.dark-mode .tanggal-card.disabled .bulan,
html.dark-mode .tanggal-card.disabled .hari,
html.dark-mode .tanggal-card.disabled .tanggal {
    opacity: 0.98 !important;
}


  </style>

  @livewireStyles
</head>

<body>

  {{-- SUPPORT UNTUK HALAMAN BLADE BIASA --}}
  @yield('content')

  {{-- SUPPORT UNTUK LIVEWIRE FULL-PAGE --}}
  {{ $slot ?? '' }}

  <footer id="footer">
    <div class="container d-md-flex py-4">
      <div class="me-md-auto text-center text-md-start">
        <div class="copyright">
          &copy; Copyright <strong><span>Dicoding</span></strong>. All Rights Reserved
        </div>
      </div>

      <div class="social-links text-center text-md-right pt-3 pt-md-0">
        <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
        <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
        <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
        <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
        <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
      </div>
    </div>
  </footer><!-- End Footer -->

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <script src="https://code.jquery.com/jquery-3.6.3.min.js"
          integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU="
          crossorigin="anonymous"></script>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset('assets/js/main.js') }}"></script>

  {{-- Dark Mode --}}
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const html = document.documentElement;
      const toggleBtn = document.getElementById("themeToggle");
      const icon = document.getElementById("themeIcon");
      const text = document.getElementById("themeText");

      const savedTheme = localStorage.getItem("theme") || "light";

      if (savedTheme === "dark") {
        html.classList.add("dark-mode");
        if (icon) icon.className = "bi bi-sun";
        if (text) text.innerText = "Terang";
      }

      if (toggleBtn) {
        toggleBtn.addEventListener("click", function () {
          html.classList.toggle("dark-mode");

          if (html.classList.contains("dark-mode")) {
            localStorage.setItem("theme", "dark");
            if (icon) icon.className = "bi bi-sun";
            if (text) text.innerText = "Terang";
          } else {
            localStorage.setItem("theme", "light");
            if (icon) icon.className = "bi bi-moon-stars";
            if (text) text.innerText = "Gelap";
          }
        });
      }
    });
  </script>

  <!-- ✅ AOS JS -->
  <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
      AOS.init({
          duration: 800,
          once: true,
          offset: 120,
      });
  </script>

  {{-- Script khusus tiap halaman --}}
  @yield('script')

  {{-- Script yang di-push dengan @push('scripts') --}}
  @stack('scripts')

  {{-- Livewire & global app.js --}}
  @livewireScripts
  <script src="{{ asset('js/app.js') }}"></script>

</body>
</html>
