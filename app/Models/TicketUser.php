<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ticket;
use App\Models\User;


    class TicketUser extends Model
    {
        use HasFactory;

        protected $fillable = [
            'ticket_id',
            'user_id',
            'message',
            'response',
            'status',
            'created_by',
            'updated_by',
        ];


    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
