<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @param   Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request) {
        $this->validate($request, [
            'login' => 'required|string|between:3,30',
            'password' => 'required|string|min:6'
        ]);

        $user = User::where('login', $request->login)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'User not found by this data'], 422);
        }

        if ($user->is_blocked) {
            return response()->json(['message' => 'User is blocked'], 422);
        }

        $user->last_seen = Carbon::now();
        $user->save();

        User::saveUserById($user->login, $user);

        return response()->json(['success' => true, 'user' => $user]);
    }
}
