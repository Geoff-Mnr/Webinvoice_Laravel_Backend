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
        'user_id',
        'name',
        'brand',
        'ean_code',
        'quantity',
        'buying_price',
        'selling_price',
        'margin', 
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
        return $this->belongsToMany(Document::class, 'document_product', 'product_id', 'document_id')
        ->withPivot('quantity', 'price_htva', 'price_vvat', 'price_total', 'discount', 'margin', 'comment', 'description', 'status', 'is_active', 'created_by', 'updated_by');
    }
 
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
