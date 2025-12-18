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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nomor_pesanan')->unique();
            $table->decimal('total_harga', 12, 2);
            $table->decimal('diskon', 12, 2)->default(0);
            $table->decimal('total_bayar', 12, 2);
            $table->string('status')->default('pending'); // pending, processing, completed, cancelled
            $table->string('metode_pembayaran')->nullable();
            $table->text('alamat_pengiriman')->nullable();
            
            // Midtrans payment fields
            $table->string('snap_token')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('transaction_status')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('payment_details')->nullable();
            
            // Shipping info
            $table->string('nama_penerima')->nullable();
            $table->string('telepon_penerima')->nullable();
            $table->text('catatan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
