<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role', 'name', 'email', 'password', 'locked', 
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'locked' => 'boolean',
    ];

    /**
     * Determine if the user has a specified role.
     *
     * @param  int  $userId
     * @param  string  $role
     * @return bool
     */
    public function hasRole($userId, $role)
    {
        $user = static::findOrFail($userId);
        return $user->role == $role ? true : false;
    }

    /**
     * Get the departments associated to the user.
     */
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'departments_users');
    }
}
