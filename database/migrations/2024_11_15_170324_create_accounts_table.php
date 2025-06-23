<?php

declare(strict_types=1);

use App\Enums\MemberStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('username')
                ->unique();
            $table->string('email')
                ->nullable()
                ->unique();
            $table->boolean('is_subscribed');
            $table->integer('subscribed_until')
                ->nullable();
            $table->enum('status', MemberStatus::values());
            $table->json('badges')
                ->nullable();
            $table->integer('gems')
                ->default(0);
            $table->integer('achievements_points');
            $table->boolean('banned')
                ->default(false);
            $table->text('ban_reason')
                ->nullable();
            $table->json('skins');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
