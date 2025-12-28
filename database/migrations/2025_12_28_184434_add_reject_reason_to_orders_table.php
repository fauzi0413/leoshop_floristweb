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
            $table->string('reject_reason', 255)->nullable()->after('cancel_reason');
            $table->timestamp('rejected_at')->nullable()->after('reject_reason'); // opsional tapi bagus
            // opsional kalau mau simpan siapa adminnya:
            // $table->foreignId('rejected_by')->nullable()->after('rejected_at')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // kalau pakai foreign:
            // $table->dropConstrainedForeignId('rejected_by');
            $table->dropColumn(['reject_reason', 'rejected_at']);
        });
    }

};
