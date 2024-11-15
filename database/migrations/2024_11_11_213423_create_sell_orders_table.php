<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sell_orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('identifier')->unique();
            $table->foreignId('item_id')->constrained();
            $table->string('seller')->nullable();
            $table->string('buyer')->nullable();
            $table->dateTime('placed_at')->nullable();
            $table->dateTime('sold_at')->nullable();
            $table->integer('quantity');
            $table->integer('price');
            $table->integer('total_price');
            $table->integer('tax')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sell_orders');
    }
};
