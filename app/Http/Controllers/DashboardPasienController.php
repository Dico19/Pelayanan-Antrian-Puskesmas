<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardPasienController extends Controller
{
    public function index(Request $request)
    {
        $patients = Patient::query()
            ->when($request->search, function ($q, $search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_ktp', 'like', "%{$search}%");
            })
            ->orderBy('nama')
            ->paginate(10);

        return view('dashboard.pasien.index', compact('patients'));
    }

    public function show(Patient $patient)
    {
        // Riwayat antrian AKTIF (yang masih ada di tabel antrians)
        $riwayatAktif = Antrian::where('patient_id', $patient->id)
            ->select([
                'id',
                'patient_id',
                'no_antrian',
                'poli',
                'tanggal_antrian',
                'pekerjaan',      // <-- tambahkan ini
                'is_call',
                'created_at',
                'updated_at',
            ])
            ->get();

        // Riwayat antrian LAMA (yang sudah dipindah ke tabel riwayat_antrians)
        $riwayatLama = DB::table('riwayat_antrians')
            ->where('patient_id', $patient->id)
            ->select([
                'id',
                'patient_id',
                'no_antrian',
                'poli',
                'tanggal_antrian',
                'pekerjaan',      // <-- tambahkan ini juga
                'is_call',
                'created_at',
                'updated_at',
            ])
            ->get();

        // Gabungkan keduanya jadi satu collection
        $riwayatGabungan = $riwayatAktif
            ->concat($riwayatLama)
            ->sortByDesc('tanggal_antrian')
            ->values(); // reset index

        // Supaya view lama yang pakai $antrians tetap aman
        $riwayat  = $riwayatGabungan;
        $antrians = $riwayatGabungan;

        return view('dashboard.pasien.show', compact('patient', 'riwayat', 'antrians'));
    }
}
