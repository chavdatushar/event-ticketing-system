<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Event extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'date', 'location', 'user_id','is_cancelled'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function organizers()
{
    return $this->belongsToMany(User::class, 'event_organizers');
}

    public function attendee()
    {
        return $this->hasManyThrough(Attendee::class, Ticket::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}
