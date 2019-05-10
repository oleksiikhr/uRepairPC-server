<?php

namespace App\Http\Controllers;

use App\Role;
use App\Enums\Permissions;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    /**
     * Add middleware depends on user permissions.
     *
     * @param  Request  $request
     * @return array
     */
    public function permissions(Request $request): array
    {
        $requestId = (int)$request->role;

        return [
            'index' => Permissions::ROLES_VIEW,
            'store' => Permissions::ROLES_MANAGE,
            'show' => Permissions::ROLES_VIEW,
            'update' => Permissions::ROLES_MANAGE,
            'destroy' => $requestId === 1 ? Permissions::DISABLE : Permissions::ROLES_MANAGE,
            'updatePermissions' => $requestId === 1 ? Permissions::DISABLE : Permissions::ROLES_MANAGE,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  RoleRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(RoleRequest $request)
    {
        $query = Role::query();

        if ($request->permissions) {
            $query->with('permissions');
        }

        // Search
        if ($request->has('search') && $request->has('columns') && ! empty($request->columns)) {
            foreach ($request->columns as $column) {
                $query->orWhere($column, 'LIKE', '%' . $request->search . '%');
            }
        }

        // Order
        if ($request->has('sortColumn')) {
            $query->orderBy($request->sortColumn, $request->sortOrder === 'descending' ? 'desc' : 'asc');
        }

        $list = $query->paginate($request->count ?? self::PAGINATE_DEFAULT);

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RoleRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RoleRequest $request)
    {
        $role = new Role;
        $role->fill($request->all());

        if (! $role->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        return response()->json([
            'message' => __('app.roles.store'),
            'role' => $role,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        return response()->json([
            'message' => __('app.roles.show'),
            'role' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RoleRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(RoleRequest $request, int $id)
    {
        $role = Role::findOrFail($id);
        $role->fill($request->all());

        if (! $role->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

//        event(new RoleEvent($id, $role, Permissions::REQUESTS_VIEW));

        return response()->json([
            'message' => __('app.roles.update'),
            'role' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePermissions(Request $request, int $id)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'string'
        ]);

        $role = Role::findOrFail($id);
        $role->syncPermissions($request->permissions);

//        event(new RoleEvent($id, $role, Permissions::REQUESTS_VIEW));

        return response()->json([
            'message' => __('app.roles.update_permissions'),
            'role' => $role,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $role = Role::findOrFail($id);

        if (! $role->delete()) {
            return response()->json(['message' => __('app.database.destroy_error')], 422);
        }

//        event(new RoleEvent($id, null, Permissions::REQUESTS_VIEW, RoleEvent::ACTION_DELETE));

        return response()->json([
            'message' => __('app.roles.destroy'),
        ]);
    }
}
