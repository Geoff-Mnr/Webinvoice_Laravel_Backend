<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Documenttype;
use Casts\CreatedByCast;
use Casts\UpdatedByCast;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'documenttype_id',
        'reference_number',
        'document_date',
        'due_date',
        'price_htva',
        'price_vvac',
        'price_total',
        'status',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_by' => CreatedByCast::class,
        'updated_by' => UpdatedByCast::class,
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class, 'customer_id');
    }

    public function documenttypes()
    {
        return $this->hasMany(Documenttype::class, 'documenttype_id');
    }

}
