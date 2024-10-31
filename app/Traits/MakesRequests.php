<?php

declare(strict_types=1);

namespace App\Traits;

use App\Services\ArtifactsService;
use Illuminate\Http\Client\PendingRequest;
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
        string $method,
        string $path,
        array $payload,
        int $attempts = 0
    ): Response {
        if ($attempts > 30) {
            throw new \Exception('Too many attempts.');
        }

        /** @var bool|PendingRequest */
        $request = RateLimiter::attempt(
            'ARTIFACTS_REQUESTS',
            2,
            fn() => Http::withToken($this->token),
            1
        );

        if (! $request) {
            sleep(1);

            return $this->baseRequest($method, $path, $payload, $attempts + 1);
        }

        $this->logRequest($method, $path, $payload);

        return $request->{$method}(self::URL . $path, $payload)
            ->throwUnlessStatus(HttpResponse::HTTP_OK);
    }

    private function get(string $path = '', array $query = []): Response
    {
        return $this->baseRequest('get', $path, $query);
    }

    private function post(string $path, array $payload = []): Response
    {
        return $this->baseRequest('post', $path, $payload);
    }

    private function patch(string $path, array $payload = []): Response
    {
        return $this->baseRequest('patch', $path, $payload);
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
            $data = $data->concat($this->{$methodName}(
                ...[
                    'perPage' => $perPage,
                    'page' => $currentPage,
                    ...$arguments,
                ]
            ));
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
}
