<?php

namespace App\Http\Controllers;

use App\Pc;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class WebsocketController extends Controller
{
    public function index()
    {
        // TODO Realize service project + .env
        return response('ws://localhost:3000/');
    }

    public function userAuth()
    {
        // TODO
    }

    // TODO Comments
    public function pcAuth(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|min:1',
            'token' => 'required|string'
        ]);

        Pc::where([
            'id' => $request->id,
            'socket_token' => $request->token
        ])->firstOrFail();

        return response()->json(['success' => true]);
    }

    /**
     * By authorized user, we get / create a PC key by id.
     * Uses in service/key.js
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function pcKey(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer|min:1',
            'login' => 'required|string',
            'secret' => 'required|string'
        ]);

        // Check user
        $user = User::where([
            'login' => $request->login,
            'secret' => $request->secret
        ])->first();

        // TODO Check user role

        if (! $user) {
            return response()->json(['error' => 'User not found']);
        }

        $pc = Pc::firstOrNew(['id' => $request->id]);
        $pc->socket_token = Str::random(60);
        $pc->save();

        return response()->json(['success' => true, 'data' => [
            'id' => $request->id,
            'token' => $pc->socket_token
        ]]);
    }
}
