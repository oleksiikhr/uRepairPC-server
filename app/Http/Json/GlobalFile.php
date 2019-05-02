<?php

namespace App\Http\Json;

class GlobalFile extends JsonFile
{
    private const FILE_NAME = 'settings.json';

    public function __construct()
    {
        parent::__construct(self::FILE_NAME);
    }

    /**
     * Get default data.
     *
     * @return array
     */
    public function getDefaultData()
    {
        return [
            'meta_title' => config('app.name'),
            'app_name' => null,
            'logo_auth' => null,
            'logo_header' => null,
            'favicon' => null,
            'name_and_logo' => null,
        ];
    }
}
