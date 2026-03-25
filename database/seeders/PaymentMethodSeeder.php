<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = ['Efectivo', 'Transferencia', 'Tarjeta', 'Pago Móvil'];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(['name' => $method]);
        }
    }
}
