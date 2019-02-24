<?php

namespace App\Http\Controllers;

use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class AuthController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Auth the user by login and email.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:191',
            'password' => 'required|between:6,191'
        ]);

        if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Дані невірні'], 401);
        }

        return response()->json([
            'message' => 'Ви увійшли в систему',
            'token' => $token,
            'user' => Auth::user(),
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
            return response()->json(['message' => 'Токен не дійсний'], 401);
        }

        return response()->json(['token' => JWTAuth::refresh($token)]);
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgot(Request $request) {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Лист відправлено на вашу пошту'], 201)
            : response()->json(['message' => 'Неможливо відправити на пошту'], 401);
    }

    /**
     * Invalidate current JWT Token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message', 'Ви вийшли з системи']);
    }
}
