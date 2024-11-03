<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Models\Character;
use Illuminate\Support\Carbon;

class LogData extends Data
{
    public Carbon $loggedAt;

    /**
     * @param  Character  $character
     */
    public function __construct(
        public Character|string $character,
        public string $account,
        public string $type,
        public string $description,
        public ?array $content,
        public null|Carbon|int $cooldown,
        ?string $cooldownExpiration,
        string $createdAt,
    ) {
        $this->cooldown = $cooldown ? Carbon::parse($cooldownExpiration) : null;
        $this->loggedAt = Carbon::parse($createdAt);
        $this->character = Character::firstWhere('name', $character);

        $this->createIfNotExists();
    }

    private function createIfNotExists(): void
    {
        $this->character->logs()->firstOrCreate([
            'account' => $this->account,
            'type' => $this->type,
            'description' => $this->description,
            'content' => $this->content ?? [],
            'cooldown' => $this->cooldown,
            'logged_at' => $this->loggedAt,
        ]);
    }
}
