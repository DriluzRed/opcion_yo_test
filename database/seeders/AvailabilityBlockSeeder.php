<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AvailabilityBlock;
use App\Models\Employee;
use App\Models\WorkSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AvailabilityBlockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->addMonths(2)->endOfMonth();

        foreach ($employees as $employee) {
            $workSchedule = WorkSchedule::where('employee_id', $employee->id)->first();

            if (!$workSchedule) {
                Log::warning("No work schedule found for employee {$employee->id}");
                continue;
            }

            $days = json_decode($workSchedule->days, true);
            $workStart = Carbon::parse($workSchedule->start_time);
            $workEnd = Carbon::parse($workSchedule->end_time);
            $lunchStart = Carbon::parse('12:00:00');
            $lunchEnd = $lunchStart->copy()->addHour();

            $date = clone $startDate;

            while ($date->lte($endDate)) {
                if (in_array($date->format('l'), $days)) {
                    $timeSlot = clone $workStart;

                    while ($timeSlot < $workEnd) {
                        $endSlot = (clone $timeSlot)->addHour();

                        if ($timeSlot->gte($lunchStart) && $timeSlot->lt($lunchEnd)) {
                            $timeSlot->addHour();
                            continue;
                        }

                        AvailabilityBlock::create([
                            'employee_id' => $employee->id,
                            'date' => $date->toDateString(),
                            'start_time' => $timeSlot->toTimeString(),
                            'end_time' => $endSlot->toTimeString(),
                            'type' => 'available',
                        ]);

                        Log::info("Created availability block for employee {$employee->id} on {$date->toDateString()} from {$timeSlot->toTimeString()} to {$endSlot->toTimeString()}");

                        $timeSlot->addHour();
                    }
                }

                $date->addDay();
            }
        }
    }
}
