<?php

namespace App\Http\Controllers;

use App\Equipment;
use App\Enums\Permissions;
use Illuminate\Http\Request;
use App\Http\Helpers\FilesHelper;
use App\Events\Equipments\EDelete;
use App\Events\Equipments\EUpdate;
use App\Http\Requests\EquipmentRequest;

class EquipmentController extends Controller
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
            'index' => Permissions::EQUIPMENTS_VIEW,
            'show' => Permissions::EQUIPMENTS_VIEW,
            'store' => Permissions::EQUIPMENTS_CREATE,
            'update' => Permissions::EQUIPMENTS_EDIT,
            'destroy' => Permissions::EQUIPMENTS_DELETE,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param  EquipmentRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(EquipmentRequest $request)
    {
        $query = Equipment::querySelectJoins();

        // Search
        if ($request->has('search') && $request->has('columns') && ! empty($request->columns)) {
            foreach ($request->columns as $column) {
                $query->orWhere(Equipment::SEARCH_RELATIONSHIP[$column] ?? $column, 'LIKE', '%' . $request->search . '%');
            }
        }

        // Order
        if ($request->has('sortColumn')) {
            $query->orderBy($request->sortColumn, $request->sortOrder === 'descending' ? 'desc' : 'asc');
        }

        $list = $query->paginate(self::PAGINATE_DEFAULT);

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EquipmentRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EquipmentRequest $request)
    {
        $equipment = new Equipment;
        $equipment->fill($request->all());

        if (! $equipment->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        return response()->json([
            'message' => __('app.equipments.store'),
            'equipment' => Equipment::querySelectJoins()->find($equipment->id),
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
        $equipment = Equipment::querySelectJoins()->findOrFail($id);

        return response()->json([
            'message' => __('app.equipments.show'),
            'equipment' => $equipment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EquipmentRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EquipmentRequest $request, int $id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->fill($request->all());

        if (! $equipment->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        $equipment = Equipment::querySelectJoins()->find($equipment->id);
        event(new EUpdate($id, $equipment->toArray()));

        return response()->json([
            'message' => __('app.equipments.update'),
            'equipment' => $equipment,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, int $id)
    {
        $request->validate([
            'files_delete' => 'boolean',
        ]);

        $equipment = Equipment::findOrFail($id);

        if ($request->files_delete) {
            $isSuccess = FilesHelper::delete($equipment->files);

            if (! $isSuccess) {
                return response()->json(['message' => __('app.files.files_not_deleted')]);
            }
        }

        if (! $equipment->delete()) {
            return response()->json(['message' => __('app.database.destroy_error')], 422);
        }

        event(new EDelete($id));

        return response()->json([
            'message' => __('app.equipments.destroy'),
        ]);
    }
}
