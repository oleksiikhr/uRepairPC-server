<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use App\Mail\UserCreated;
use App\Mail\EmailChange;
use App\Enums\Permissions;
use Illuminate\Http\Request;
use App\Http\Traits\ImageTrait;
use App\Http\Helpers\FileHelper;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Events\Users\Edit as UserEditEvent;

class UserController extends Controller
{
    use ImageTrait;

    /** @var string */
    private $_model = User::class;

    /**
     * Add middleware depends on user permissions.
     *
     * @param  Request  $request
     * @return array
     */
    public function permissions(Request $request): array
    {
        $requestId = (int)$request->user;
        $isOwnProfile = $requestId === Auth::id();

        return [
            'index' => Permissions::USERS_VIEW,
            'show' => $isOwnProfile ? null : Permissions::USERS_VIEW,
            'getImage' => $isOwnProfile ? null : Permissions::USERS_VIEW,
            'update' => $isOwnProfile ? Permissions::PROFILE_EDIT : Permissions::USERS_EDIT,
            'updateEmail' => $isOwnProfile ? Permissions::PROFILE_EDIT : Permissions::USERS_EDIT,
            'setImage' => $isOwnProfile ? Permissions::PROFILE_EDIT : Permissions::USERS_EDIT,
            'deleteImage' => $isOwnProfile ? Permissions::PROFILE_EDIT : Permissions::USERS_EDIT,
            'updatePassword' => $isOwnProfile ? Permissions::PROFILE_EDIT : Permissions::USERS_EDIT,
            'store' => Permissions::USERS_CREATE,
            'delete' => $requestId === 1 || $isOwnProfile ? Permissions::DISABLE : Permissions::USERS_DELETE,
            'updateRoles' => $requestId === 1 ? Permissions::DISABLE : Permissions::ROLES_MANAGE,
        ];
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

        if (Auth::user()->can(Permissions::ROLES_VIEW)) {
            $query->with('roles');
        }

        // Search
        if ($request->has('search') && $request->has('columns') && ! empty($request->columns)) {
            foreach ($request->columns as $column) {
                $query->orWhere($column, 'LIKE', '%' . $request->search . '%');
            }
        }

        // Order
        if ($request->has('sortColumn')) {
            $query->orderBy($request->sortColumn, $request->sortOrder === 'descending' ? 'desc' : 'asc');
        }

        // Filter
        if ($request->has('filterRoleById')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('id', $request->filterRoleById);
            });
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
        $defaultRoles = Role::where('default', true)->get();
        $password = User::generateRandomStrPassword();

        $user = new User;
        $user->fill($request->all());
        $user->email = $request->email;
        $user->password = bcrypt($password);
        $user->assignRole($defaultRoles);

        if (! $user->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        Mail::to($user)->send(new UserCreated($password));
//        TODO Event

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
        $me = Auth::user();

        $output = [
            'message' => __('app.users.show'),
            'user' => $user,
        ];

        if ($me->can(Permissions::ROLES_VIEW) || $me->id === $user->id) {
            $output['permissions'] = $user->getAllPermissions()->pluck('name');
        }

        return response()->json($output);
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

        if (! $user->save()) {
            return response()->json(['message' => __('app.database.save_error')], 422);
        }

        event(new UserEditEvent($user, 'data'));

        return response()->json([
            'message' => __('app.users.update'),
            'user' => $user,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  UserRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(UserRequest $request, int $id)
    {
        $user = User::findOrFail($id);

        // Destroy profile image (avatar)
        if ($request->image_delete && $user->image) {
            $deleted = FileHelper::delete($user->image);

            if (! $deleted) {
                return response()->json(['message' => __('app.files.file_not_deleted')]);
            }

            $user->image = null;
            $user->save();
        }

        if (! $user->delete()) {
            return response()->json(['message' => __('app.database.destroy_error')], 422);
        }

//        TODO Event

        return response()->json([
            'message' => __('app.users.destroy'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRoles(Request $request, int $id)
    {
        $request->validate([
            'roles' => 'array',
        ]);

        $user = User::findOrFail($id);
        $user->syncRoles($request->roles);

        event(new UserEditEvent($user, 'roles'));

        return response()->json([
            'message' => __('app.users.roles_changed'),
            'user' => $user,
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

        event(new UserEditEvent($user, 'email'));

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
        if (Auth::id() === $id) {
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
        event(new UserEditEvent($user, 'email'));

        return response()->json([
            'message' => __('app.users.password_email_changed'),
        ]);
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

        event(new UserEditEvent($user, 'password'));

        return response()->json([
            'message' => __('app.users.password_changed'),
        ]);
    }
}
