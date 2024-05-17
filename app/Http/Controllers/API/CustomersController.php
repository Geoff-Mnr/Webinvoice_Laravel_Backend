<?php

namespace App\Http\Controllers\API;


use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class CustomersController extends BaseController
{
    

    public function index(Request $request)
    {
        $search = $request->q;
        $perPage = $request->input('perPage', 10);

        try {
            $query = Customer::where('user_id', auth()->user()->id)
                ->when($search, function ($query) use ($search) {
                    return $query->where('company_name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });

            $customers = $query->paginate($perPage)->withQueryString();

            return $this->handleResponse('Customers fetched successfully', $customers);
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
                'company_name' => 'required',
                'email' => 'required',
            ]);
            $request['user_id'] = auth()->user()->id;
            $customer = Customer::create($request->all());
            return $this->handleResponseNoPagination('Customer created successfully', $customer);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $customer = Customer::where('user_id', auth()->user()->id)->find($id);
            if ($customer) {
                return $this->handleResponseNoPagination('Customer fetched successfully', 200, $customer);
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
            return $this->handleResponseNoPagination('Customer updated successfully', $customer, 200);
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
