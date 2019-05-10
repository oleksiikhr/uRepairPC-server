<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use Illuminate\Http\Request;
use App\EquipmentManufacturer;
use App\Events\EquipmentManufacturers\ECreate;
use App\Events\EquipmentManufacturers\EDelete;
use App\Events\EquipmentManufacturers\EUpdate;
use App\Http\Requests\EquipmentManufacturerRequest;

class EquipmentManufacturerController extends Controller
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
        $list = EquipmentManufacturer::all();

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EquipmentManufacturerRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EquipmentManufacturerRequest $request)
    {
        $equipmentManufacturer = new EquipmentManufacturer;
        $equipmentManufacturer->fill($request->all());

        if (! $equipmentManufacturer->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        event(new ECreate($equipmentManufacturer));

        return response()->json([
            'message' => __('app.equipment_manufacturers.store'),
            'equipment_manufacturer' => $equipmentManufacturer,
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
        $equipmentManufacturer = EquipmentManufacturer::findOrFail($id);

        return response()->json([
            'message' => __('app.equipment_manufacturers.show'),
            'equipment_manufacturer' => $equipmentManufacturer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EquipmentManufacturerRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EquipmentManufacturerRequest $request, int $id)
    {
        $equipmentManufacturer = EquipmentManufacturer::findOrFail($id);
        $equipmentManufacturer->fill($request->all());

        if (! $equipmentManufacturer->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        event(new EUpdate($id, $equipmentManufacturer));

        return response()->json([
            'message' => __('app.equipment_manufacturers.update'),
            'equipment_manufacturer' => $equipmentManufacturer,
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
        if (! EquipmentManufacturer::destroy($id)) {
            return response()->json(['message' => __('app.database.destroy_error')], 422);
        }

        event(new EDelete($id));

        return response()->json([
            'message' => __('app.equipment_manufacturers.destroy'),
        ]);
    }
}
