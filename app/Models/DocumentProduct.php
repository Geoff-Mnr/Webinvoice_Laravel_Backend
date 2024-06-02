<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class DocumentProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'document_id',
        'product_id',
        'quantity',
        'buying_price', 
        'selling_price',
        'price_htva',
        'price_vvat',
        'price_total',
        'discount',
        'margin',
        'comment',
        'description',
        'status',
        'created_by',
        'updated_by',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }   
}
