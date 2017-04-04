<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
   /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'departments';

    /**
     * Indicates if the timestamps are included.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'name', 
    ];

    /**
     * Get the users associated to a department.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'departments_users');
    }
}
