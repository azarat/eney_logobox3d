<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property bool $processed
 * @property int $category
 * @property string $model
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $model_id_2d
 * @property int $model_id_3d
 * @property Collection areas
 * @property ProductAreaPreset $preset
 **/
class Product extends Model
{

    protected $table = 'product';

    public function areas()
    {
        return $this->belongsToMany(Area::class, 'product_to_area');
    }

    public function hasArea(): bool
    {
        return $this->areas()->count() > 0;
    }

    public function preset()
    {
        return $this->belongsTo(ProductAreaPreset::class);
    }
}
