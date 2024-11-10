<?php

declare(strict_types=1);

use App\Enums\Skills;
use App\Enums\TaskTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('code');
            $table->integer('level');
            $table->enum('type', TaskTypes::values());
            $table->integer('min_quantity');
            $table->integer('max_quantity');
            $table->enum('skill', Skills::values())->nullable();
            $table->integer('rewarded_coins');
        });

        Schema::create('task_rewards', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('code');
            $table->integer('min_quantity');
            $table->integer('max_quantity');
            $table->integer('rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_rewards');
        Schema::dropIfExists('tasks');
    }
};
