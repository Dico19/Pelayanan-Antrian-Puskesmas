<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Antrian;
use PDF;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardLaporanController extends Controller
{
    // Query filter bersama (tanggal, poli, search)
    protected function buildQuery(Request $request)
    {
        $query = Antrian::query();

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_antrian', $request->tanggal);
        }

        if ($request->filled('poli')) {
            $query->where('poli', $request->poli);
        }

        if ($request->filled('search')) {
            $query->where('nama', 'like', "%{$request->search}%");
        }

        return $query;
    }

    // Menampilkan halaman laporan admin
    public function index(Request $request)
    {
        $antrians = $this->buildQuery($request)->paginate(10);

        return view('dashboard.laporan.index', compact('antrians'));
    }

    // ==========================
    // EXPORT PDF
    // ==========================
    public function exportPdf(Request $request)
    {
        $antrians = $this->buildQuery($request)->get();

        $pdf = PDF::loadView('dashboard.laporan.pdf', compact('antrians'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('laporan-antrian.pdf');
    }

    // ==========================
    // EXPORT EXCEL (CSV)
    // ==========================
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

            fputcsv($handle, $columns);

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
