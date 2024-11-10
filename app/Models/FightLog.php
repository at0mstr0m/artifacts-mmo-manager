<?php

declare(strict_types=1);

namespace App\Models;

class FightLog extends Model
{
    protected $fillable = [
        'text',
    ];

    protected $casts = [
        'text' => 'string',
    ];

    public function fight()
    {
        return $this->belongsTo(Fight::class);
    }
}
