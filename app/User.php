<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    /** @var string - DB */
    const ROLE_ADMIN = 'admin';

    /** @var string - DB */
    const ROLE_MODERATOR = 'moderator';

    /** @var string - DB */
    const ROLE_WORKER = 'worker';

    /** @var string - DB */
    const ROLE_USER = 'user';

    /** @var array */
    const ROLES = [self::ROLE_ADMIN, self::ROLE_MODERATOR, self::ROLE_WORKER, self::ROLE_USER];

    /** @var array */
    const ALLOW_COLUMNS_SEARCH = [
        'id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'image',
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

    use Notifiable;

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
     * Role of the user is Admin.
     *
     * @return bool
     */
    public function isAdminRole()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Role of the user is Moderator.
     *
     * @return bool
     */
    public function isModeratorRole()
    {
        return $this->role === self::ROLE_MODERATOR;
    }

    /**
     * Role of the user is Worker.
     *
     * @return bool
     */
    public function isWorkerRole()
    {
        return $this->role === self::ROLE_WORKER;
    }

    /**
     * Role of the user is User.
     *
     * @return bool
     */
    public function isUserRole()
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

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return MailMessage
     */
    public function sendPasswordResetNotification($token)
    {
//        TODO
        return (new MailMessage)
            ->subject('Скинути сповіщення про пароль')
            ->line('Ви отримуєте це повідомлення, оскільки ми отримали запит на зміну пароля для вашого облікового запису.')
            ->action(Lang::getFromJson('Reset Password'), url(config('app.url').route('password.reset', $this->token, false)))
            ->line('Ця посилання для скидання пароля закінчується: ' . config('auth.passwords.users.expire') . ' хвилин(а)')
            ->line('Якщо ви не подали запит на скидання пароля, додаткові дії не потрібно');
    }
}
