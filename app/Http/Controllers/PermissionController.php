<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use App\Http\Requests\PermissionRequest;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Add middleware depends on user permissions.
     *
     * @return array
     */
    public function permissions(): array
    {
        return [
            'index' => Permissions::GROUPS_VIEW,
        ];
    }

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
