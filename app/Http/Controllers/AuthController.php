<?php

namespace App\Http\Controllers;

use JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * Auth the user by login and email.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {
        $data = $request->only('email', 'password');
        $jwtToken = null;

        try {
            if (!$jwtToken = JWTAuth::attempt($data)) {
                return response()->json([ 'message' => 'Invalid Credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        return response()->json(['token' => $jwtToken]);
    }
}
