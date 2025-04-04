<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Carbon\Carbon;


class AvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'end_time' => 'required|date_format:Y-m-d H:i:s|after:start_time',
        ]);
    
        $startNY = Carbon::parse($request->start_time, 'America/New_York');
        $endNY = Carbon::parse($request->end_time, 'America/New_York');
    
        $availableEmployees = Employee::whereHas('workSchedules', function ($query) use ($startNY, $endNY) {
            $query->whereJsonContains('days', $startNY->format('l'))
                ->whereTime('start_time', '<=', $startNY->format('H:i:s'))
                ->whereTime('end_time', '>=', $endNY->format('H:i:s'));
        })
        ->with(['workSchedules', 'reservations', 'availabilityBlocks'])
        ->get()
        ->map(function ($employee) use ($startNY, $endNY) {
            $employee->reservations = $employee->reservations->filter(function ($reservation) use ($startNY, $endNY) {
                return $reservation->reservation_date == $startNY->toDateString() &&
                    $reservation->start_time < $endNY->format('H:i:s') &&
                    $reservation->end_time > $startNY->format('H:i:s');
            });
    
            $employee->availabilityBlocks = $employee->availabilityBlocks->filter(function ($block) use ($startNY, $endNY) {
                return $block->date == $startNY->toDateString() &&
                    $block->start_time < $endNY->format('H:i:s') &&
                    $block->end_time > $startNY->format('H:i:s') &&
                    $block->type === 'break';
            });
    
            return $employee;
        });
    
        return response()->json([
            'date' => $startNY->toDateString(),
            'start_time' => $startNY->format('H:i:s'),
            'end_time' => $endNY->format('H:i:s'),
            'available_employees' => $availableEmployees,
        ]);
    }

    public function getAvailableEmployees(Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i:s',
        ]);
    
        $requestedTimeNY = Carbon::parse($request->date . ' ' . $request->time, 'America/New_York');
    
        $availableEmployees = Employee::whereHas('workSchedules', function ($query) use ($requestedTimeNY) {
            $query->whereJsonContains('days', $requestedTimeNY->format('l'))
                ->whereTime('start_time', '<=', $requestedTimeNY->format('H:i:s'))
                ->whereTime('end_time', '>=', $requestedTimeNY->format('H:i:s'));
        })
        ->whereDoesntHave('reservations', function ($query) use ($requestedTimeNY) {
            $query->whereDate('reservation_date', $requestedTimeNY->toDateString())
                ->whereTime('start_time', '<=', $requestedTimeNY->format('H:i:s'))
                ->whereTime('end_time', '>', $requestedTimeNY->format('H:i:s'));
        })
        ->whereDoesntHave('availabilityBlocks', function ($query) use ($requestedTimeNY) {
            $query->whereDate('date', $requestedTimeNY->toDateString())
                ->whereTime('start_time', '<=', $requestedTimeNY->format('H:i:s'))
                ->whereTime('end_time', '>', $requestedTimeNY->format('H:i:s'))
                ->where('type', 'break');
        })
        ->with('workSchedules')
        ->get();
    
        return response()->json([
            'date' => $requestedTimeNY->toDateString(),
            'time' => $requestedTimeNY->format('H:i:s'),
            'available_employees' => $availableEmployees,
        ]);
    }
}
