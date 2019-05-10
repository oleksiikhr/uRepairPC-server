<?php

namespace App\Http\Controllers;

use App\EquipmentModel;
use App\Enums\Permissions;
use Illuminate\Http\Request;
use App\Events\EquipmentModels\ECreate;
use App\Events\EquipmentModels\EDelete;
use App\Events\EquipmentModels\EUpdate;
use App\Http\Requests\EquipmentModelRequest;

class EquipmentModelController extends Controller
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
        $list = EquipmentModel::querySelectJoins()->get();

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EquipmentModelRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EquipmentModelRequest $request)
    {
        $equipmentModel = new EquipmentModel;
        $equipmentModel->fill($request->all());

        if (! $equipmentModel->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        $equipmentModel = EquipmentModel::querySelectJoins()->find($equipmentModel->id);
        event(new ECreate($equipmentModel->toArray()));

        return response()->json([
            'message' => __('app.equipment_model.store.store'),
            'equipment_model' => $equipmentModel,
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
        $equipmentModel = EquipmentModel::querySelectJoins()->findOrFail($id);

        return response()->json([
            'message' => __('app.equipment_model.show'),
            'equipment_model' => $equipmentModel,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EquipmentModelRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EquipmentModelRequest $request, int $id)
    {
        $equipmentModel = EquipmentModel::findOrFail($id);
        $equipmentModel->fill($request->all());

        if (! $equipmentModel->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        $equipmentModel = EquipmentModel::querySelectJoins()->find($equipmentModel->id);
        event(new EUpdate($id, $equipmentModel->toArray()));

        return response()->json([
            'message' => __('app.equipment_model.update'),
            'equipment_model' => $equipmentModel,
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
        if (! EquipmentModel::destroy($id)) {
            return response()->json(['message' => __('app.database.destroy_error')], 422);
        }

        event(new EDelete($id));

        return response()->json([
            'message' => __('app.equipment_model.destroy'),
        ]);
    }
}
