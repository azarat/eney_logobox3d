<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    public static function getBySiteId($siteId)
    {
        return self::where('key', $siteId)->firstOrFail();
    }
}
