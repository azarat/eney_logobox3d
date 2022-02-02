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
 **/
final class Type extends EntityWithTranslations
{
    protected $table = 'type';
    protected $translationForeignKey = 'type_id';

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_to_type');
    }

    public function applicationTypes()
    {
        return $this->belongsTo(ApplicationType::class, 'application_type_id', 'id');
    }
}
