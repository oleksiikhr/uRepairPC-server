<?php

namespace App\Http\Controllers;

use App\User;
use App\Equipment;
use App\Enums\Perm;
use App\Events\JoinRooms;
use Illuminate\Http\Request;
use App\Request as RequestModel;
use App\Http\Requests\ListenerRequest;

class ListenerController extends Controller
{
//    TODO Rewrite all! Need new schema to work with socket

    /**
     * @var User
     */
    private $_user;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->_user = auth()->user();
    }

    /**
     * Add middleware depends on user permissions.
     *
     * @param  Request  $request
     * @return array
     */
    public function permissions(Request $request): array
    {
        return [
            //
        ];
    }

    /**
     * Refresh all rooms to input socketId.
     *
     * @param  ListenerRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync(ListenerRequest $request)
    {
        $rooms = $this->filterRoomsByPermissions($request->rooms ?? []);

        // Listen events for own profile
        array_push($rooms, 'users.'.$this->_user->id, 'users.'.$this->_user->id.'.roles');

        // Listen Equipment settings
        if ($this->_user->perm(Perm::EQUIPMENTS_CONFIG_VIEW)) {
            array_push($rooms, 'equipment_types', 'equipment_manufacturers', 'equipment_models');
        }

        // Listen Request settings
        if ($this->_user->perm(Perm::REQUESTS_CONFIG_VIEW)) {
            array_push($rooms, 'request_statuses', 'request_priorities', 'request_types');
        }

        event(new JoinRooms($rooms, true));

        return response()->json([
            'rooms' => $rooms,
        ]);
    }

    /**
     * Send event to Redis for join in rooms.
     *
     * @param  ListenerRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function join(ListenerRequest $request)
    {
        $rooms = $this->filterRoomsByPermissions($request->rooms ?? []);
        event(new JoinRooms($rooms, false));

        return response()->json([
            'rooms' => $rooms,
        ]);
    }

    /**
     * @param  array  $rooms
     * @return array
     */
    private function filterRoomsByPermissions(array $rooms): array
    {
        $filteredRooms = [];

        foreach ($rooms as $room) {
            // users.1, roles.51, etc.
            $explode = explode('.', $room);
            $method = 'logic_' . $explode[0]; // section

            if (count($explode) > 2 && method_exists(self::class, $method)) {
                array_push($filteredRooms, ...$this->$method(...$explode));
            }
        }

        return $filteredRooms;
    }

    /* | -----------------------------------------------------------------------------------
     * | Logic
     * | -----------------------------------------------------------------------------------
     */

    private function logic_users($room, $id): array
    {
        $rooms = [];

        if ($this->_user->perm(Perm::USERS_VIEW_ALL)) {
            $rooms[] = $room.'.'.$id;
        }

        if ($this->_user->perm(Perm::ROLES_VIEW_ALL)) {
            $rooms[] = $room.'.'.$id.'.roles';
        }

        return $rooms;
    }

    private function logic_roles($room, $id): array
    {
        if ($this->_user->perm(Perm::ROLES_VIEW_ALL)) {
            return [$room.'.'.$id];
        }

        return [];
    }

    private function logic_equipments($room, $id): array
    {
        $rooms = [];

        if (! $this->_user->perm(Perm::EQUIPMENTS_VIEW_ALL)) {
            if (! $this->_user->perm(Perm::EQUIPMENTS_VIEW_OWN)) {
                return [];
            }

            $isExists = Equipment::where('id', $id)->where('user_id', $this->_user->id)->exists();
            if (! $isExists) {
                return [];
            }
        }

        $rooms[] = $room.'.'.$id;

        if ($this->_user->perm(Perm::EQUIPMENTS_FILES_VIEW_ALL)) {
            $rooms[] = 'equipment_files.'.$id;
        }

//        TODO own listen?

        return $rooms;
    }

    private function logic_requests($room, $id): array
    {
        $rooms = [];

        if ($this->_user->perm(Perm::REQUESTS_VIEW_OWN)) {
//            RequestModel::where('user_id', $this->_user->id)
//                ->orWhere('assign_id', $this->_user->id)
//                ->exists()
            $filteredRooms[] = $room;
        }

        return $rooms;
    }
}
