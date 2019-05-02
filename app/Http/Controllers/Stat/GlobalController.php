<?php

namespace App\Http\Controllers\Stat;

use App\Enums\Permissions;
use Illuminate\Http\Request;
use App\Http\Json\GlobalFile;
use App\Http\Helpers\FileHelper;
use App\Http\Requests\GlobalRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\GlobalJsonResource;

class GlobalController extends Controller
{
    /**
     * List of attributes
     */
    private const LIST_FILES = [
        'favicon', 'logo_auth', 'logo_header'
    ];

    /**
     * Add middleware depends on user permissions.
     *
     * @param  Request  $request
     * @return array
     */
    public function permissions(Request $request): array
    {
        return [
            'store' => Permissions::GLOBAL_SETTINGS,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $json = (new GlobalFile)->getData();

        return response()->json(new GlobalJsonResource($json));
    }

    /**
     * Update all resources in storage.
     *
     * @param  GlobalRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GlobalRequest $request)
    {
        $globalFile = new GlobalFile;
        $json = $globalFile->getData();
        $data = $request->validated();

        // Replace file to path in storage and delete old file.
        if ($globalFile->isFromFile) {
            foreach ($data as $key => &$value) {
                if (in_array($key, self::LIST_FILES)) {
                    if (array_key_exists($key, $json)) {
                        FileHelper::delete($json[$key], 'public');
                    }
                    if ($request->hasFile($key)) {
                        $fileHelper = new FileHelper($value);
                        $value = $fileHelper->store('global', 'public');
                    }
                }
            }
        }

        $outputData = array_merge($json, $data);
        $globalFile->saveData($outputData);

        return response()->json(new GlobalJsonResource($outputData));
    }
}
