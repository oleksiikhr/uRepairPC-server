<?php

namespace App\Http\Controllers;

use App\Events\JoinRooms;
use App\Enums\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ListenerRequest;

class ListenerController extends Controller
{
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync()
    {
        $user = Auth::user();
        $rooms = ['users.' . $user->id];

        if ($user->can(Permissions::EQUIPMENTS_CONFIG_VIEW)) {
            array_push($rooms, 'equipment_types', 'equipment_manufacturers', 'equipment_models');
        }

        if ($user->can(Permissions::REQUESTS_CONFIG_VIEW)) {
            array_push($rooms, 'request_statuses', 'request_priorities', 'request_types');
        }

        event(new JoinRooms($rooms, true));

        return response()->json([
            'rooms' => $rooms,
        ]);
    }

    /**
     * Send event to Redis for join in rooms.
     * TODO Refactor + share to sync method
     * TODO Change to one room
     *
     * @param  ListenerRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function join(ListenerRequest $request)
    {
        $user = Auth::user();
        $roomsListeners = [];

        foreach ($request->rooms as $room) {
            $explode = explode('.', $room);
            $section = $explode[0];

            switch ($section) {
                case 'equipments':
                    if ($user->can(Permissions::EQUIPMENTS_VIEW)) {
                        $roomsListeners[] = $room;
                    }
                    break;
                case 'equipment_files':
                    if ($user->can(Permissions::EQUIPMENTS_FILES_VIEW)) {
                        $roomsListeners[] = $room;
                    }
                    break;
                case 'users':
                    if ($user->can(Permissions::USERS_VIEW)) {
                        $roomsListeners[] = $room;
                    }
                    break;
                case 'roles':
                    if ($user->can(Permissions::ROLES_VIEW)) {
                        $roomsListeners[] = $room;
                    }
                    break;
                case 'requests':
                    if ($user->can(Permissions::REQUESTS_VIEW)) {
                        $roomsListeners[] = $room;
//                        TODO request_files
                    } else {
                        // TODO Make request to DB and check if user create / assign to request
                    }
                    break;
            }
        }

        event(new JoinRooms($roomsListeners, false));

        return response()->json([
            'rooms' => $roomsListeners,
        ]);
    }
}
