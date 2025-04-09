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
        Schema::create('dat_ve', function (Blueprint $table) {
            $table->integer('ma_ve', true);
            $table->integer('ma_nguoi_dung')->index('ma_nguoi_dung');
            $table->integer('ma_suat_chieu')->index('ma_suat_chieu');
            $table->decimal('tong_gia_tien', 10);
            $table->integer('tong_so_ve');
            $table->dateTime('ngay_dat')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dat_ve');
    }
};
