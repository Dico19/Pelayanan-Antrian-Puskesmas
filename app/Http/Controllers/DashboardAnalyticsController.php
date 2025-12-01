<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardAnalyticsController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Total antrian hari ini
        $totalToday = DB::table('antrians')
            ->whereDate('tanggal_antrian', $today)
            ->count();

        // Total pasien unik hari ini (berdasarkan patient_id)
        $uniquePatientsToday = DB::table('antrians')
            ->whereDate('tanggal_antrian', $today)
            ->distinct('patient_id')
            ->count('patient_id');

        // Jumlah poli yang aktif hari ini
        $activePoliToday = DB::table('antrians')
            ->whereDate('tanggal_antrian', $today)
            ->distinct('poli')
            ->count('poli');

        // 1. Jumlah pasien per poli (HARI INI)
        $perPoli = DB::table('antrians')
            ->select('poli', DB::raw('COUNT(*) as total'))
            ->whereDate('tanggal_antrian', $today)
            ->groupBy('poli')
            ->get();

        // 2. Jam kunjungan tersibuk (HARI INI)
        $perJam = DB::table('antrians')
            ->select(DB::raw('HOUR(created_at) as jam'), DB::raw('COUNT(*) as total'))
            ->whereDate('created_at', $today)
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->orderBy('jam')
            ->get();

        // Ambil jam tersibuk (kalau ada)
        $busiestHour = null;
        if ($perJam->isNotEmpty()) {
            $max = $perJam->sortByDesc('total')->first();
            $busiestHour = str_pad($max->jam, 2, '0', STR_PAD_LEFT) . ':00';
        }

        // 3. Rata-rata lama tunggu
        $avgWait = DB::table('antrians')
            ->where('is_call', 1)
            ->whereDate('tanggal_antrian', $today)
            ->whereNotNull('created_at')
            ->whereNotNull('updated_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, created_at, updated_at)) as avg_wait'))
            ->value('avg_wait');

        $avgWait = round($avgWait ?? 0, 1);

        // 4. Tren harian – 7 hari terakhir
        $dailyTrend = DB::table('antrians')
            ->select(DB::raw('DATE(tanggal_antrian) as tanggal'), DB::raw('COUNT(*) as total'))
            ->whereDate('tanggal_antrian', '>=', Carbon::today()->subDays(6))
            ->groupBy(DB::raw('DATE(tanggal_antrian)'))
            ->orderBy('tanggal')
            ->get();

        // 5. Tren bulanan – 6 bulan terakhir
        $monthlyTrend = DB::table('antrians')
            ->select(
                DB::raw('DATE_FORMAT(tanggal_antrian, "%Y-%m") as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->whereDate('tanggal_antrian', '>=', Carbon::today()->subMonths(5)->startOfMonth())
            ->groupBy(DB::raw('DATE_FORMAT(tanggal_antrian, "%Y-%m")'))
            ->orderBy('bulan')
            ->get();

        return view('dashboard.analytics.index', compact(
            'perPoli',
            'perJam',
            'avgWait',
            'dailyTrend',
            'monthlyTrend',
            'totalToday',
            'uniquePatientsToday',
            'activePoliToday',
            'busiestHour'
        ));
    }
}
