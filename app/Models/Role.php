<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Casts\CreatedByCast;
use Casts\UpdatedByCast;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'name',
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

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

}
