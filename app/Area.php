<?php

namespace App;

use Carbon\Carbon;

/**
 * @phpdoc{inherit}
 * @property int $id
 * @property bool $status
 * @property int $application_type_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $code
 * @property double $prepare_price
 * @property double $print_price
 * @property double $sticking_price
 * @property double $roasting_price
 * @property double $kx
 * @property double $kz
 * @property int $max_colors
 * @property int $max_copy
 * @property string $image
 **/
final class Area extends EntityWithTranslations
{
    protected $table = 'area';
    protected $translationForeignKey = 'area_id';

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_to_area');
    }

    public function applicationTypes()
    {
        return $this->belongsTo(ApplicationType::class, 'application_type_id', 'id');
    }
}
