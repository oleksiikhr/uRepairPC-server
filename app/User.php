<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes;

    /** @var int */
    private const RANDOM_PASSWORD_LEN = 10;

    /** @var string - DB */
    const ROLE_ADMIN = 'admin';

    /** @var string - DB */
    const ROLE_WORKER = 'worker';

    /** @var string - DB */
    const ROLE_USER = 'user';

    /** @var array */
    const ROLES = [self::ROLE_ADMIN, self::ROLE_WORKER, self::ROLE_USER];

    /** @var array */
    const ALLOW_COLUMNS_SEARCH = [
        'id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'role',
        'phone',
        'updated_at',
        'created_at',
    ];

    /** @var array */
    const ALLOW_COLUMNS_SORT = [
        'id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'role',
        'phone',
        'updated_at',
        'created_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'phone',
        'description',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        // Only admin can see user role or current user own role
        if (Auth::user()->role !== self::ROLE_ADMIN && Auth::user()->id !== $this->id) {
            $this->makeHidden('role');
        }

        return parent::toArray();
    }

    /**
     * Role of the user is Admin.
     *
     * @return bool
     */
    public function admin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Role of the user is Worker.
     *
     * @return bool
     */
    public function worker()
    {
        return $this->role === self::ROLE_WORKER;
    }

    /**
     * Role of the user is User.
     *
     * @return bool
     */
    public function user()
    {
        return $this->role === self::ROLE_USER;
    }

    /**
     * @param  User  $user
     * @param  String  $role
     * @return bool
     */
    public static function setRole(User &$user, string $role)
    {
        $me = Auth::user();

        if (empty($role)) {
            return false;
        }

        // No admin can't set a role
        if (! $me->admin()) {
            return false;
        }

        // Block change myself a role
        if ($me->id === $user->id) {
            return false;
        }

        $user->role = $role;

        return true;
    }

    /**
     * @return string
     */
    public static function generateRandomStrPassword()
    {
        return str_random(self::RANDOM_PASSWORD_LEN);
    }
}
