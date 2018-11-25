<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'secret'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'updated_at',
        'created_at',
        'last_seen'
    ];

    /** @var string - key for Redis (user:{login}) */
    private const _REDIS_KEY = 'user:';

    /**
     * @return String|null
     */
    public function getSecretAttribute()
    {
        $attr = $this->attributes['secret'];

        if (! $attr) {
            return null;
        }

        return decrypt($attr);
    }

    /**
     * @param  String $value
     * @return void
     */
    public function setSecretAttribute($value)
    {
        $this->attributes['secret'] = encrypt($value);
    }

    /**
     * Get user from Redis store or database.
     *
     * @param  int $id
     * @return mixed
     */
    public static function getUserById($id): User
    {
        $store = Redis::get(self::_REDIS_KEY . $id);

        $user = $store ? unserialize($store) : User::find($id);

        return $user;
    }

    /**
     * Save the user to Redis by id.
     *
     * @param  int $id
     * @param  User $user
     * @param  int $ttl
     * @return void
     */
    public static function saveUserById($id, $user, $ttl = Controller::DAY_SECONDS): void
    {
        Redis::set(self::_REDIS_KEY . $id, serialize($user));
        Redis::expire(self::_REDIS_KEY . $id, $ttl);
    }
}
