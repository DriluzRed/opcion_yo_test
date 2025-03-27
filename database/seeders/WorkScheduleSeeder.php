<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\WorkSchedule;

class WorkScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        $workingDays = json_encode(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']);

        foreach ($employees as $employee) {
            WorkSchedule::create([
                'employee_id' => $employee->id,
                'days' => $workingDays,
                'start_time' => '08:00:00',
                'end_time' => '15:00:00'
            ]);
        }
    }
}
