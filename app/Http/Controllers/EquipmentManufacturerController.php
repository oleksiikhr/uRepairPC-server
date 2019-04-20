<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use App\EquipmentManufacturer;
use Illuminate\Http\Request;
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
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function store(EquipmentManufacturerRequest $request)
    {
        $equipmentManufacturer = new EquipmentManufacturer;
        $equipmentManufacturer->fill($request->all());

        if (! $equipmentManufacturer->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        return response()->json([
            'message' => __('app.equipment_manufacturers.store'),
            'equipment_manufacturer' => $equipmentManufacturer,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function update(EquipmentManufacturerRequest $request, int $id)
    {
        $equipmentManufacturer = EquipmentManufacturer::findOrFail($id);
        $equipmentManufacturer->fill($request->all());

        if (! $equipmentManufacturer->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        return response()->json([
            'message' => __('app.equipment_manufacturers.update'),
            'equipment_manufacturer' => $equipmentManufacturer,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if (! EquipmentManufacturer::destroy($id)) {
            return response()->json(['message' => __('app.database.destroy_error')], 422);
        }

        return response()->json([
            'message' => __('app.equipment_manufacturers.destroy'),
        ]);
    }
}
