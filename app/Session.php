<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    public function printings()
    {
        return $this->hasMany('App\Printing');
    }

    public function products()
    {
        return $this->hasManyThrough(
            'App\Product',
            'App\Printing',
            'session_id',
            'id',
            'id',
            'product_id'
        );
    }
}
