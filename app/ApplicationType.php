<?php

namespace App;

/**
 * @phpdoc{inherit}
 * @property int $id
 * @property bool $status
 * @property string $code
 * @property string $time
 * @property string $image
 **/
final class ApplicationType extends EntityWithTranslations
{
    protected $table = 'application_type';
    protected $translationForeignKey = 'application_type_id';

    public function areas(){
        return $this->hasMany(Area::class);
    }
}
