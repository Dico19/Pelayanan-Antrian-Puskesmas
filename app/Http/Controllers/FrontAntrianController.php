<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;

class FrontAntrianController extends Controller
{
    /**
     * Halaman form ambil antrian (view utama antrian).
     */
    public function index()
    {
        return view('antrian.index');
    }

    /**
     * Kalau suatu saat mau pisah form create, sekarang diarahkan ke index saja.
     */
    public function create()
    {
        return redirect()->route('antrian.index');
    }

    /**
     * Halaman form cari antrian via NIK.
     * URL: GET /antrian/cari
     */
    public function showCariAntrianForm()
    {
        return view('antrian.cari-nik');
    }

    /**
     * Alias lama: supaya route yang masih pakai 'cariProses'
     * tetap jalan. Dia cuma meneruskan ke searchByNik().
     *
     * URL: POST /antrian/cari
     */
    public function cariProses(Request $request)
    {
        return $this->searchByNik($request);
    }

    /**
     * Proses pencarian antrian berdasarkan NIK (no_ktp).
     * URL: POST /antrian/cari
     */
    public function searchByNik(Request $request)
    {
        // Validasi basic
        $request->validate([
            'no_ktp' => 'required|string',
        ], [
            'no_ktp.required' => 'Silakan masukkan NIK Anda.',
        ]);

        $nik = $request->no_ktp;

        // 1 NIK bisa punya beberapa antrian di tanggal berbeda
        $antrians = Antrian::where('no_ktp', $nik)
            ->orderByDesc('tanggal_antrian')
            ->get();

        // Jika tidak ada data antrian untuk NIK tsb
        if ($antrians->isEmpty()) {
            return back()
                ->withInput()
                ->with('nik_not_found', 'NIK belum terdaftar / tidak valid.');
        }

        return view('antrian.hasil-cari-nik', [
            'nik'      => $nik,
            'antrians' => $antrians,
        ]);
    }

    /**
     * Halaman tiket antrian (untuk cetak tiket + QR status + QR survei)
     * URL: /antrian/tiket/{id}
     */
    public function tiketAntrian($id)
    {
        $antrian = Antrian::findOrFail($id);

        /** -------- QR 1: STATUS ANTRIAN -------- */
        $statusUrl = route('antrian.status', $antrian->id);
        $qrStatus  = $this->generateQrDataUri($statusUrl);

        /** -------- QR 2: SURVEY GOOGLE FORM -------- */
        // Ganti link ini pakai link Google Form survei kepuasan kamu sendiri
        $surveyUrl = 'https://docs.google.com/forms/d/e/1FAIpQLSdoPhLJcn4n4TdPje5dvg9AiBh-uVzu58DHW6ZvML6wjLlsgg/viewform?usp=header';
        $qrSurvey  = $this->generateQrDataUri($surveyUrl);

        return view('antrian.tiket', [
            'antrian'  => $antrian,
            'qrStatus' => $qrStatus,
            'qrSurvey' => $qrSurvey,
        ]);
    }

    /**
     * Helper kecil: generate QR dengan Google Chart, lalu kembalikan data URI base64.
     */
    protected function generateQrDataUri(string $text, int $size = 280): string
    {
        $url = 'https://chart.googleapis.com/chart?chs=' . $size . 'x' . $size . '&cht=qr&chl='
             . urlencode($text);

        // Ambil PNG dari Google Chart
        $png = @file_get_contents($url);

        // Kalau gagal (misal tidak ada internet), balikin string kosong biar view bisa handle
        if ($png === false) {
            return '';
        }

        return 'data:image/png;base64,' . base64_encode($png);
    }

    /**
     * Simpan antrian baru dari form publik.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'            => 'required|string',
            'alamat'          => 'required|string',
            'jenis_kelamin'   => 'required|string',
            'no_hp'           => 'required|string',
            'no_ktp'          => 'required|string',
            'tgl_lahir'       => 'required|date',
            'pekerjaan'       => 'nullable|string',
            'poli'            => 'required|string',
            'tanggal_antrian' => 'nullable|date', // boleh dikirim dari form
        ]);

        // Tanggal layanan: dari form kalau ada, kalau tidak pakai hari ini
        $tanggalLayanan = !empty($data['tanggal_antrian'])
            ? Carbon::parse($data['tanggal_antrian'])->toDateString()
            : now()->toDateString();

        // Cek pasien berdasarkan NIK
        $patient = Patient::where('no_ktp', $data['no_ktp'])->first();

        // Kalau belum ada, buat pasien baru
        if (! $patient) {
            $patient = Patient::create([
                'nama'          => $data['nama'],
                'alamat'        => $data['alamat'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'no_hp'         => $data['no_hp'],
                'no_ktp'        => $data['no_ktp'],
                'tgl_lahir'     => $data['tgl_lahir'],
                'pekerjaan'     => $data['pekerjaan'] ?? null,
            ]);
        }

        // Generate nomor antrian berdasarkan poli & tanggal layanan
        $noAntrian = $this->generateNoAntrianForDate($data['poli'], $tanggalLayanan);

        $antrian = Antrian::create([
            'patient_id'      => $patient->id,
            'user_id'         => Auth::check() ? Auth::id() : null,
            'no_antrian'      => $noAntrian,
            'nama'            => $data['nama'],
            'alamat'          => $data['alamat'],
            'jenis_kelamin'   => $data['jenis_kelamin'],
            'no_hp'           => $data['no_hp'],
            'no_ktp'          => $data['no_ktp'],
            'tgl_lahir'       => $data['tgl_lahir'],
            'pekerjaan'       => $data['pekerjaan'] ?? null,
            'poli'            => $data['poli'],
            'tanggal_antrian' => $tanggalLayanan,
            'is_call'         => 0,
        ]);

        return redirect()
            ->route('antrian.index')
            ->with('success', 'Berhasil mengambil nomor antrian: ' . $antrian->no_antrian);
    }

    /**
     * Detail antrian.
     */
    public function show(Antrian $antrian)
    {
        return view('antrian.show', compact('antrian'));
    }

