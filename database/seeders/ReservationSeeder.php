<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\AvailabilityBlock;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        $customers = Customer::all();
        foreach ($employees as $employee) {
            $availabilityBlocks = AvailabilityBlock::where('employee_id', $employee->id)
                ->where('type', 'available')
                ->where('date', '>=', Carbon::now()->startOfMonth()->toDateString())
                ->where('date', '<=', Carbon::now()->addMonths(2)->endOfMonth()->toDateString())
                ->get();
            if ($availabilityBlocks->isEmpty()) {
                Log::info('No availability blocks found for employee ' . $employee->id);
                continue;
            }
            $reservationsCount = 0;

            foreach ($availabilityBlocks as $block) {
                if ($reservationsCount >= 8) {
                    break;
                }

                $customer = $customers->random();
                if ($this->isAvailableForReservation($employee, $block)) {
                    $this->createReservation($employee, $customer, $block);
                    $reservationsCount++;
                }
            }
        }
    }

    /**
     * Verifica si el bloque de disponibilidad está dentro del rango de fechas de la reserva.
     */
    private function isAvailableForReservation($employee, $block)
    {
        Log::info('Checking availability for employee ' . $employee->id . ' on ' . $block->date);
        return !Reservation::where('employee_id', $employee->id)
            ->where('reservation_date', $block->date)
            ->where(function ($query) use ($block) {
                $query->where('start_time', '<', $block->end_time)
                    ->where('end_time', '>', $block->start_time);
            })
            ->exists();
    }

    /**
     * Crea una nueva reserva para el cliente y el empleado.
     */
    private function createReservation($employee, $customer, $block)
    {
        Log::info('Creating reservation for employee ' . $employee->id . ' on ' . $block->date);
        Reservation::create([
            'employee_id' => $employee->id,
            'customer_id' => $customer->id,
            'start_time' => $block->start_time,
            'end_time' => $block->end_time,
            'status' => 'pending',
            'reservation_date' => $block->date,
        ]);
    }
}
