<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
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
            'store' => Permissions::GROUPS_MANAGE,
            'show' => Permissions::GROUPS_MANAGE,
            'update' => Permissions::GROUPS_MANAGE,
            'destroy' => Permissions::GROUPS_MANAGE,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  RoleRequest  $request
     * @return \Illuminate\Http\Response
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
