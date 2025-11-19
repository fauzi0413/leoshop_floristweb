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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->decimal('total_price', 15, 2)->default(0)->after('address');
            $table->string('payment_type')->nullable()->after('total_price');
            $table->string('transaction_status')->default('pending')->after('payment_type');
            $table->string('midtrans_order_id')->nullable()->after('transaction_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
