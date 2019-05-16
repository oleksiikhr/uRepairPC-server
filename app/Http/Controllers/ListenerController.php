<?php

namespace App\Http\Controllers;

use App\User;
use App\Events\JoinRooms;
use App\Enums\Permissions;
use Illuminate\Http\Request;
use App\Request as RequestModel;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ListenerRequest;

class ListenerController extends Controller
{
    /**
     * @var User
     */
    private $_user;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->_user = Auth::user();
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
        array_push($rooms, 'users.'.$this->_user->id);

        if ($this->_user->can(Permissions::EQUIPMENTS_CONFIG_VIEW)) {
            array_push($rooms, 'equipment_types', 'equipment_manufacturers', 'equipment_models');
        }

        if ($this->_user->can(Permissions::REQUESTS_CONFIG_VIEW)) {
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
     * TODO Optimize.
     *
     * @param  array  $rooms
     * @return array
     */
    private function filterRoomsByPermissions(array $rooms): array
    {
        $filterRooms = [];

        foreach ($rooms as $room) {
            $explode = explode('.', $room);
            $section = $explode[0];

            switch ($section) {
                case 'equipments':
                    if ($this->_user->can(Permissions::EQUIPMENTS_VIEW)) {
                        $filterRooms[] = $room;
                    }
                    break;
                case 'equipment_files':
                    if ($this->_user->can(Permissions::EQUIPMENTS_FILES_VIEW)) {
                        $filterRooms[] = $room;
                    }
                    break;
                case 'users':
                    if ($this->_user->can(Permissions::USERS_VIEW)) {
                        $filterRooms[] = $room;
                    }
                    break;
                case 'roles':
                    if ($this->_user->can(Permissions::ROLES_VIEW)) {
                        $filterRooms[] = $room;
                    }
                    break;
                case 'request_comments':
                case 'request_files':
                case 'requests':
                    if ($this->_user->can(Permissions::REQUESTS_VIEW) ||
                        RequestModel::where('user_id', $this->_user->id)
                            ->orWhere('assign_id', $this->_user->id)
                            ->exists()
                    ) {
                        $filterRooms[] = $room;
                    }
                    break;
            }
        }

        return $filterRooms;
    }
}
