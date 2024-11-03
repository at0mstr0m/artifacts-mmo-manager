<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string $skin
 * @property int $x
 * @property int $y
 * @property string|null $content_type
 * @property string|null $content_code
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map query()
 *
 * @mixin \Eloquent
 */
class Map extends Model
{
    protected $fillable = [
        'name',
        'skin',
        'x',
        'y',
        'content_type',
        'content_code',
    ];

    protected $casts = [
        'name' => 'string',
        'skin' => 'string',
        'x' => 'integer',
        'y' => 'integer',
        'content_type' => 'string',
        'content_code' => 'string',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
