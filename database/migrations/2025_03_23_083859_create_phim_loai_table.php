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
        Schema::create('phim_loai', function (Blueprint $table) {
            $table->integer('ma_phim')->index('ma_phim');
            $table->integer('ma_loai')->index('ma_loai');

            $table->primary(['ma_phim', 'ma_loai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phim_loai');
    }
};
