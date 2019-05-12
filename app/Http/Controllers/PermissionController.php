<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Enums\Permissions;
use Illuminate\Http\Request;
use App\Http\Requests\PermissionRequest;

class PermissionController extends Controller
{
    /**
     * Add middleware depends on user permissions.
     *
     * @param  Request  $request
     * @return array
     */
    public function permissions(Request $request): array
    {
        return [
            'index' => Permissions::ROLES_VIEW,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  PermissionRequest  $request
     * @return \Illuminate\Http\JsonResponse
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
