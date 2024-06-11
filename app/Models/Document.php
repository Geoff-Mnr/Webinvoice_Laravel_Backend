<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Documenttype;
use App\Models\DocumentProduct;


class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'documenttype_id',
        'customer_id',
        'reference_number',
        'document_date',
        'due_date',
        'price_htva',
        'price_vvat',
        'price_tvac',
        'price_total',
        'status',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function documenttype()
    {
        return $this->belongsTo(Documenttype::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products() : BelongsToMany
    {
        return $this->belongsToMany(Product::class)
        ->withPivot('selling_price', 'quantity', 'price_total' ,'discount', 'margin',  'comment', 'description');
    }

}
