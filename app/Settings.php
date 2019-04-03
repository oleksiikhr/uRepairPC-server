<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Settings extends Model
{
    /**
     * Always send data to website (frontend).
     * Global settings: logo, name, etc.
     *
     * @var string
     */
    const SECTION_FRONTEND = 'frontend';

    /**
     * @var string
     */
    const CACHE_KEY = 'settings';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'settings';

    public static function getFrontendModified()
    {
        return self::getSectionModified(self::SECTION_FRONTEND);
    }

    /**
     * @param  string  $section
     * @return int
     */
    public static function getSectionModified(string $section): int
    {
        return Cache::get(self::CACHE_KEY . '_' . $section . '_modified', -1);
    }

    /**
     * @param  string  $section
     * @return void
     */
    public static function updateSectionModified(string $section): void
    {
        $key = self::CACHE_KEY . '_' . $section . '_modified';

        Cache::forget($key);
        Cache::rememberForever($key, function () {
            return time();
        });
    }

    /**
     * Get data for frontend section and cache forever.
     *
     * @return mixed
     */
    public static function getFrontendRecords()
    {
        return Cache::rememberForever(self::CACHE_KEY . '_' . self::SECTION_FRONTEND, function () {
            $list = self::select('name', 'value')
                ->where('name', 'LIKE', self::SECTION_FRONTEND . '_%')
                ->get();

            // Refresh timestamp for response header
            self::updateSectionModified(self::SECTION_FRONTEND);

            return self::mapWithKeysSection($list, self::SECTION_FRONTEND);
        });
    }

    /**
     * @param  Collection  $list
     * @param  string  $section
     * @return mixed
     * @example [
     *  ['section_key1' => 'value1'],
     *  ['section_key2' => 'value2']
     * ]
     * Will be
     * ['key1' => 'value1', 'key2' => 'value2']
     */
    private static function mapWithKeysSection(Collection  $list, string $section)
    {
        return $list->mapWithKeys(function ($item) use ($section) {
            $name = Str::after($item['name'], $section . '_');
            return [$name => $item['value']];
        })->all();
    }
}
