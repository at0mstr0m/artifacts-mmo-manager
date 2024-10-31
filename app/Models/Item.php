<?php

declare(strict_types=1);

namespace App\Models;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string $code
 * @property int $level
 * @property string $type
 * @property string $subtype
 * @property string $description
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Effect> $effects
 * @property-read int|null $effects_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item query()
 *
 * @mixin \Eloquent
 */
class Item extends Model
{
    protected $fillable = [
        'name',
        'code',
        'level',
        'type',
        'subtype',
        'description',
    ];

    protected $casts = [
        'name' => 'string',
        'code' => 'string',
        'level' => 'integer',
        'type' => 'string',
        'subtype' => 'string',
        'description' => 'string',
    ];

    public function effects()
    {
        return $this->belongsToMany(Effect::class);
    }
}
