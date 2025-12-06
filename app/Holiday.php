<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = [
        'day_name', 'date', 'holiday_name', 'working_date'
    ];
}
