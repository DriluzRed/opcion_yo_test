<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'name' => 'Juan Pérez',
                'email' => 'juan@example.com',
                'country' => 'Argentina',
                'timezone' => 'America/Argentina/Buenos_Aires'
            ],
            [
                'name' => 'María López',
                'email' => 'maria@example.com',
                'country' => 'México',
                'timezone' => 'America/Mexico_City'
            ],
            [
                'name' => 'Carlos Gómez',
                'email' => 'carlos@example.com',
                'country' => 'España',
                'timezone' => 'Europe/Madrid'
            ]
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
