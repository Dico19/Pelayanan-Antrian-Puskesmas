<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            // Tambahkan kolom jika belum ada
            if (!Schema::hasColumn('patients', 'nama')) {
                $table->string('nama')->after('id');
            }
            if (!Schema::hasColumn('patients', 'alamat')) {
                $table->text('alamat')->after('nama');
            }
            if (!Schema::hasColumn('patients', 'jenis_kelamin')) {
                $table->enum('jenis_kelamin', ['laki-laki', 'perempuan'])->after('alamat');
            }
            if (!Schema::hasColumn('patients', 'no_hp')) {
                $table->string('no_hp')->after('jenis_kelamin');
            }
            if (!Schema::hasColumn('patients', 'no_ktp')) {
                $table->string('no_ktp')->unique()->after('no_hp');
            }
            if (!Schema::hasColumn('patients', 'tgl_lahir')) {
                $table->date('tgl_lahir')->after('no_ktp');
            }
            if (!Schema::hasColumn('patients', 'pekerjaan')) {
                $table->string('pekerjaan')->after('tgl_lahir');
            }
        });
    }

    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'nama',
                'alamat',
                'jenis_kelamin',
                'no_hp',
                'no_ktp',
                'tgl_lahir',
                'pekerjaan',
            ]);
        });
    }
};
