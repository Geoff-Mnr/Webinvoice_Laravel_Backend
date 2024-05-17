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
        'company_name',
        'email',
        'phone_number',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_zip_code',
        'billing_country',
        'website',
        'vat_number',
        'status',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_by' => \App\Casts\CreatedByCast::class,
        'updated_by' => \App\Casts\UpdatedByCast::class,
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'user_id');
    }
    
}
