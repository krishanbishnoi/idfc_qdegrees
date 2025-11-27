<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BeatPlanSubParts extends Model
{
    protected $fillable =['branch_id','date','description','beat_id','user_id'];
    public function branch()
    {
        return $this->hasOne('App\Model\Branch', 'id','branch_id');
    }
}
