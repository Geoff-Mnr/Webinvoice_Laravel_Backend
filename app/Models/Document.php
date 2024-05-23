<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Documenttype;


class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'documenttype_id',
        'customer_id', 
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

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function documenttype()
    {
        return $this->belongsTo(Documenttype::class);
    }

}
