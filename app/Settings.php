<?php

namespace App;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Settings extends Model
{
    /**
     * Always send data to website (frontend).
     * Global settings.
     */
    const SECTION_FRONTEND = 'frontend';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * @return mixed
     */
    public static function getFrontendRecords()
    {
        return Cache::rememberForever('settings_' . self::SECTION_FRONTEND, function () {
            $list = self::select('name', 'value')
                ->where('name', 'LIKE', self::SECTION_FRONTEND . '_%')
                ->get();

            /**
             * @example [
             *  ['section_key1' => 'value1'],
             *  ['section_key2' => 'value2']
             * ]
             * Will be
             * ['key1' => 'value1', 'key2' => 'value2']
             */
            return collect($list)->mapWithKeys(function ($item) {
                $name = Str::after($item['name'], self::SECTION_FRONTEND . '_');
                return [$name => $item['value']];
            })
                ->all();
        });
    }
}
