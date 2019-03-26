<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

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
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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
        // Hide role
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
     * User role is Admin.
     *
     * @param  string  $role
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }
}
