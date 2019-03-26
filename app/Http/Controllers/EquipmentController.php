<?php

namespace App\Http\Controllers;

use App\User;
use App\Equipment;
use Illuminate\Http\Request;
use App\Http\Requests\EquipmentRequest;

class EquipmentController extends Controller
{
    /**
     * Uses in search.
     */
    private const SEARCH_RELATIONSHIP = [
        'manufacturer_name' => 'equipment_manufacturers.name',
        'model_name' => 'equipment_models.name',
        'type_name' => 'equipment_types.name',
    ];

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
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'string',
            'columns.*' => 'string|in:' . join(',', Equipment::ALLOW_COLUMNS_SEARCH),
            'sortColumn' => 'string|in:' . join(',', Equipment::ALLOW_COLUMNS_SORT),
            'sortOrder' => 'string|in:ascending,descending',
        ]);

        $query = $this->getSelectModel();

        // Search
        if ($request->has('search') && $request->has('columns') && count($request->columns)) {
            foreach ($request->columns as $column) {
                $query->orWhere(self::SEARCH_RELATIONSHIP[$column] ?? $column, 'LIKE', '%' . $request->search . '%');
            }
        }

        // Order
        if ($request->has('sortColumn')) {
            $query->orderBy($request->sortColumn, $request->sortOrder === 'descending' ? 'desc' : 'asc');
        }

        $list = $query->paginate(self::PAGINATE_DEFAULT);

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
        if (Equipment::destroy($id)) {
            return response()->json(['message' => 'Обладнання видалено']);
        }

        return response()->json(['message' => 'Виникла помилка при видаленні'], 422);
    }

    /**
     * @return mixed
     */
    private function getSelectModel()
    {
        return Equipment::select(
            'equipments.*',
            'equipment_types.name as type_name',
            'equipment_manufacturers.name as manufacturer_name',
            'equipment_models.name as model_name'
        )
            ->join('equipment_types', 'equipments.type_id', '=', 'equipment_types.id')
            ->join('equipment_manufacturers', 'equipments.manufacturer_id', '=', 'equipment_manufacturers.id')
            ->join('equipment_models', 'equipments.model_id', '=', 'equipment_models.id');
    }
}
