<?php

namespace App\Http\Controllers;

use App\Settings;
use Illuminate\Http\Request;

class SettingsFrontendController extends Controller
{
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
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Settings::updateFrontendRecords($request->all());

        return response()->json([
            'message' => __('app.settings.store'),
            'settings' => Settings::getFrontendRecords(),
        ]);
    }
}
