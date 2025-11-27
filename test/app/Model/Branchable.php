<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Branchable extends Model
{
    protected $fillable = ['branch_id', 'product_id','manager_id','type','bucket','acm_id'];
    public function product()
    {
        return $this->hasOne('App\Model\Products', 'id','product_id');
    }
    public function Branch()
    {
        return $this->hasOne('App\Model\Branch', 'id','branch_id');
    }
    public function user()
    {
        return $this->hasOne('App\User', 'id','manager_id');
    }
}
