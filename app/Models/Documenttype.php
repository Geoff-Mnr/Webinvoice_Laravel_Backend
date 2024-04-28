<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documenttype extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'name',
        'description',
        'status',
        'is_active',
        'created_by',
        'updated_by',
    ];
}
