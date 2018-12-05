<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public const WRITER = 'writer';
    public const READER = 'reader';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'api_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'author_id');
    }

    /**
     * @param string $role
     * @return bool
     */
    public function isGranted(string $role): bool
    {
        return $this->role == $role;
    }
}
