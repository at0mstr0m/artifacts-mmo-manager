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
        Schema::create('occupaions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->json('config');
        });

        Schema::table('characters', function (Blueprint $table) {
            $table->foreignId('occupaion_id')
                ->nullable()
                ->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->dropForeign(['occupaion_id']);
            $table->dropColumn('occupaion_id');
        });

        Schema::dropIfExists('occupaions');
    }
};
