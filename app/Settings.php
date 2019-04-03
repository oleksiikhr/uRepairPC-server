<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
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

    /**
     * @return int
     */
    public static function getFrontendModified(): int
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
     * Get data for frontend section and cache forever.
     *
     * @return array
     */
    public static function getFrontendRecords(): array
    {
        return Cache::rememberForever(self::CACHE_KEY . '_' . self::SECTION_FRONTEND, function () {
            $list = self::select('name', 'value', 'type')
                ->where('name', 'LIKE', self::SECTION_FRONTEND . '_%')
                ->get();

            // Refresh timestamp for response header
            self::updateSectionModified(self::SECTION_FRONTEND);

            return self::mapWithKeysSubstrSection($list, self::SECTION_FRONTEND);
        });
    }

    /**
     * @param  array  $array
     * @return void
     */
    public static function updateFrontendRecords(array $array): void
    {
        $settings = Settings::getFrontendRecords();
        $clearCache = false;

        foreach ($array as $key => $value) {
            if ($value !== $settings[$key] && array_key_exists($key, $settings)) {
                DB::table('settings')
                    ->where('name', self::SECTION_FRONTEND . '_' . $key)
                    ->update([
                        'value' => $value,
                    ]);

                $clearCache = true;
            }
        }

        if ($clearCache) {
            Cache::forget(self::CACHE_KEY . '_' . self::SECTION_FRONTEND);
        }
    }

    /**
     * @param  string  $section
     * @return void
     */
    private static function updateSectionModified(string $section): void
    {
        $key = self::CACHE_KEY . '_' . $section . '_modified';

        Cache::forget($key);
        Cache::rememberForever($key, function () {
            return time();
        });
    }

    /**
     * @param  Collection  $list
     * @param  string  $section
     * @return array
     * @example [
     *  ['section_key1' => 'value1'],
     *  ['section_key2' => 'value2']
     * ]
     * ---> ['key1' => 'value1', 'key2' => 'value2']
     */
    private static function mapWithKeysSubstrSection(Collection $list, string $section): array
    {
        return $list->mapWithKeys(function ($item) use ($section) {
            $name = Str::after($item['name'], $section . '_');
            return [$name => self::normalizeValue($item['value'], $item['type'])];
        })->all();
    }

    /**
     * @param  {mixed}  $value
     * @param  {string}  $type
     * @return mixed
     */
    private static function normalizeValue($value, $type)
    {
        if (!$value) {
            return null;
        }

        switch ($type) {
            case 'file':
                return Storage::url($value);
            default:
                return $value;
        }
    }
}
