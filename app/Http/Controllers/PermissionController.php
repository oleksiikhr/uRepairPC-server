<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  PermissionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(PermissionRequest $request)
    {
        $list = Permission::all();

        if ($request->group) {
            return response()->json($list->groupBy('section_name'));
        }

        return response()->json($list);
    }
}
