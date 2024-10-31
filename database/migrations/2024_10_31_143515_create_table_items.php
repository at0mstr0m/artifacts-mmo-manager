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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->tinyText('name')->nullable();
            $table->tinyText('code')->nullable();
            $table->integer('level')->nullable();
            $table->tinyText('type')->nullable();
            $table->tinyText('subtype')->nullable();
            $table->text('description')->nullable();
        });

        Schema::create('effects', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->tinyText('name');
            $table->integer('value');
        });

        Schema::create('effect_item', function (Blueprint $table) {
            $table->foreignId('effect_id')->constrained();
            $table->foreignId('item_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('effect_item');
        Schema::dropIfExists('effects');
        Schema::dropIfExists('items');
    }
};
