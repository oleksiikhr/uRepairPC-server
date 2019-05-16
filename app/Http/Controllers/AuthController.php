<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Add middleware depends on user permissions.
     *
     * @param  Request  $request
     * @return array
     */
    public function permissions(Request $request): array
    {
        return [];
    }

    /**
     * Get a current user with permissions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        $user = Auth::user();

        return response()->json([
            'user' => $user,
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    }

    /**
     * Auth the user by login and email.
     *
     * @param  AuthRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AuthRequest $request)
    {
        if (! $token = JWTAuth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => __('app.auth.login_error')], 422);
        }

        $user = Auth::user();

        return response()->json([
            'message' => __('app.auth.login_success'),
            'token' => $token,
            'user' => $user,
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    }

    /**
     * Refresh token to user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $token = JWTAuth::getToken();

        if (! $token) {
            return response()->json(['message' => __('app.auth.token_invalid')], 422);
        }

        $newToken = JWTAuth::refresh($token);

        // deleted_at has value - user is empty
        $userId = JWTAuth::setToken($newToken)->getPayload()->get('sub');
        $user = User::find($userId);

        if (! $user) {
            return response()->json(['message' => __('app.auth.token_invalid')], 422);
        }

        return response()->json([
            'message' => __('app.auth.token_refresh'),
            'token' => $newToken,
        ]);
    }

    /**
     * Invalidate current JWT Token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'message' => __('app.auth.logout'),
            'asd' => JWTAuth::getPayload(),
        ]);
    }
}
