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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->nullable();
            $table->integer('total_items');
            $table->decimal('total_price',10,2);
            $table->tinyInteger('discount')->default(0);
            $table->decimal('paid',10,2);
            $table->decimal('received',10,2);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
