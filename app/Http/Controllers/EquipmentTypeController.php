<?php

namespace App\Http\Controllers;

use App\User;
use App\EquipmentType;
use App\Http\Requests\EquipmentTypeRequest;

class EquipmentTypeController extends Controller
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
        $list = EquipmentType::paginate(self::PAGINATE_DEFAULT);

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EquipmentTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EquipmentTypeRequest $request)
    {
        $model = new EquipmentType;
        $model->name = $request->name;
        $model->description = $request->description;

        if (! $model->save()) {
            return response()->json(['message' => 'Виникла помилка при збереженні'], 422);
        }

        return response()->json(['message' => 'Збережено', 'model' => $model]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = EquipmentType::findOrFail($id);

        return response()->json(['message' => 'Тип обладнання отриман', 'model' => $model]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EquipmentTypeRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EquipmentTypeRequest $request, $id)
    {
        $model = EquipmentType::findOrFail($id);
        $model->name = $request->has('name') ? $request->name : $model->name;
        $model->description = $request->has('description') ? $request->description : $model->description;

        if (! $model->save()) {
            return response()->json(['message' => 'Виникла помилка при збереженні'], 422);
        }

        return response()->json(['message' => 'Збережено', 'model' => $model]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (EquipmentType::destroy($id)) {
            return response()->json(['message' => 'Тип обладнання видалений']);
        }

        return response()->json(['message' => 'Виникла помилка при видаленні'], 422);
    }
}
