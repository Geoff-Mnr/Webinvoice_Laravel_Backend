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

    public function customers()
    {
        return $this->belongsTo(Customer::class);
    }

    public function documenttypes()
    {
        return $this->belongsTo(Documenttype::class);
    }

}
