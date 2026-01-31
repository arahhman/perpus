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
        Schema::table('MasterMahasiswa', function (Blueprint $table) {
            $table->enum('flag_aktif', ['Y', 'N'])->default('Y');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('MasterMahasiswa', function (Blueprint $table) {
            $table->dropColumn('flag_aktif');
        });
    }
};
