<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
   /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tickets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'department_id', 'number', 'room', 'phone', 'subject', 'details', 'priority', 'resolved', 'assigned_to', 
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'assigned_to' => 'integer',
        'resolved' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'creator', 'formatted_status', 'formatted_date', 'details_button'
    ];

    /**
     * The query scope to return authenticated users tickets.
     *
     * @param  string  $query
     * @return $this
     */
    public function scopeMine($query)
    {
        return $query->where('user_id', auth()->user()->id);
    }

    /**
     * The query scope for ticket searching via number, subject, and details.
     *
     * @param  string  $query
     * @return $this
     */
    public function scopeSearch($query, $search)
    {
        $value = is_null($search) ? '' : $search;

        return $query->where('number', 'LIKE', '%'.$value.'%')
                     ->orWhere('subject', 'LIKE', '%'.$value.'%')
                     ->orWhere('details', 'LIKE', '%'.$value.'%');
    }

    /**
     * Generate the next ticket number.
     *
     * @return int
     */
    public static function number($id)
    {
        return 10000 + $id;
    }

    /**
     * Get the owner of the ticket.
     */
    public function owner()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get the assigned staff member of the ticket.
     */
    public function assignment()
    {
        return $this->hasOne(User::class, 'id', 'assigned_to');
    }  

    /**
     * Get the department associated with the ticket.
     */
    public function department()
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }

    /**
     * Get the comments associated with the ticket.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'ticket_id', 'id')->orderBy('created_at', 'desc');
    }

    /**
     * Get the creator name of the ticket.
     */
    public function getCreatorAttribute()
    {
        $user = $this->owner;
        return $user->name ?: 'Orphaned';
    }

    /**
     * Display to the user if the ticket is open or closed.
     *
     * @param bool  $status
     * @return string
     */
    public function getFormattedStatusAttribute()
    {
        return $this->resolved == false ? '<span class="label label-danger">Open</span>' : '<span class="label label-warning">Closed</span>';
    }

    /**
     * Display to the ticket created at date in diffForHumans style.
     *
     * @param bool  $status
     * @return string
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Display the details button link.
     *
     * @param bool  $status
     * @return string
     */
    public function getDetailsButtonAttribute()
    {
        return $this->owner->role == 'user' ? '<a href="'.url('/tickets/'.$this->id).'" class="btn btn-info">Details</a>'
                                              : '<a href="'.url('/tickets/staff/'.$this->id).'" class="btn btn-info">Details</a>';
    }
}
