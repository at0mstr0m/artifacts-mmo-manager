<?php

declare(strict_types=1);

use App\Enums\Skills;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('crafts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('item_id')->constrained();
            $table->enum('skill', Skills::values());
            $table->integer('level');
            $table->integer('quantity');
        });

        Schema::create('craft_item', function (Blueprint $table) {
            $table->foreignId('craft_id')->constrained();
            $table->string('item_code');
            $table->unique(['craft_id', 'item_code']);
            $table->integer('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('craft_item');
        Schema::dropIfExists('craft');
    }
};
