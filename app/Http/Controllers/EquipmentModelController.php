<?php

namespace App\Http\Controllers;

use App\User;
use App\EquipmentModel;
use App\Http\Requests\EquipmentModelRequest;

class EquipmentModelController extends Controller
{
    public function __construct()
    {
        $this->allowRoles([
            User::ROLE_WORKER => [
                'index', 'store', 'show', 'update', 'destroy',
            ],
            User::ROLE_USER => [
                'index', 'show',
            ],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function store(EquipmentModelRequest $request)
    {
        $equipmentModel = new EquipmentModel;
        $equipmentModel->fill($request->all());

        if (! $equipmentModel->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        return response()->json([
            'message' => __('app.equipment_model.store.store'),
            'equipment_model' => EquipmentModel::querySelectJoins()->find($equipmentModel->id),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
     * @return \Illuminate\Http\Response
     */
    public function update(EquipmentModelRequest $request, $id)
    {
        $equipmentModel = EquipmentModel::findOrFail($id);
        $equipmentModel->fill($request->all());

        if (! $equipmentModel->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        return response()->json([
            'message' => __('app.equipment_model.update'),
            'equipment_model' => EquipmentModel::querySelectJoins()->find($equipmentModel->id),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! EquipmentModel::destroy($id)) {
            return response()->json(['message' => __('app.database.destroy_error')], 422);
        }

        return response()->json([
            'message' => __('app.equipment_model.destroy'),
        ]);
    }
}
