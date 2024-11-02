<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Models\Map;

class MapData extends Data
{
    public ?string $contentType = null;

    public ?string $contentCode = null;

    private Map $model;

    public function __construct(
        public string $name,
        public string $skin,
        public int $x,
        public int $y,
        ?array $content,
    ) {
        $this->contentType = data_get($content, 'type');
        $this->contentCode = data_get($content, 'code');
        $this->createIfNotExists();
    }

    public function getModel(): Map
    {
        return $this->model;
    }

    private function createIfNotExists(): void
    {
        $this->model = Map::firstOrCreate([
            'x' => $this->x,
            'y' => $this->y,
        ], [
            'name' => $this->name,
            'skin' => $this->skin,
            'content_type' => $this->contentType,
            'content_code' => $this->contentCode,
        ]);
    }
}
