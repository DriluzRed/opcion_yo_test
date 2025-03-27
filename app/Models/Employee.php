<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'position', 'status'];

    public function workSchedules()
    {
        return $this->hasMany(WorkSchedule::class);
    }

    public function availabilityBlocks()
    {
        return $this->hasMany(AvailabilityBlock::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    
}
