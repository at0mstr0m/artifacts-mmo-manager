<?php

declare(strict_types=1);

namespace App\Traits;

use App\Data\NextJobData;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;

/**
 * @mixin Queueable
 */
trait SelfDispatchable
{
    protected array $constructorArguments = [];

    /** @var Collection<NextJobData> */
    protected ?Collection $nextJobs = null;

    /**
     * @param Collection<NextJobData> $nextJobs
     */
    public function setNextJobs(null|Collection|NextJobData $nextJobs): static
    {
        $this->nextJobs = $nextJobs ? collect($nextJobs) : null;

        return $this;
    }

    public function pushNextJob(NextJobData $data): static
    {
        empty($this->nextJobs)
            ? $this->nextJobs = collect([$data])
            : $this->nextJobs->push($data);

        return $this;
    }

    public function unshiftNextJob(NextJobData $data): static
    {
        empty($this->nextJobs)
            ? $this->nextJobs = collect([$data])
            : $this->nextJobs->unshift($data);

        return $this;
    }

    public function makeNextJobData(): NextJobData
    {
        return new NextJobData(static::class, $this->constructorArguments);
    }

    public function dispatchNextJob(): ?PendingDispatch
    {
        /** @var ?NextJobData */
        $nextJob = $this->nextJobs?->shift();

        if (! $nextJob) {
            return null;
        }

        return dispatch($nextJob->makeJob()->setNextJobs($this->nextJobs));
    }

    /**
     * @param self&ShouldQueue $job
     */
    public function dispatchWithComeback(
        ShouldQueue $job
    ): PendingDispatch {
        return dispatch(
            $job->setNextJobs($this->nextJobs)
                ->unshiftNextJob($this->makeNextJobData())
        );
    }

    protected function selfDispatch(array $arguments = []): PendingDispatch
    {
        return dispatch((new static(...[
            ...$this->constructorArguments,
            ...$arguments,
        ]))->setNextJobs($this->nextJobs));
    }
}
