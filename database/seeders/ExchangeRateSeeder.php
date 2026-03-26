<?php

namespace Database\Seeders;

use App\Models\ExchangeRate;
use Illuminate\Database\Seeder;

class ExchangeRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ExchangeRate::create([
            'currency' => 'USD',
            'source' => 'oficial',
            'value' => 36.50,
            'last_update' => now(),
        ]);

        ExchangeRate::create([
            'currency' => 'USD',
            'source' => 'paralelo',
            'value' => 45.30,
            'last_update' => now(),
        ]);

        ExchangeRate::create([
            'currency' => 'EUR',
            'source' => 'oficial',
            'value' => 39.80,
            'last_update' => now(),
        ]);

        ExchangeRate::create([
            'currency' => 'EUR',
            'source' => 'paralelo',
            'value' => 49.50,
            'last_update' => now(),
        ]);
    }
}
