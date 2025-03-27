<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    protected $fillable = ['employee_id', 'days', 'start_time', 'end_time'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function availabilityBlocks()
    {
        return $this->hasMany(AvailabilityBlock::class);
    }
}
