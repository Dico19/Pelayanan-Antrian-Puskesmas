<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>TV Antrian Puskesmas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: radial-gradient(circle at top, #4b6bff 0, #1a237e 35%, #0a102e 70%, #020412 100%);
            color: #fff;
            overflow: hidden;
        }

        /* HEADER ------------------------------------- */
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 40px;
            background: linear-gradient(90deg, rgba(10,23,80,0.8), rgba(21,101,192,0.85));
            box-shadow: 0 6px 20px rgba(0,0,0,0.4);
            position: relative;
            z-index: 10;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .logo-puskesmas {
            width: 56px;
            height: 56px;
            object-fit: contain;
            border-radius: 50%;
            background: #ffffff;
            padding: 6px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.4);
        }

        header .brand-title {
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        header .brand-subtitle {
            font-size: 16px;
            opacity: 0.9;
        }

        header .right-box {
            text-align: right;
        }

        .clock {
            font-size: 22px;
            font-weight: 600;
        }

        .date-text {
            font-size: 14px;
            opacity: 0.8;
        }

        /* LAYOUT UTAMA -------------------------------- */
        .main-wrapper {
            display: flex;
            gap: 24px;
            padding: 24px 40px 32px;
            height: calc(100vh - 80px);
        }

        .current-section {
            flex: 1.2;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .side-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 18px;
            justify-content: center;
        }

        /* GLASS CARD ---------------------------------- */
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            box-shadow:
                0 20px 40px rgba(0,0,0,0.5),
                0 0 0 1px rgba(255,255,255,0.06);
            padding: 22px 26px;
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        /* CURRENT NUMBER ------------------------------ */
        .current-label {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 2px;
            opacity: 0.95;
        }

        .current-number-wrapper {
            position: relative;
            display: inline-block;
            padding: 26px 60px;
            border-radius: 999px;
        }

        .pulse-ring {
            position: absolute;
            inset: -30px;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(79,195,247,0.45), transparent 60%);
            opacity: 0.0;
            animation: pulse 2.5s infinite;
            z-index: -1;
        }

        @keyframes pulse {
            0%   { transform: scale(0.9); opacity: 0.0; }
            40%  { transform: scale(1);   opacity: 0.75; }
            100% { transform: scale(1.1); opacity: 0; }
        }

        .current-number {
            font-size: 160px;
            font-weight: 800;
            letter-spacing: 6px;
            color: #ffeb3b;
            text-shadow:
                0 0 20px rgba(255,235,59,0.8),
                0 0 40px rgba(255,255,255,0.7);
        }

        /* highlight ketika nomor baru dipanggil */
        .current-number--highlight {
            animation: highlightFlash 0.9s ease-out;
        }

        @keyframes highlightFlash {
            0% {
                transform: scale(1);
                filter: brightness(1);
            }
            25% {
                transform: scale(1.08);
                filter: brightness(1.4);
            }
            55% {
                transform: scale(0.95);
                filter: brightness(1.1);
            }
            100% {
                transform: scale(1);
                filter: brightness(1);
            }
        }

        .current-poli {
            margin-top: 16px;
            font-size: 34px;
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        .status-badge {
            margin-top: 12px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 999px;
            background: rgba(0, 230, 118, 0.18);
            color: #a5ffcb;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1.4px;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: #00e676;
            box-shadow: 0 0 10px rgba(0,230,118,0.9);
        }

        .no-data-text {
            margin-top: 16px;
            font-size: 20px;
            opacity: 0.9;
        }

        /* NEXT QUEUE CARD ----------------------------- */
        .card-title {
            font-size: 22px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.6px;
            margin-bottom: 14px;
        }

        .next-list {
            margin-top: 6px;
        }

        .next-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            border-radius: 14px;
            background: rgba(0,0,0,0.25);
            margin-bottom: 8px;
            opacity: 0;
            transform: translateY(16px);
            animation: slideUpFade 0.55s ease-out forwards;
        }

        @keyframes slideUpFade {
            0% {
                opacity: 0;
                transform: translateY(16px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .next-num {
            font-size: 32px;
            font-weight: 700;
        }

        .next-poli {
            font-size: 16px;
            opacity: 0.85;
            text-transform: uppercase;
        }

        .next-badge {
            padding: 4px 10px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.35);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
        }

        /* INFO CARD ----------------------------------- */
        .info-text {
            font-size: 16px;
            line-height: 1.8;
            opacity: 0.95;
        }

        .info-text ul {
            margin: 0;
            padding-left: 20px;
        }

        /* RUNNING FOOTER ------------------------------ */
        .footer-bar {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            height: 36px;
            overflow: hidden;
            background: linear-gradient(90deg, #0d47a1, #1976d2);
            box-shadow: 0 -4px 20px rgba(0,0,0,0.7);
        }

        .footer-bar marquee {
            color: #fff;
            font-size: 15px;
            line-height: 36px;
            font-family: inherit;
        }

        /* RESPONSIVE ---------------------------------- */
        @media (max-width: 1024px) {
            .main-wrapper {
                flex-direction: column;
                height: auto;
            }

            .current-number {
                font-size: 110px;
            }
        }

    </style>
</head>
<body>

<header>
    <div class="header-left">
        <img src="{{ asset('assets/img/logo-puskesmas.png') }}" alt="Logo Puskesmas" class="logo-puskesmas">
        <div>
            <div class="brand-title">LAYANAN ANTRIAN ONLINE</div>
            <div class="brand-subtitle">PUSKESMAS KALIGANDU</div>
        </div>
    </div>
    <div class="right-box">
        <div class="clock" id="clock">--:--:--</div>
        <div class="date-text" id="dateText">-</div>
    </div>
</header>

<div class="main-wrapper">

    {{-- SECTION NOMOR SEDANG DIPANGGIL --}}
    <section class="current-section">
        <div class="glass-card" style="text-align:center; max-width: 680px; width: 100%;">
            <div class="current-label">Nomor yang Sedang Dipanggil</div>

            <div class="current-number-wrapper">
                <div class="pulse-ring"></div>
                <div class="current-number" id="current-number">
                    {{ $current ? $current->no_antrian : '--' }}
                </div>
            </div>

            <div class="current-poli" id="current-poli">
                @if($current)
                    POLI {{ strtoupper($current->poli) }}
                @else
                    &nbsp;
                @endif
            </div>

            <div class="status-badge" id="status-badge" style="{{ $current ? '' : 'display:none;' }}">
                <span class="status-dot"></span>
                <span id="status-text">Silakan menuju ruang pelayanan</span>
            </div>

            <div class="no-data-text" id="current-empty-text" style="{{ $current ? 'display:none;' : '' }}">
                Belum ada nomor yang dipanggil.
            </div>
        </div>
    </section>

    {{-- SECTION KANAN: NOMOR BERIKUTNYA + INFO --}}
    <section class="side-section">

        {{-- Nomor berikutnya --}}
        <div class="glass-card">
            <div class="card-title">Nomor Berikutnya</div>

            <div id="next-list">
                @if($next->count())
                    @foreach($next as $index => $row)
                        <div class="next-item" style="animation-delay: {{ $index * 0.08 }}s">
                            <div>
                                <div class="next-num">{{ $row->no_antrian }}</div>
                                <div class="next-poli">POLI {{ strtoupper($row->poli) }}</div>
                            </div>
                            <div class="next-badge">
                                @if($index === 0)
                                    Segera
                                @else
                                    Menunggu
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="no-data-text">Belum ada antrian berikutnya.</div>
                @endif
            </div>
        </div>

        {{-- Info --}}
        <div class="glass-card">
            <div class="card-title">Informasi</div>
            <div class="info-text">
                <ul>
                    <li>Harap menunggu hingga nomor Anda tampil pada layar dan dipanggil oleh petugas.</li>
                    <li>Siapkan kartu identitas dan kartu BPJS (jika ada) sebelum memasuki ruang pelayanan.</li>
                    <li>Jika nomor Anda terlewat, segera hubungi petugas di loket informasi.</li>
                    <li>Layar ini diperbarui secara otomatis setiap beberapa detik.</li>
                </ul>
            </div>
        </div>
    </section>
</div>

<div class="footer-bar">
    <marquee behavior="scroll" direction="left" scrollamount="4">
        Terima kasih atas kesabaran Anda. Jaga kesehatan, gunakan masker bila sedang kurang sehat, dan ikuti arahan petugas Puskesmas Kaligandu. &nbsp;•&nbsp;
        Tetap jaga jarak dan cuci tangan secara berkala. &nbsp;•&nbsp; Semoga lekas sembuh.
    </marquee>
</div>

{{-- 🔉 Audio ding-dong --}}
<audio id="bell-audio" src="{{ asset('assets/sound/bell.mp3') }}"></audio>

<script>
    // Jam & tanggal
    function updateDateTime() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('clock').innerText = h + ':' + m + ':' + s;

        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateStr = now.toLocaleDateString('id-ID', options);
        document.getElementById('dateText').innerText = dateStr;
    }
    setInterval(updateDateTime, 1000);
    updateDateTime();

    // nomor terakhir yang ditampilkan (untuk deteksi perubahan)
    let lastCurrentNumber = null;

    // flag untuk mengizinkan audio (browser butuh interaksi user)
    let audioAllowed = false;

    // 🔔 fungsi untuk mainkan suara ding-dong
    function playBell() {
        const audio = document.getElementById('bell-audio');
        if (!audio) return;
        if (!audioAllowed) return;

        audio.currentTime = 0;
        audio.play().catch((err) => {
            console.warn('Audio gagal diputar:', err);
        });
    }

    // pilih voice yang (semoga) cewek & bahasa Indonesia
    function getPreferredVoice() {
        const synth = window.speechSynthesis;
        const voices = synth.getVoices();
        if (!voices || !voices.length) return null;

        // coba cari id-ID yang namanya ada "female", "woman", "wanita"
        let voice = voices.find(v =>
            v.lang.toLowerCase().startsWith('id') &&
            /female|woman|wanita/i.test(v.name)
        );
        // kalau nggak ada, ambil id-ID apa saja
        if (!voice) {
            voice = voices.find(v => v.lang.toLowerCase().startsWith('id'));
        }
        // fallback: suara cewek English kalau ada
        if (!voice) {
            voice = voices.find(v =>
                v.lang.toLowerCase().startsWith('en') &&
                /female|woman|girl|zira|aria/i.test(v.name)
            );
        }
        return voice || null;
    }

    // 🔊 fungsi text-to-speech pengumuman nomor
    function speakQueue(number, poli) {
    if (!audioAllowed) return;
    if (!('speechSynthesis' in window)) return;

    // Versi super halus + jeda panjang
    const text =
        `Nomor antrian... ${number}... ` +
        `dipersilakan... untuk menuju... perlahan... ` +
        `ke poli ${poli}.`;

    const utterance = new SpeechSynthesisUtterance(text);

    utterance.lang = 'id-ID';
    utterance.rate = 0.78;   // lebih pelan
    utterance.pitch = 1.05;  // sedikit lebih tinggi (biar lebih soft)
    utterance.volume = 1.0;

    window.speechSynthesis.cancel();
    window.speechSynthesis.speak(utterance);
}



    // ✅ user klik sekali untuk mengaktifkan suara
    document.addEventListener('click', () => {
        if (audioAllowed) return;
        audioAllowed = true;

        // tes awal: bell + contoh pengumuman setelah 7 detik
        playBell();
        setTimeout(() => {
            speakQueue('contoh satu', 'umum');
        }, 7000);
    }, { once: true });

    // Update data antrian TANPA reload halaman
    async function fetchQueueData() {
        try {
            const response = await fetch("{{ route('tv.data') }}");
            const data = await response.json();

            // Update current
            const currentNumberEl = document.getElementById('current-number');
            const currentPoliEl   = document.getElementById('current-poli');
            const statusBadgeEl   = document.getElementById('status-badge');
            const currentEmptyEl  = document.getElementById('current-empty-text');

            if (data.current) {
                const newNumber = data.current.no_antrian;
                const poli      = String(data.current.poli).toUpperCase();

                // jika nomor berubah → trigger animasi + suara
                if (newNumber !== lastCurrentNumber) {
                    lastCurrentNumber = newNumber;

                    currentNumberEl.textContent = newNumber;
                    currentPoliEl.textContent   = 'POLI ' + poli;

                    currentNumberEl.classList.remove('current-number--highlight');
                    void currentNumberEl.offsetWidth; // reset animasi
                    currentNumberEl.classList.add('current-number--highlight');

                    // 🔔 mainkan bell dulu
                    playBell();
                    // 🔊 7 detik kemudian baru suara baca nomor
                    setTimeout(() => {
                        speakQueue(newNumber, poli);
                    }, 7000);
                } else {
                    // nomor sama, cuma pastikan teks up-to-date
                    currentNumberEl.textContent = newNumber;
                    currentPoliEl.textContent   = 'POLI ' + poli;
                }

                statusBadgeEl.style.display = 'inline-flex';
                currentEmptyEl.style.display = 'none';
            } else {
                currentNumberEl.textContent = '--';
                currentPoliEl.innerHTML     = '&nbsp;';
                statusBadgeEl.style.display = 'none';
                currentEmptyEl.style.display = 'block';
                lastCurrentNumber = null;
            }

            // Update next list
            const nextListEl = document.getElementById('next-list');
            if (data.next && data.next.length > 0) {
                let html = '';
                data.next.forEach(function (row, index) {
                    html += `
                        <div class="next-item" style="animation-delay: ${index * 0.08}s">
                            <div>
                                <div class="next-num">${row.no_antrian}</div>
                                <div class="next-poli">POLI ${String(row.poli).toUpperCase()}</div>
                            </div>
                            <div class="next-badge">
                                ${index === 0 ? 'Segera' : 'Menunggu'}
                            </div>
                        </div>
                    `;
                });
                nextListEl.innerHTML = html;
            } else {
                nextListEl.innerHTML = '<div class="no-data-text">Belum ada antrian berikutnya.</div>';
            }

        } catch (e) {
            console.error('Gagal mengambil data TV antrian:', e);
        }
    }

    // Panggil pertama kali, lalu setiap 5 detik
    fetchQueueData();
    setInterval(fetchQueueData, 5000);
</script>

</body>
</html>
