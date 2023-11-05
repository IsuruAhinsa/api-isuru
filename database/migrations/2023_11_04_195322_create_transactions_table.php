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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->morphs('transactionable');
            $table->foreignId('customer_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('payment_method_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained();
            $table->string('trx_id')->nullable();
            $table->tinyInteger('transaction_type')->comment('1 = Credit, 2 = Debit')->nullable();
            $table->integer('amount')->nullable();
            $table->boolean('status')->default(TRUE)->comment('TRUE = success, FALSE = failed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
