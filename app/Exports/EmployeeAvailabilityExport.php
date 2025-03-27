<?php

namespace App\Exports;

use App\Models\Employee;
use App\Models\AvailabilityBlock;
use App\Models\Reservation;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeAvailabilityExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Employee::with('availabilityBlocks', 'reservations')->get();
    }

    public function headings(): array
    {
        return ["Nombre y Apellido", "Horas Disponibles", "Horas Reservadas"];
    }

    public function map($employee): array
    {
        $totalAvailableHours = $employee->availabilityBlocks
            ->where('type', 'available')
            ->sum(function ($block) {
                return Carbon::parse($block->start_time)->diffInHours(Carbon::parse($block->end_time));
            });

        $totalReservedHours = $employee->reservations
            ->sum(function ($reservation) {
                return Carbon::parse($reservation->start_time)->diffInHours(Carbon::parse($reservation->end_time));
            });

        return [
            $employee->name,
            $totalAvailableHours . ' h',
            $totalReservedHours . ' h',
        ];
    }
    
}
