<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->tinyText('name');
            $table->tinyText('skin');
            $table->tinyInteger('level');

            $table->integer('xp');
            $table->integer('max_xp');
            $table->integer('achievements_points');
            $table->integer('gold');
            $table->integer('speed');

            $table->tinyInteger('mining_level');
            $table->integer('mining_xp');
            $table->integer('mining_max_xp');

            $table->tinyInteger('woodcutting_level');
            $table->integer('woodcutting_xp');
            $table->integer('woodcutting_max_xp');

            $table->tinyInteger('fishing_level');
            $table->integer('fishing_xp');
            $table->integer('fishing_max_xp');

            $table->tinyInteger('weaponcrafting_level');
            $table->integer('weaponcrafting_xp');
            $table->integer('weaponcrafting_max_xp');

            $table->tinyInteger('gearcrafting_level');
            $table->integer('gearcrafting_xp');
            $table->integer('gearcrafting_max_xp');

            $table->tinyInteger('jewelrycrafting_level');
            $table->integer('jewelrycrafting_xp');
            $table->integer('jewelrycrafting_max_xp');

            $table->tinyInteger('cooking_level');
            $table->integer('cooking_xp');
            $table->integer('cooking_max_xp');

            $table->integer('hp');
            $table->integer('haste');
            $table->integer('critical_strike');
            $table->integer('stamina');

            $table->integer('attack_fire');
            $table->integer('attack_earth');
            $table->integer('attack_water');
            $table->integer('attack_air');

            $table->integer('dmg_fire');
            $table->integer('dmg_earth');
            $table->integer('dmg_water');
            $table->integer('dmg_air');

            $table->integer('res_fire');
            $table->integer('res_earth');
            $table->integer('res_water');
            $table->integer('res_air');

            $table->integer('x');
            $table->integer('y');

            $table->integer('cooldown');
            $table->dateTime('cooldown_expiration');

            $table->tinyText('weapon_slot');
            $table->tinyText('shield_slot');
            $table->tinyText('helmet_slot');
            $table->tinyText('body_armor_slot');
            $table->tinyText('leg_armor_slot');
            $table->tinyText('boots_slot');
            $table->tinyText('ring1_slot');
            $table->tinyText('ring2_slot');
            $table->tinyText('amulet_slot');
            $table->tinyText('artifact1_slot');
            $table->tinyText('artifact2_slot');
            $table->tinyText('consumable1_slot');
            $table->tinyInteger('consumable1_slot_quantity');
            $table->tinyText('consumable2_slot');
            $table->tinyInteger('consumable2_slot_quantity');

            $table->tinyText('task');
            $table->tinyText('task_type');
            $table->integer('task_progress');
            $table->integer('task_total');
            $table->tinyInteger('inventory_max_items');
        });

        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('character_id')
                ->constrained();
            $table->tinyInteger('slot');
            $table->tinyText('code');
            $table->integer('quantity');
            $table->unique(['character_id', 'slot']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('characters');
    }
};
