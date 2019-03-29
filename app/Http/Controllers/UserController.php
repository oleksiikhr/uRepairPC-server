<?php

namespace App\Http\Controllers;

use App\User;
use App\Mail\UserCreated;
use App\Mail\EmailChange;
use Illuminate\Http\Request;
use App\Http\Traits\ImageTrait;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    use ImageTrait;

    /** @var string */
    private $_model = User::class;

    public function __construct()
    {
        $this->middleware('admin.or.current')->only([
            'update', 'updateEmail', 'setImage', 'deleteImage', 'updatePassword',
        ]);

        $this->allowRoles([
            User::ROLE_WORKER => [
                'index', 'show', 'update', 'updatePassword', 'updateEmail', 'getImage', 'setImage', 'deleteImage',
            ],
            User::ROLE_USER => [
                'index', 'show', 'update', 'updatePassword', 'updateEmail', 'getImage', 'setImage', 'deleteImage',
            ],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  UserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(UserRequest $request)
    {
        $query = User::query();

        // Search
        if ($request->has('search') && $request->has('columns') && count($request->columns)) {
            foreach ($request->columns as $column) {
                $query->orWhere($column, 'LIKE', '%' . $request->search . '%');
            }
        }

        // Order
        if ($request->has('sortColumn')) {
            $query->orderBy($request->sortColumn, $request->sortOrder === 'descending' ? 'desc' : 'asc');
        }

        $list = $query->paginate(self::PAGINATE_DEFAULT);

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
        $password = User::generateRandomStrPassword();

        $user = new User;
        $user->fill($request->all());
        $user->email = $request->email;
        $user->password = bcrypt($password);
        User::setRole($user, $request->role);

        if (! $user->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        Mail::to($user)->send(new UserCreated($password));

        return response()->json([
            'message' => __('app.users.store'),
            'user' => $user,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'message' => __('app.users.show'),
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserRequest  $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, int $id)
    {
        $user = User::findOrFail($id);
        $user->fill($request->all());
        User::setRole($user, $request->role);

        if (! $user->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        return response()->json([
            'message' => __('app.users.update'),
            'user' => $user,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $me = Auth::user();

        if ($me->id === $id) {
            return response()->json(['message' => __('app.users.self_destroy_error')], 403);
        }

        if (! User::destroy($id)) {
            return response()->json(['message' => __('app.database.destroy_error')], 422);
        }

        return response()->json([
            'message' => __('app.users.destroy'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateEmail(Request $request, int $id)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        $user = User::findOrFail($id);
        Mail::to($user)->send(new EmailChange($request->email));
        $user->email = $request->email;

        if (! $user->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        return response()->json([
            'message' => __('app.users.email_changed'),
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * If the user edit the same, need password
     * Another - send email to the user with
     * new random password.
     *
     * @param   Request  $request
     * @param   int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        // We can change only for own profile.
        if (Auth::user()->id === $id) {
            return $this->setPasswordProfile($request, $user);
        }

        return $this->setPasswordEmail($user);
    }

    /**
     * Generate a new random password and send to email.
     *
     * @param  User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    private function setPasswordEmail(User $user)
    {
        $password = User::generateRandomStrPassword();

        // Change password for another users - send generate random password to email.
        $user->password = bcrypt($password);

        if (! $user->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        Mail::to($user)->send(new UserCreated($password));

        return response()->json(['message' => __('app.users.password_email_changed')]);
    }

    /**
     * Set a new password to profile of the current user.
     *
     * @param  Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    private function setPasswordProfile(Request $request, User $user)
    {
        $request->validate(['password' => 'required|string']);

        $user->password = bcrypt($request->password);

        if (! $user->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        return response()->json(['message' => __('app.users.password_changed')]);
    }
}
