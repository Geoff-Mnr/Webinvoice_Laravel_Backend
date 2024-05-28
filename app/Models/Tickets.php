<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Casts\CreatedByCast;
use Casts\UpdatedByCast;

class Tickets extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'comment',
        'description',
        'status',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_by' => CreatedByCast::class,
        'updated_by' => UpdatedByCast::class,
    ];

    public function user()
    {
        return $this->belongsToMany(User::class,'ticket_user', 'ticket_id', 'user_id')
        ->withPivot('message', 'response', 'status', 'created_by', 'updated_by');
    }
}
