<?php

namespace App\Http\Controllers\API;


use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;

class CustomersController extends BaseController
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('perPage', 10);

        try {
            $query = Customer::where('user_id', auth()->user()->id)
                ->where(function($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('brand', 'like', "%$search%")
                        ->orWhere('ean_code', 'like', "%$search%")
                        ->orWhereHas('products', function($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        });
                });

            $customers = $query->paginate($perPage)->withQueryString();
            return $this->handleResponse(200, 'Customers fetched successfully', $customers);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'brand' => 'required',
                'ean_code' => 'required',
                'stock' => 'required',
                'buying_price' => 'required',
                'selling_price' => 'required',
                'discount' => 'required',
            ]);

            $customer = Customer::create($request->all());
            return $this->handleResponse(200, 'Customer created successfully', $customer);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        try {
            if ($customer->user_id == auth()->user()->id) {
                return $this->handleResponse(200, 'Customer retrieved successfully', $customer);
            } else {
                return $this->handleError('Customer not found', 400);
            }
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        try {
        $customer->update($request->all());
        return $this->handleResponse(200, 'Customer updated successfully', $customer);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        try {
            if ($customer->user_id == auth()->user()->id) {
                $customer->delete();
                return $this->handleResponse(200, 'Customer deleted successfully', $customer);
            } else {
                return $this->handleError('Customer not found', 400);
            } 
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }
    }
}
