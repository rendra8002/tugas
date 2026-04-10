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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');

            $table->integer('jumlah')->default(1);
            // Di dalam file migration create_peminjamans_table
            $table->enum('status', ['pending', 'approve', 'rejected', 'returned', 'verifikasi'])->default('pending');
            $table->boolean('is_printed')->default(false);
            $table->date('tanggal_pinjam')->nullable();
            $table->date('jatuh_tempo')->nullable();

            // TAMBAHKAN BARIS INI (Ini yang bikin error tadi)
            $table->date('tanggal_kembali')->nullable();

            $table->integer('total_denda')->default(0);
            $table->string('bukti_pembayaran')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
