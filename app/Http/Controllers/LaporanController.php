<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PDF; // kalau kamu pakai cetak PDF dari barryvdh

class LaporanController extends Controller
{
    // ====== QUERY BERSAMA (filter tanggal, poli, search) ======
    protected function buildQuery(Request $request)
    {
        $query = Antrian::query();

        // contoh filter tanggal (name input: tanggal)
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_antrian', $request->tanggal);
        }

        // contoh filter poli (name input: poli)
        if ($request->filled('poli')) {
            $query->where('poli', $request->poli);
        }

        // cari nama pasien (name input: search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama', 'like', "%{$search}%");
        }

        return $query;
    }

    // ====== HALAMAN LAPORAN ======
    public function index(Request $request)
    {
        $antrians = $this->buildQuery($request)->paginate(10);

        return view('admin.laporan.index', compact('antrians'));
        // sesuaikan path view kalau beda
    }

    // ====== CETAK PDF (opsional, kalau kamu pakai) ======
    public function exportPdf(Request $request)
    {
        $antrians = $this->buildQuery($request)->get();

        $pdf = PDF::loadView('admin.laporan.pdf', compact('antrians'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('laporan-antrian.pdf');
    }

    // ====== EXPORT "EXCEL" VERSI CSV ======
    public function exportExcelCsv(Request $request): StreamedResponse
    {
        $fileName = 'laporan-antrian.csv';

        $antrians = $this->buildQuery($request)->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $columns = [
            'No Antrian',
            'Nama',
            'Alamat',
            'Jenis Kelamin',
            'No HP',
            'No KTP',
            'Tanggal Lahir',
            'Pekerjaan',
            'Poli',
            'Tanggal Antrian',
        ];

        $callback = function () use ($antrians, $columns) {
            $handle = fopen('php://output', 'w');

            // tulis header kolom
            fputcsv($handle, $columns);

            // tulis data baris per baris
            foreach ($antrians as $row) {
                fputcsv($handle, [
                    $row->no_antrian,
                    $row->nama,
                    $row->alamat,
                    $row->jenis_kelamin,
                    $row->no_hp,
                    $row->no_ktp,
                    $row->tgl_lahir,
                    $row->pekerjaan,
                    $row->poli,
                    $row->tanggal_antrian,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
