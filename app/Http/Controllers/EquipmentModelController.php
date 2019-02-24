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
            User::ROLE_MODERATOR => [
                'index', 'store', 'show', 'update', 'destroy',
            ],
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
        $list = $this->getSelectModel()->paginate(self::PAGINATE_DEFAULT);

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
        $model = new EquipmentModel;
        $model->name = $request->name;
        $model->description = $request->description;
        $model->type_id = $request->type_id;
        $model->manufacturer_id = $request->manufacturer_id;

        if (! $model->save()) {
            return response()->json(['message' => 'Виникла помилка при збереженні'], 422);
        }

        return response()->json([
            'message' => 'Збережено',
            'model' => $this->getSelectModel()->find($model->id),
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
        $model = $this->getSelectModel()->findOrFail($id);

        return response()->json(['message' => 'Модель обладнання отриман', 'model' => $model]);
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
        $model = EquipmentModel::findOrFail($id);
        $model->name = $request->has('name') ? $request->name : $model->name;
        $model->description = $request->has('description') ? $request->description : $model->description;
        $model->type_id = $request->has('type_id') ? $request->type_id : $model->type_id;
        $model->manufacturer_id = $request->has('manufacturer_id')
            ? $request->manufacturer_id
            : $model->manufacturer_id;

        if (! $model->save()) {
            return response()->json(['message' => 'Виникла помилка при збереженні'], 422);
        }

        return response()->json([
            'message' => 'Збережено',
            'model' => $this->getSelectModel()->find($model->id),
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
        if (EquipmentModel::destroy($id)) {
            return response()->json(['message' => 'Модель обладнання видалений']);
        }

        return response()->json(['message' => 'Виникла помилка при видаленні'], 422);
    }

    /**
     * @return mixed
     */
    private function getSelectModel()
    {
        return EquipmentModel::select(
            'equipment_models.*',
            'equipment_types.name as equipment_name',
            'equipment_manufacturers.name as manufacturer_name'
        )
            ->join('equipment_types', 'equipment_models.type_id', '=', 'equipment_types.id')
            ->join('equipment_manufacturers', 'equipment_models.manufacturer_id', '=', 'equipment_manufacturers.id');
    }
}
