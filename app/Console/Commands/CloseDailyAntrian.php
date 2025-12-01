<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Antrian;
use Illuminate\Support\Facades\DB;

class CloseDailyAntrian extends Command
{
    // nama command di terminal
    protected $signature = 'antrian:close-daily';

    protected $description = 'Pindahkan antrian tanggal yang sudah lewat ke tabel riwayat_antrians dan kosongkan dari antrians';

    public function handle(): int
    {
        $today = now()->toDateString();

        // kita tutup SEMUA antrian yang tanggal_antrian-nya SUDAH LEWAT hari ini
        // artinya: kemarin dan hari-hari sebelumnya
        $this->info("Memindahkan data antrian dengan tanggal < {$today} ke tabel riwayat_antrians...");

        $antrians = Antrian::whereDate('tanggal_antrian', '<', $today)->get();

        if ($antrians->isEmpty()) {
            $this->info('Tidak ada data antrian yang sudah lewat.');
            return Command::SUCCESS;
        }

        DB::transaction(function () use ($antrians) {
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

            // hapus dari tabel antrians (supaya tabel utama hanya berisi antrian hari ini & masa depan)
            Antrian::whereIn('id', $antrians->pluck('id'))->delete();
        });

        $this->info('Selesai. Semua antrian yang tanggalnya sudah lewat sudah dipindahkan ke riwayat.');

        return Command::SUCCESS;
    }
}
