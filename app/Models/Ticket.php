<?php

namespace App\Models;

use App\Enums\TicketType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = ['event_id','type', 'price', 'availability'];

    protected $casts = [
        'type' => TicketType::class,
    ];


    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
