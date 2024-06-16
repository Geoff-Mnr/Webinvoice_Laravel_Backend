<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Casts\CreatedByCast;
use App\Casts\UpdatedByCast;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'status',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
