<?php

namespace App\Traits;

trait ModelHasDefaultTrait
{
    /**
     * @return int
     */
    public static function clearDefaultValues() {
        return self::where('default', true)->update(['default' => false]);
    }
}