    /**
     * Halaman status antrian (dipakai di QR status).
     */
    public function status(Antrian $antrian)
    {
        $orangDiDepan = Antrian::where('poli', $antrian->poli)
            ->whereDate('tanggal_antrian', $antrian->tanggal_antrian)
            ->where('is_call', 0)
            ->where('id', '<', $antrian->id)
            ->count();

        $menitPerOrang = 10;
        $estimasiMenit = $orangDiDepan * $menitPerOrang;

        return view('antrian.status', [
            'antrian'       => $antrian,
            'orangDiDepan'  => $orangDiDepan,
            'estimasiMenit' => $estimasiMenit,
        ]);
    }

    /**
     * Profil pasien + riwayat antrian.
     */
    public function profilPasien($id)
    {
        $pasien = Patient::findOrFail($id);

        $riwayat = Antrian::where('patient_id', $pasien->id)
            ->orderByDesc('tanggal_antrian')
            ->take(10)
            ->get();

        return view('livewire.pasien.profil', [
            'pasien'  => $pasien,
            'riwayat' => $riwayat,
        ]);
    }

    /**
     * Kartu pasien.
     */
    public function kartuPasien($id)
    {
        $pasien = Patient::findOrFail($id);

        return view('livewire.pasien.kartu', [
            'pasien' => $pasien,
        ]);
    }

    /**
     * Form edit antrian dari sisi pasien.
     * (biodata + poli + tanggal)
     */
    public function edit(Antrian $antrian)
    {
        // daftar poli (disamakan dengan generateNoAntrianForDate)
        $poliOptions = [
            'umum'                 => 'Umum',
            'gigi'                 => 'Gigi',
            'tht'                  => 'THT',
            'balita'               => 'Balita',
            'kia & kb'             => 'KIA & KB',
            'nifas/pnc'            => 'Nifas / PNC',
            'lansia & disabilitas' => 'Lansia & Disabilitas',
        ];

        // 6 hari ke depan, tidak termasuk hari Minggu
        $tanggalOptions = [];
        $date = now();
        $count = 0;

        while ($count < 6) {
            if (! $date->isSunday()) {
                $tanggalOptions[] = $date->copy();
                $count++;
            }
            $date->addDay();
        }

        return view('antrian.edit', [
            'antrian'        => $antrian,
            'poliOptions'    => $poliOptions,
            'tanggalOptions' => $tanggalOptions,
        ]);
    }

    /**
     * Update data antrian.
     * Sekarang bisa ubah biodata + poli + tanggal antrian.
     */
    public function update(Request $request, Antrian $antrian)
    {
        $data = $request->validate([
            'nama'            => 'required|string|max:255',
            'alamat'          => 'required|string',
            'jenis_kelamin'   => 'required|in:laki-laki,perempuan',
            'no_hp'           => 'required|string|max:20',
            'pekerjaan'       => 'nullable|string|max:255',
            'poli'            => 'required|string',
            'tanggal_antrian' => 'required|date',
        ]);

        $antrian->update($data);

        return redirect()
            ->route('antrian.cari')
            ->with('success', 'Data antrian berhasil diperbarui.');
    }

        /**
     * Hapus antrian.
     */
    public function destroy(Antrian $antrian)
    {
        $antrian->delete();

        return redirect()
            ->route('antrian.cari')
            ->with('success', 'Antrian berhasil dihapus.');
    }

    /**
     * Generate nomor antrian berdasarkan poli + tanggal (default hari ini).
     */
    protected function generateNoAntrian(string $poli): string
    {
        return $this->generateNoAntrianForDate($poli, now()->toDateString());
    }

    /**
     * Generate nomor antrian berdasarkan poli + tanggal tertentu.
     */
    protected function generateNoAntrianForDate(string $poli, string $tanggal): string
    {
        $prefixMap = [
            'umum'                 => 'U',
            'gigi'                 => 'G',
            'tht'                  => 'T',
            'balita'               => 'B',
            'kia & kb'             => 'K',
            'kia & kb '            => 'K',
            'nifas/pnc'            => 'N',
            'lansia & disabilitas' => 'L',
        ];

        $key    = strtolower($poli);
        $prefix = $prefixMap[$key] ?? 'A';

        $last = Antrian::where('poli', $poli)
            ->whereDate('tanggal_antrian', $tanggal)
            ->orderByDesc('id')
            ->first();

        if ($last && preg_match('/\d+$/', $last->no_antrian, $match)) {
            $nextNumber = (int) $match[0] + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . $nextNumber;
    }
}
