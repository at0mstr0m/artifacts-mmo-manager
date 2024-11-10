<?php

declare(strict_types=1);

use App\Enums\FightResults;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fights', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('character_id')->constrained();
            $table->integer('xp');
            $table->integer('gold');
            $table->integer('turns');

            $table->integer('monster_blocked_hits_fire');
            $table->integer('monster_blocked_hits_earth');
            $table->integer('monster_blocked_hits_water');
            $table->integer('monster_blocked_hits_air');
            $table->integer('monster_blocked_hits_total');

            $table->integer('player_blocked_hits_fire');
            $table->integer('player_blocked_hits_earth');
            $table->integer('player_blocked_hits_water');
            $table->integer('player_blocked_hits_air');
            $table->integer('player_blocked_hits_total');

            $table->enum('result', FightResults::values());
        });

        Schema::create('fight_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('fight_id')->constrained();
            $table->text('text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fight_logs');
        Schema::dropIfExists('fights');
    }
};
