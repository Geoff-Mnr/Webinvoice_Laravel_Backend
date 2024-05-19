<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Document;
use Casts\CreatedByCast;
use Casts\UpdatedByCast;

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

    

    public function documents()
    {
        return $this->hasMany(Document::class, 'documenttype_id');
    }
}
