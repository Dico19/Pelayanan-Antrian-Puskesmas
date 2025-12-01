<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_antrians', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('patient_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('no_antrian', 255);
            $table->string('nama', 255);
            $table->text('alamat')->nullable();
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->string('no_hp', 255)->nullable();
            $table->string('no_ktp', 255)->nullable();

            $table->enum('poli', ['umum', 'gigi', 'tht', 'lansia & disabilitas', 'balita', 'kia & kb', 'nifas/pnc']);
            $table->date('tgl_lahir')->nullable();
            $table->string('pekerjaan', 255)->nullable();

            $table->boolean('is_call')->default(0);
            $table->date('tanggal_antrian');

            // kapan dipindahkan ke riwayat
            $table->timestamp('closed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_antrians');
    }
};
