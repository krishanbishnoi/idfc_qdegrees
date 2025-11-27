<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = ['name','type','bucket','is_recovery','capacity'];
    //
    public function productUser()
    {
        return $this->hasMany('App\Model\ProductUser','product_id', 'id')->with('user');
    }
}
