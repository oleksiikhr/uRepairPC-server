<?php

namespace App\Http\Controllers;

use App\User;
use App\EquipmentManufacturer;
use App\Http\Requests\EquipmentManufacturerRequest;

class EquipmentManufacturerController extends Controller
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
        $manufacturer = new EquipmentManufacturer;
        $manufacturer->name = $request->name;
        $manufacturer->description = $request->description;

        if (! $manufacturer->save()) {
            return response()->json(['message' => 'Виникла помилка при збереженні'], 422);
        }

        return response()->json(['message' => 'Збережено', 'model' => $manufacturer]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $manufacturer = EquipmentManufacturer::findOrFail($id);

        return response()->json([
            'message' => 'Виробник обладнання отриман',
            'manufacturer' => $manufacturer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EquipmentManufacturerRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EquipmentManufacturerRequest $request, $id)
    {
        $manufacturer = EquipmentManufacturer::findOrFail($id);
        $manufacturer->name = $request->has('name') ? $request->name : $manufacturer->name;
        $manufacturer->description = $request->has('description') ? $request->description : $manufacturer->description;

        if (! $manufacturer->save()) {
            return response()->json(['message' => 'Виникла помилка при збереженні'], 422);
        }

        return response()->json(['message' => 'Збережено', 'manufacturer' => $manufacturer]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (EquipmentManufacturer::destroy($id)) {
            return response()->json(['message' => 'Виробник обладнання видалений']);
        }

        return response()->json(['message' => 'Виникла помилка при видаленні'], 422);
    }
}
