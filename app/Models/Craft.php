<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Skills;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Craft extends Model
{
    protected $fillable = [
        'skill',
        'level',
        'quantity',
    ];

    protected $casts = [
        'skill' => Skills::class,
        'level' => 'integer',
        'quantity' => 'integer',
    ];

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class)->withPivot(['quantity']);
    }
}
