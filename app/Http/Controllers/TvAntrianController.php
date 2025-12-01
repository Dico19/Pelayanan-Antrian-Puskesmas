<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Antrian;

class TvAntrianController extends Controller
{
    public function index()
    {
        // Tanggal hari ini
        $today = date('Y-m-d');

        // Antrian yang sedang dipanggil (is_call = 1)
        $current = Antrian::where('tanggal_antrian', $today)
            ->where('is_call', 1)
            ->orderBy('updated_at', 'desc')
            ->first();

        // Antrian berikutnya (is_call = 0)
        $next = Antrian::where('tanggal_antrian', $today)
            ->where('is_call', 0)
            ->orderBy('no_antrian', 'asc')
            ->take(5)
            ->get();

        return view('tv.index', compact('current', 'next'));
    }

    // 🔹 endpoint JSON untuk diambil lewat JS (tanpa reload halaman)
    public function data()
    {
        $today = date('Y-m-d');

        $current = Antrian::where('tanggal_antrian', $today)
            ->where('is_call', 1)
            ->orderBy('updated_at', 'desc')
            ->first();

        $next = Antrian::where('tanggal_antrian', $today)
            ->where('is_call', 0)
            ->orderBy('no_antrian', 'asc')
            ->take(5)
            ->get();

        return response()->json([
            'current' => $current ? [
                'no_antrian' => $current->no_antrian,
                'poli'       => $current->poli,
            ] : null,
            'next' => $next->map(function ($row) {
                return [
                    'no_antrian' => $row->no_antrian,
                    'poli'       => $row->poli,
                ];
            })->values(),
        ]);
    }
}
