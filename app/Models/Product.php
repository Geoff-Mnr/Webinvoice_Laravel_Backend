<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Document;
use Casts\CreatedByCast;
use Casts\UpdatedByCast;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'ean_code',
        'stock',
        'buying_price',
        'selling_price',
        'discount',
        'description',
        'comment',
        'status',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'product_documents', 'product_id', 'document_id');
    }
}
