<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
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
