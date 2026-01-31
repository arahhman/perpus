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
        Schema::create('MasterMahasiswa', function (Blueprint $table) {
            $table->string('id_user');
            $table->string('nim');
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('alamat');
            $table->date('ttl')->nullable();
            $table->string('program_studi')->nullable();
            $table->timestamps();

            $table->primary('id_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('MasterMahasiswa');
    }
};
