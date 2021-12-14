<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @phpdoc{inherit}
 * @property int $id
 * @property Collection $areas
 * @property Collection $products
 * @property string $name
 **/
final class ProductAreaPreset extends Model
{
    public $timestamps = false;

    public function Areas()
    {
        return $this->belongsToMany(Area::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}