<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\traits\ImageTrait;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ImageTrait;

    /** @var string */
    private $_model = User::class;

    public function __construct()
    {
        $this->allowRoles([
            User::ROLE_MODERATOR => ['index2', 'show', 'store', 'update', 'getImage', 'updateImage', 'destroyImage'],
            User::ROLE_WORKER => ['index', 'show', 'getImage', 'updateImage', 'destroyImage'],
            User::ROLE_USER => ['index', 'show', 'getImage', 'updateImage', 'destroyImage'],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $list = User::paginate(50);

        return response()->json($list);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request)
    {
        $password = str_random(10);

        $user = new User;
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->description = $request->description;
        $user->role = $this->setRole($user, $request->role);
        $user->password = bcrypt($password);

        if (! $user->save()) {
            return response()->json(['message' => 'Виникла помилка при збереженні'], 422);
        }

//        TODO Send email with password

        return response()->json(['message' => 'Збережено', 'user' => $user]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return response()->json(['message' => 'Користувач отриман', 'user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserRequest  $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, $id)
    {
        $me = Auth::user();

        if (($me->isUserRole() || $me->isWorkerRole()) && $me->id !== $id) {
            return response()->json(['Немає прав'], 422);
        }

        $user = User::findOrFail($id);
        $user->first_name = $request->has('first_name') ? $request->first_name : $user->first_name;
        $user->middle_name = $request->has('middle_name') ? $request->middle_name : $user->middle_name;
        $user->last_name = $request->has('last_name') ? $request->last_name : $user->last_name;
        $user->phone = $request->has('phone') ? $request->phone : $user->phone;
        $user->description = $request->has('description') ? $request->description : $user->description;
        $user->role = $request->has('role') ? $this->setRole($user, $request->role) : $user->role;

        if (! $user->save()) {
            return response()->json(['message' => 'Виникла помилка при збереженні'], 422);
        }

        return response()->json(['message' => 'Збережено', 'user' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $me = Auth::user();

        if ($me->id === $id) {
            return response()->json(['message' => 'Неможливо видалити самого себе'], 422);
        }

        if (User::destroy($id)) {
            return response()->json(['message' => 'Користувач видалений']);
        }

        return response()->json(['message' => 'Виникла помилка при видаленні'], 422);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateImage(Request $request, int $id)
    {
        $me = Auth::user();

        if (($me->isUserRole() || $me->isWorkerRole()) && $me->id !== $id) {
            return response()->json(['message' => 'Немає прав'], 422);
        }

        return $this->setImage($request, $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyImage(int $id)
    {
        $me = Auth::user();

        if (($me->isUserRole() || $me->isWorkerRole()) && $me->id !== $id) {
            return response()->json(['message' => 'Немає прав'], 422);
        }

        return $this->deleteImage($id);
    }

    /**
     * @param  User  $user
     * @param  String  $role
     * @return bool
     */
    private function setRole(&$user, $role)
    {
        $me = Auth::user();
        $user->role = User::ROLE_USER;

        if (empty($role)) {
            return false;
        }

        // User and Worker can't set a role
        if ($me->isUserRole() || $me->isWorkerRole()) {
            return false;
        }

        // Block change myself a role
        if ($me->id === $user->id) {
            return false;
        }

        // Moderator can't set admin a role
        if ($me->isModeratorRole() && $role === User::ROLE_ADMIN) {
            return false;
        }

        $user->role = $role;
        return true;
    }
}
