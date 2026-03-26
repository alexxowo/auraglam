<?php

namespace App\Console\Commands;

use App\Models\ExchangeRate;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

#[Signature('app:fetch-exchange-rates')]
#[Description('Fetch exchange rates from DolarAPI and apply 2% margin.')]
class FetchExchangeRates extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = 'https://ve.dolarapi.com/v1/euros/oficial';
        $this->info("Fetching EUR Oficial from {$url}...");

        try {
            $response = Http::get($url);

            if ($response->successful()) {
                $data = $response->json();
                $promedio = $data['promedio'] ?? null;

                if (! $promedio) {
                    $this->error('Promedio value not found in API response.');

                    return 1;
                }

                $valueWithMargin = $promedio * 1; // Deshabilitado de momento
                // $valueWithMargin = $promedio * 1.02; // 2% extra

                // Parse date from API or use now()
                $lastUpdate = isset($data['fechaActualizacion'])
                    ? Carbon::parse($data['fechaActualizacion'])
                    : now();

                ExchangeRate::updateOrCreate(
                    [
                        'currency' => 'EUR',
                        'source' => 'oficial',
                    ],
                    [
                        'value' => $valueWithMargin,
                        'last_update' => $lastUpdate,
                    ]
                );

                $this->info('EUR Oficial updated: '.number_format($valueWithMargin, 4).' (Original: '.number_format($promedio, 4).')');

                return 0;
            }

            $this->error('API request failed with status: '.$response->status());

            return 1;

        } catch (\Exception $e) {
            $this->error('An error occurred: '.$e->getMessage());

            return 1;
        }
    }
}
