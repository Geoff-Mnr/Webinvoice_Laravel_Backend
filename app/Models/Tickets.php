<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'comment',
        'description',
        'status',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsToMany(User::class,'ticket_user' ,'user_id', 'ticket_id')
        ->withPivot('message', 'response', 'status', 'created_by', 'updated_by');
    }
}
