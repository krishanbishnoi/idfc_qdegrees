<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['name', 'manager_id', 'owner_id', 'city_id', 'location', 'uuid','lob'];

    //

    public function city()
    {
        return $this->hasOne('App\Model\City', 'id','city_id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id','manager_id');
    }
    public function branchable()
    {
        return $this->hasMany('App\Model\Branchable', 'branch_id','id')->with('user','product.productUser');
    }
}
