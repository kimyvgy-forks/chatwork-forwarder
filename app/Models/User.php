<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use App\Notifications\MailResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function bots()
    {
        return $this->hasMany(Bot::class);
    }

    public function webhooks()
    {
        return $this->hasMany(Webhook::class);
    }

    public function scopeSearch($query, $searchParams, $perPage)
    {
        return $query
            ->where('name', 'LIKE', '%' . $searchParams['name'] . '%')
            ->where('email', 'LIKE', '%' . $searchParams['email'] . '%')
            ->paginate($perPage);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordNotification($token));
    }

    public function templates()
    {
        return $this->hasMany(Template::class);
    }
}
