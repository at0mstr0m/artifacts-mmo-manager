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
        Schema::create('monsters', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('code')->unique();
            $table->integer('level');
            $table->integer('hp');

            $table->integer('attack_fire');
            $table->integer('attack_earth');
            $table->integer('attack_water');
            $table->integer('attack_air');

            $table->integer('res_fire');
            $table->integer('res_earth');
            $table->integer('res_water');
            $table->integer('res_air');

            $table->integer('critical_strike');

            $table->integer('min_gold');
            $table->integer('max_gold');
        });

        Schema::create('drops', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->morphs('source');
            $table->foreignId('item_id')->constrained();
            $table->integer('rate');
            $table->integer('min_quantity');
            $table->integer('max_quantity');
        });

        Schema::create('effect_monster', function (Blueprint $table) {
            $table->foreignId('effect_id')->constrained();
            $table->foreignId('monster_id')->constrained();
            $table->unique(['effect_id', 'monster_id']);
            $table->integer('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drops');
        Schema::dropIfExists('monsters');
    }
};
