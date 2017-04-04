<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
   /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'ticket_id', 'user_id', 'details', 'hidden', 
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'hidden' => 'boolean'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formatted_date', 'contributor', 
    ];

    /**
     * Get the contributor name of the comment.
     */
    public function getContributorAttribute()
    {
        $user = User::find($this->user_id);
        return $this->user_id == 0 ? 'System Generated' : $user->name;
    }

    /**
     * Display to the comment created at date in diffForHumans style.
     *
     * @param bool  $status
     * @return string
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * The query scope to return comments not hidden by staff.
     *
     * @param  string  $query
     * @return $this
     */
    public function scopeVisible($query)
    {
        return auth()->user()->role == 'user' ? $query->where('hidden', false) : '';
    }
}
