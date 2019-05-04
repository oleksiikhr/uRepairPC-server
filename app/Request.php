<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Request extends Model
{
    use SoftDeletes;

    /** @var array */
    const ALLOW_COLUMNS_SEARCH = [
        'id',
        'user_name',
        'assign_name',
        'equipment_serial_number',
        'equipment_inventory_number',
        'equipment_name',
        'title',
        'location',
        'updated_at',
        'created_at',
    ];

    /** @var array */
    const ALLOW_COLUMNS_SORT = [
        'id',
        'title',
        'location',
        'priority_name',
        'updated_at',
        'created_at',
    ];

    /**
     * Correctly display ORM request.
     *
     * @var array
     */
    const SEARCH_RELATIONSHIP = [
        'user_name' => 'users.last_name',
        'assign_name' => 'users_assign.last_name',
        'equipment_serial_number' => 'equipments.serial_number',
        'equipment_inventory_number' => 'equipments.inventory_number',
        'equipment_name' => 'equipment_models.name',
    ];

    /**
     * Correctly display ORM request.
     *
     * @var array
     */
    const SORT_RELATIONSHIP = [
        'priority_name' => 'request_priorities.value',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'equipment_id',
        'title',
        'description',
        'location',
    ];

    /**
     * @return mixed
     */
    public static function querySelectJoins()
    {
        return self::select(
            'requests.*',
            DB::raw("CONCAT(users.last_name,' ',users.first_name) AS user_name"),
            DB::raw("CONCAT(users_assign.last_name,' ',users_assign.first_name) AS assign_name"),
            'request_types.name as type_name',
            'request_priorities.name as priority_name',
            'request_priorities.value as priority_value',
            'request_priorities.color as priority_color',
            'request_statuses.name as status_name',
            'request_statuses.color as status_color',
            'equipments.serial_number as equipment_serial_number',
            'equipments.inventory_number as equipment_inventory_number',
            'equipments.model_id as equipment_model_id',
            'equipment_models.name as equipment_name'
        )
            ->leftJoin('users', 'requests.user_id', '=', 'users.id')
            ->leftJoin('users as users_assign', 'requests.assign_id', '=', 'users_assign.id')
            ->leftJoin('request_types', 'requests.type_id', '=', 'request_types.id')
            ->leftJoin('request_priorities', 'requests.priority_id', '=', 'request_priorities.id')
            ->leftJoin('request_statuses', 'requests.status_id', '=', 'request_statuses.id')
            ->leftJoin('equipments', 'requests.equipment_id', '=', 'equipments.id')
            ->leftJoin('equipment_models', 'equipments.model_id', '=', 'equipment_models.id');
    }
}
