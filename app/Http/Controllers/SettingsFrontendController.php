<?php

namespace App\Http\Controllers;

use App\Settings;
use App\Enums\Permissions;
use Illuminate\Support\Str;
use App\Http\Helpers\FileHelper;
use App\Http\Requests\SettingsFrontendRequest;

class SettingsFrontendController extends Controller
{
    public function __construct()
    {
        $this->allowPermissions([
            'store' => Permissions::OTHER_GLOBAL_SETTINGS,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Settings::getFrontendRecords();

        return response()->json($list);
    }

    /**
     * Update all resources in storage.
     *
     * @param  SettingsFrontendRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SettingsFrontendRequest $request)
    {
        $settings = Settings::getFrontendRecords();
        $data = $request->all();

        // Replace file to path in storage and delete old file.
        foreach ($data as $key => &$value) {
            if (in_array($key, Settings::ATTR_FILES)) {
                FileHelper::delete(Str::after($settings[$key], 'storage/'), 'public');
                if ($request->hasFile($key)) {
                    $fileHelper = new FileHelper($value);
                    $value = $fileHelper->store('global', 'public');
                }
            }
        }

        Settings::updateFrontendRecords($data);

        return response()->json([
            'message' => __('app.settings.store'),
            'settings' => Settings::getFrontendRecords(),
            'modified' => Settings::getFrontendModified(),
        ]);
    }
}
