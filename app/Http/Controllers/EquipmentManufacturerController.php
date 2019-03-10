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
        $list = EquipmentManufacturer::paginate(self::PAGINATE_DEFAULT);

//        TODO order, sort, count

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
        $model = new EquipmentManufacturer;
        $model->name = $request->name;
        $model->description = $request->description;

        if (! $model->save()) {
            return response()->json(['message' => 'Виникла помилка при збереженні'], 409);
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
        $model = EquipmentManufacturer::findOrFail($id);

        return response()->json([
            'message' => 'Виробник обладнання отриман',
            'model' => $model,
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
        $model = EquipmentManufacturer::findOrFail($id);
        $model->name = $request->has('name') ? $request->name : $model->name;
        $model->description = $request->has('description') ? $request->description : $model->description;

        if (! $model->save()) {
            return response()->json(['message' => 'Виникла помилка при збереженні'], 409);
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
        if (EquipmentManufacturer::destroy($id)) {
            return response()->json(['message' => 'Виробник обладнання видалений']);
        }

        return response()->json(['message' => 'Виникла помилка при видаленні'], 409);
    }
}
