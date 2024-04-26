<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
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

    public function customers()
    {
        return $this->hasMany(Customer::class, 'customer_id');
    }

    public function documenttypes()
    {
        return $this->hasMany(Documenttype::class, 'documenttype_id');
    }

}
