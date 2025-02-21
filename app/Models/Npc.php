<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\NpcTypes;
use App\Traits\IdentifiableByCode;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string $code
 * @property string $description
 * @property NpcTypes $type
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Npc newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Npc newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Npc query()
 *
 * @mixin \Eloquent
 */
class Npc extends Model
{
    use IdentifiableByCode;

    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
    ];

    protected $casts = [
        'name' => 'string',
        'code' => 'string',
        'description' => 'string',
        'type' => NpcTypes::class,
    ];
}
