<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    /** @var array */
    const GET_ROLES = [
        User::ROLE_ADMIN,
        User::ROLE_WORKER
    ];

    public function __construct()
    {
        $this->allowRoles([
            User::ROLE_WORKER => [],
            User::ROLE_USER => [],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = User::query();

//        TODO Count requests, another info

        foreach (self::GET_ROLES as $role) {
            $query->orWhere('role', $role);
        }

        $list = $query->get()->groupBy('role');

        return response()->json($list);
    }
}
