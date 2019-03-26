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
        $equipment = new Equipment;
        $equipment->serial_number = $request->serial_number;
        $equipment->inventory_number = $request->inventory_number;
        $equipment->type_id = $request->type_id;
        $equipment->manufacturer_id = $request->manufacturer_id;
        $equipment->model_id = $request->model_id;

        if (! $equipment->save()) {
            return response()->json(['message' => 'Виникла помилка при збереженні'], 422);
        }

        return response()->json([
            'message' => 'Збережено',
            'equipment' => $this->getSelectModel()->find($equipment->id),
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
        $equipment = $this->getSelectModel()->findOrFail($id);

        return response()->json(['message' => 'Обладнання отримано', 'equipment' => $equipment]);
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
        $equipment = Equipment::findOrFail($id);
        $equipment->serial_number = $request->has('serial_number') ? $request->serial_number : $equipment->serial_number;
        $equipment->inventory_number = $request->has('inventory_number') ? $request->inventory_number : $equipment->inventory_number;
        $equipment->manufacturer_id = $request->has('manufacturer_id') ? $request->manufacturer_id : $equipment->manufacturer_id;
        $equipment->type_id = $request->has('type_id') ? $request->type_id : $equipment->type_id;
        $equipment->model_id = $request->has('model_id') ? $request->model_id : $equipment->model_id;

        if (! $equipment->save()) {
            return response()->json(['message' => 'Виникла помилка при збереженні'], 422);
        }

        return response()->json([
            'message' => 'Збережено',
            'equipment' => $this->getSelectModel()->find($equipment->id),
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
            ->leftJoin('equipment_types', 'equipments.type_id', '=', 'equipment_types.id')
            ->leftJoin('equipment_manufacturers', 'equipments.manufacturer_id', '=', 'equipment_manufacturers.id')
            ->leftJoin('equipment_models', 'equipments.model_id', '=', 'equipment_models.id');
    }
}
