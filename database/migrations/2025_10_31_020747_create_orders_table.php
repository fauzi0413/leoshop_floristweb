<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('whatsapp');
            $table->text('address');
            $table->string('status')->default('pending');
            $table->timestamps();
        });

    }

    /**
     * Batalkan migration (rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
