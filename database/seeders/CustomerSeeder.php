<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            ['name' => 'Pedro Alvarado', 'email' => 'pedro.alvarado@example.com', 'country' => 'Paraguay', 'timezone' => 'America/Asuncion'],
            ['name' => 'Sofía Méndez', 'email' => 'sofia.mendez@example.com', 'country' => 'Uruguay', 'timezone' => 'America/Montevideo'],
            ['name' => 'Javier Torres', 'email' => 'javier.torres@example.com', 'country' => 'Perú', 'timezone' => 'America/Lima'],
            ['name' => 'Valentina Ríos', 'email' => 'valentina.rios@example.com', 'country' => 'Ecuador', 'timezone' => 'America/Guayaquil'],
            ['name' => 'Miguel Herrera', 'email' => 'miguel.herrera@example.com', 'country' => 'Bolivia', 'timezone' => 'America/La_Paz'],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
