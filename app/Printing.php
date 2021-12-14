<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Printing extends Model
{
    public function area()
    {
        return $this->belongsTo('App\Area');
    }

    public function applicationType()
    {
        return $this->belongsTo('App\ApplicationType');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
