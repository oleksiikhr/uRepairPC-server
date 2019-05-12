<?php

namespace App\Http\Controllers;

use App\EquipmentType;
use App\Enums\Permissions;
use Illuminate\Http\Request;
use App\Events\EquipmentTypes\ECreate;
use App\Events\EquipmentTypes\EDelete;
use App\Events\EquipmentTypes\EUpdate;
use App\Http\Requests\EquipmentTypeRequest;

class EquipmentTypeController extends Controller
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
            'index' => Permissions::EQUIPMENTS_CONFIG_VIEW,
            'show' => Permissions::EQUIPMENTS_CONFIG_VIEW,
            'store' => Permissions::EQUIPMENTS_CONFIG_CREATE,
            'update' => Permissions::EQUIPMENTS_CONFIG_EDIT,
            'destroy' => Permissions::EQUIPMENTS_CONFIG_DELETE,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $list = EquipmentType::all();

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EquipmentTypeRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EquipmentTypeRequest $request)
    {
        $equipmentType = new EquipmentType;
        $equipmentType->fill($request->all());

        if (! $equipmentType->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        event(new ECreate($equipmentType->toArray()));

        return response()->json([
            'message' => __('app.equipment_type.store'),
            'equipment_type' => $equipmentType,
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
        $equipmentType = EquipmentType::findOrFail($id);

        return response()->json([
            'message' => __('app.equipment_type.show'),
            'equipment_type' => $equipmentType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EquipmentTypeRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EquipmentTypeRequest $request, int $id)
    {
        $equipmentType = EquipmentType::findOrFail($id);
        $equipmentType->fill($request->all());

        if (! $equipmentType->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        event(new EUpdate($id, $equipmentType));

        return response()->json([
            'message' => __('app.equipment_type.update'),
            'equipment_type' => $equipmentType,
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
        if (! EquipmentType::destroy($id)) {
            return response()->json(['message' => __('app.database.destroy_error')], 422);
        }

        event(new EDelete($id));

        return response()->json([
            'message' => __('app.equipment_type.destroy'),
        ]);
    }
}
