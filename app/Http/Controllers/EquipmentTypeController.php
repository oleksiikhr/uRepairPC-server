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
        $list = EquipmentType::all();

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
        $type = new EquipmentType;
        $type->name = $request->name;
        $type->description = $request->description;

        if (! $type->save()) {
            return response()->json(['message' => 'Виникла помилка при збереженні'], 422);
        }

        return response()->json(['message' => 'Збережено', 'model' => $type]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $type = EquipmentType::findOrFail($id);

        return response()->json(['message' => 'Тип обладнання отриман', 'type' => $type]);
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
        $type = EquipmentType::findOrFail($id);
        $type->name = $request->has('name') ? $request->name : $type->name;
        $type->description = $request->has('description') ? $request->description : $type->description;

        if (! $type->save()) {
            return response()->json(['message' => 'Виникла помилка при збереженні'], 422);
        }

        return response()->json(['message' => 'Збережено', 'type' => $type]);
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
