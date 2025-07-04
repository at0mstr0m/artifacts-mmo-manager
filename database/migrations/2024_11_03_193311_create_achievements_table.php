<?php

declare(strict_types=1);

use App\Enums\AchievementTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('code');
            $table->text('description');
            $table->integer('points');
            $table->enum('type', AchievementTypes::values());
            $table->string('target')->nullable();
            $table->integer('total');
            $table->integer('rewarded_gold');
            $table->integer('current')
                ->nullable();
            $table->dateTime('completed_at')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
