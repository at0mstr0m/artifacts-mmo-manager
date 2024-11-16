<?php

declare(strict_types=1);

namespace App\Models;

class Occupaion extends Model
{
    protected $fillable = ['config'];

    protected $casts = [
        'config' => 'array',
    ];

    public function characters()
    {
        return $this->hasMany(Character::class);
    }
}
