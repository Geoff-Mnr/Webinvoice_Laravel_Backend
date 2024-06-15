<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;


class Ticket extends Model
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


    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ticket_user')
            ->withPivot('message', 'response', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
