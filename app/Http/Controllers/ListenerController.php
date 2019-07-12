<?php

namespace App\Http\Controllers;

use App\Events\JoinRooms;
use Illuminate\Http\Request;

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
     * Refresh all rooms for socketId and listen profile events.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync()
    {
        $user = auth()->user();

        // Listen events profile
        $rooms = [
            "users.{$user->id}",
            "users.{$user->id}.roles",
        ];

        event(new JoinRooms($rooms, true));

        return response()->json([
            'rooms' => $rooms,
        ]);
    }
}
