<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Exceptions\EndJob;
use App\Models\Character;
use App\Traits\SelfDispatchable;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class CharacterJob implements ShouldBeUniqueUntilProcessing, ShouldQueue
{
    use Queueable;
    use SelfDispatchable;

    protected Character $character;

    /**
     * Create a new job instance.
     */
    public function __construct(protected int $characterId) {}

    /**
     * Get the middleware the job should pass through.
     *
     * @return array<int, object>
     */
    public function middleware(): array
    {
        return [(new WithoutOverlapping($this->characterId))->releaseAfter(10)];
    }

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return (string) $this->characterId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->character = Character::find($this->characterId)->refetch();

        $cooldownExpiration = $this->character->cooldown_expiration;
        if ($cooldownExpiration->isAfter(now())) {
            $this->log('Character is on cooldown');
            $this->release($cooldownExpiration);

            return;
        }

        $this->log('start handling character');

        try {
            $this->handleCharacter();
        } catch (EndJob) {
            // do nothing, just handy to exit nested logic easily
        }

        $this->log('finished handling character');
    }

    abstract protected function handleCharacter(): void;

    protected function log(string $message, array $replacements = []): void
    {
        Log::info(
            $this->character->name
                . ' '
                . static::class
                . ' "'
                . Str::replaceArray(':?', $replacements, $message)
                . '"'
        );
    }
}
