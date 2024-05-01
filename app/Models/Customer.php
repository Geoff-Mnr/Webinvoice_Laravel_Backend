<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;
use Casts\CreatedByCast;
use Casts\UpdatedByCast;


class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
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

    protected $casts = [
        'created_by' => CreatedByCast::class,
        'updated_by' => UpdatedByCast::class,
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'user_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'product_id');
    }
    
}