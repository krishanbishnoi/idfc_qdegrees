<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    protected $fillable=['name','branch_id','agency_id','agency_manager','location','addresss'];
    public function branch()
    {
        return $this->hasOne('App\Model\Branch', 'id','branch_id');
    }
    public function user()
    {
        return $this->hasOne('App\User', 'id','agency_manager');
    }
}
