<?php

namespace App\Models;

use Database\Factories\ExchangeRateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    /** @use HasFactory<ExchangeRateFactory> */
    use HasFactory;

    protected $fillable = [
        'currency',
        'source',
        'value',
        'last_update',
    ];

    protected $casts = [
        'value' => 'decimal:4',
        'last_update' => 'datetime',
    ];
}
