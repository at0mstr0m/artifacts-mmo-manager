<?php

declare(strict_types=1);

namespace App\Traits;

use App\Enums\RateLimitTypes;
use App\Services\ArtifactsService;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

/**
 * @property string $url
 * @property-read int MAX_PER_PAGE = 100
 *
 * @mixin ArtifactsService
 */
trait MakesRequests
{
    private const string URL = 'https://api.artifactsmmo.com/';

    private function logRequest(
        string $method,
        string $path,
        array $payload
    ): void {
        $method = strtoupper($method);
        $payload = json_encode($payload);

        Log::channel('api_requests')
            ->info("{$method} {$path} {$payload}");
    }

    private function baseRequest(
        ?RateLimitTypes $type,
        string $method,
        string $path,
        array $payload,
        int $attempts = 0
    ): Response {
        if ($attempts > 30) {
            throw new \Exception('Too many attempts.');
        }

        /** @var bool|PendingRequest */
        $request = $this->handleRateLimits($type);

        if (! $request) {
            sleep(1);

            return $this->baseRequest(
                $type,
                $method,
                $path,
                $payload,
                $attempts + 1
            );
        }

        $this->logRequest($method, $path, $payload);

        return $request
            ->{$method}(dump(self::URL . $path), $payload)
            ->throwUnlessStatus(HttpResponse::HTTP_OK);
    }

    private function get(
        string $path = '',
        ?RateLimitTypes $type = null,
        array $query = [],
    ): Response {
        return $this->baseRequest($type, 'get', $path, $query);
    }

    private function post(
        string $path,
        ?RateLimitTypes $type = null,
        array $payload = [],
    ): Response {
        return $this->baseRequest($type, 'post', $path, $payload);
    }

    private function getAllPagesData(
        Collection $data,
        Response $response,
        string $methodName,
        int $page,
        int $perPage,
        array $arguments = []
    ): Collection {
        $currentPage = $response->json('page');
        $totalPages = $response->json('pages');

        for (
            $currentPage = $page + 1;
            $currentPage <= $totalPages;
            ++$currentPage
        ) {
            $data = $data->concat($this->{$methodName}(...[
                'perPage' => $perPage,
                'page' => $currentPage,
                ...$arguments,
            ]));
        }

        return $data;
    }

    private static function paginationParams(
        int $perPage,
        int $page,
        bool $all
    ): array {
        return [
            'size' => $all ? static::MAX_PER_PAGE : $perPage,
            'page' => $page,
        ];
    }

    private function handleRateLimits(
        ?RateLimitTypes $type = null
    ): bool|PendingRequest {
        $callback = fn () => Http::withToken($this->token)
            ->retry(2, 1000, function (\Exception $exception) {
                $result = $exception instanceof RequestException
                    && $exception->response->status() === 499;
                if ($result) {
                    Log::channel('api_requests')
                        ->info('Retrying request after 499 status code.');
                }

                return $result;
            });
        if (! $type) {
            return $callback();
        }
        $limits = $type->getLimits();
        $onlyHourlyLimit = count($limits) === 1;

        $request = null;
        if (array_key_exists('hour', $limits)) {
            $request = RateLimiter::attempt(
                $type->value . '_per_hour',
                $limits['hour'],
                /*
                 * No need to create a new Http instance beffore the other rate
                 * limits are checked.
                 */
                $onlyHourlyLimit ? $callback : fn () => true,
                60 * 60 // 1 hour
            );
            if ($onlyHourlyLimit) {
                return $request;
            }
        }

        /** @var bool|PendingRequest */
        $request = RateLimiter::attempt(
            $type->value . '_per_minute',
            $limits['minute'],
            fn () => true,  // no need to create a new Http instance yet
        );

        if (! $request) {
            return $request;
        }

        return RateLimiter::attempt(
            $type->value . '_per_second',
            $limits['second'],
            $callback,
            1
        );
    }
}
