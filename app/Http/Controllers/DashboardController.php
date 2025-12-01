<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $poliUmum   = Antrian::where('poli', 'umum')->where('is_call', false)->count();
        $poliGigi   = Antrian::where('poli', 'gigi')->where('is_call', false)->count();
        $poliTht    = Antrian::where('poli', 'tht')->where('is_call', false)->count();
        $polLansia  = Antrian::where('poli', 'lansia & disabilitas')->where('is_call', false)->count();
        $poliBalita = Antrian::where('poli', 'balita')->where('is_call', false)->count();
        $poliKia    = Antrian::where('poli', 'kia & kb')->where('is_call', false)->count();
        $poliNifas  = Antrian::where('poli', 'nifas/pnc')->where('is_call', false)->count();

        return view('dashboard.index', [
            'poliUmum'   => $poliUmum,
            'poliGigi'   => $poliGigi,
            'poliTht'    => $poliTht,
            'poliLansia' => $polLansia,
            'poliBalita' => $poliBalita,
            'poliKia'    => $poliKia,
            'poliNifas'  => $poliNifas,
        ]);
    }

    /**
     * Reset antrian HARI INI:
     * - Pindahkan antrian dengan tanggal_antrian = hari ini ke tabel riwayat_antrians
     * - Hapus dari tabel antrians
     * Dipanggil dari tombol di dashboard (route: admin.dashboard.reset-antrian)
     */
    public function resetAntrianHariIni()
    {
        $today = now()->toDateString();

        // Ambil semua antrian HARI INI saja
        $antrians = Antrian::whereDate('tanggal_antrian', $today)->get();

        if ($antrians->isEmpty()) {
            return back()->with('status', 'Tidak ada data antrian pada tanggal hari ini.');
        }

        DB::transaction(function () use ($antrians) {
            // Pindahkan ke tabel riwayat_antrians
            foreach ($antrians as $antrian) {
                DB::table('riwayat_antrians')->insert([
                    'patient_id'       => $antrian->patient_id,
                    'user_id'          => $antrian->user_id,
                    'no_antrian'       => $antrian->no_antrian,
                    'nama'             => $antrian->nama,
                    'alamat'           => $antrian->alamat,
                    'jenis_kelamin'    => $antrian->jenis_kelamin,
                    'no_hp'            => $antrian->no_hp,
                    'no_ktp'           => $antrian->no_ktp,
                    'poli'             => $antrian->poli,
                    'tgl_lahir'        => $antrian->tgl_lahir,
                    'pekerjaan'        => $antrian->pekerjaan,
                    'is_call'          => $antrian->is_call,
                    'tanggal_antrian'  => $antrian->tanggal_antrian,
                    'closed_at'        => now(),
                    'created_at'       => $antrian->created_at,
                    'updated_at'       => $antrian->updated_at,
                ]);
            }

            // Hapus semua antrian HARI INI dari tabel antrians
            Antrian::whereIn('id', $antrians->pluck('id'))->delete();
        });

        return back()->with('success', 'Antrian hari ini berhasil di-reset.');
    }
}
