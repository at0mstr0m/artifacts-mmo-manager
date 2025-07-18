<?php

declare(strict_types=1);

use App\Enums\ComparisonOperators;
use App\Enums\EffectSubTypes;
use App\Enums\EffectTypes;
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
            $table->string('name');
            $table->string('code')->unique();
            $table->integer('level');
            $table->string('type');
            $table->string('subtype');
            $table->text('description');
            $table->boolean('tradeable');
            $table->integer('deposited')
                ->default(0);
        });

        Schema::create('effects', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description');
            $table->enum('type', EffectTypes::values());
            $table->enum('subtype', EffectSubTypes::values());
        });

        Schema::create('effect_item', function (Blueprint $table) {
            $table->foreignId('effect_id')->constrained();
            $table->foreignId('item_id')->constrained();
            $table->integer('value');
        });

        Schema::create('item_conditions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('item_id')->constrained();
            $table->string('code');
            $table->enum('operator', ComparisonOperators::values());
            $table->integer('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_conditions');
        Schema::dropIfExists('effect_item');
        Schema::dropIfExists('effects');
        Schema::dropIfExists('items');
    }
};
