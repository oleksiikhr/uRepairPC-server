<?php

namespace App;

use App\Enums\Permissions;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes, HasRoles;

    protected $guard_name = 'api';

    /** @var int */
    private const RANDOM_PASSWORD_LEN = 10;

    /** @var string - DB */
    const ROLE_ADMIN = 'admin';

    /** @var array */
    const ALLOW_COLUMNS_SEARCH = [
        'id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
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
        $user = Auth::user();

        // Only with Permissions::ROLES_VIEW can see roles or for profile
        if (! $user->can(Permissions::ROLES_VIEW) && $user->id !== $this->id) {
            $this->makeHidden('roles');
            $this->makeHidden('permissions');
            $this->makeHidden('permission_names');
        }

        return parent::toArray();
    }

    /**
     * @return string
     */
    public static function generateRandomStrPassword()
    {
        return str_random(self::RANDOM_PASSWORD_LEN);
    }

    /* | -----------------------------------------------------------------------------------
     * | Relationships
     * | -----------------------------------------------------------------------------------
     */

    public function request()
    {
        return $this->hasMany(Request::class);
    }
}
