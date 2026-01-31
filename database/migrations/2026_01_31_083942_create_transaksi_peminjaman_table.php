<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('TransaksiPeminjaman', function (Blueprint $table) {
            $table->string('id_user');
            $table->string('id_buku');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            $table->enum('flag_end', ['Y', 'N'])->default('N');
            $table->timestamps();

            $table->primary(['id_user', 'id_buku']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TransaksiPeminjaman');
    }
};
