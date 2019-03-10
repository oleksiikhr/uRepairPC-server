<?php

namespace App\Http\Controllers;

use App\User;
use App\Equipment;
use App\Http\Requests\EquipmentRequest;

class EquipmentController extends Controller
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
        $list = $this->getSelectModel()->paginate(self::PAGINATE_DEFAULT);

//        TODO order, sort, count

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EquipmentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EquipmentRequest $request)
    {
        $model = new Equipment;
        $model->serial_number = $request->serial_number;
        $model->inventory_number = $request->inventory_number;
        $model->type_id = $request->type_id;
        $model->manufacturer_id = $request->manufacturer_id;
        $model->model_id = $request->model_id;

        if (! $model->save()) {
            return response()->json(['message' => 'Виникла помилка при збереженні'], 409);
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

        return response()->json(['message' => 'Обладнання отримано', 'model' => $model]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EquipmentRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EquipmentRequest $request, $id)
    {
        $model = Equipment::findOrFail($id);
        $model->serial_number = $request->has('serial_number') ? $request->serial_number : $model->serial_number;
        $model->inventory_number = $request->has('inventory_number') ? $request->inventory_number : $model->inventory_number;
        $model->manufacturer_id = $request->has('manufacturer_id') ? $request->manufacturer_id : $model->manufacturer_id;
        $model->type_id = $request->has('type_id') ? $request->type_id : $model->type_id;
        $model->model_id = $request->has('model_id') ? $request->model_id : $model->model_id;

        if (! $model->save()) {
            return response()->json(['message' => 'Виникла помилка при збереженні'], 409);
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
        if (Equipment::destroy($id)) {
            return response()->json(['message' => 'Обладнання видалено']);
        }

        return response()->json(['message' => 'Виникла помилка при видаленні'], 409);
    }

    /**
     * @return mixed
     */
    private function getSelectModel()
    {
        return Equipment::select(
            'equipments.*',
            'equipment_types.name as equipment_name',
            'equipment_manufacturers.name as manufacturer_name',
            'equipment_models.name as model_name'
        )
            ->join('equipment_types', 'equipments.type_id', '=', 'equipment_types.id')
            ->join('equipment_manufacturers', 'equipments.manufacturer_id', '=', 'equipment_manufacturers.id')
            ->join('equipment_models', 'equipments.model_id', '=', 'equipment_models.id');
    }
}
