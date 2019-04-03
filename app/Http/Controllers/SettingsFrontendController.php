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
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $settings = new Settings;

        if (! $settings->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        return response()->json([
//            'message' => __(''),
        ]);
    }
}
