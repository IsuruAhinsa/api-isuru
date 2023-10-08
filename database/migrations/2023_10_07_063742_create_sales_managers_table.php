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
        Schema::create('sales_managers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('shop_id')->constrained('shops', 'id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('password');
            $table->string('nic')->unique();
            $table->boolean('status');
            $table->text('bio')->nullable();
            $table->string('photo');
            $table->string('nic_photo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_managers');
    }
};
