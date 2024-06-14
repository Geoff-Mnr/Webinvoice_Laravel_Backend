<?php

namespace App\Http\Controllers\API;


use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Resources\CustomerResource;

class CustomersController extends BaseController
{
    

    public function index(Request $request)
    {
        $search = $request->q;
        $perPage = $request->input('per_page', 10);
        
        try {
            $customers = Customer::where('user_id', auth()->user()->id)
                ->when($search, function($query) use ($search) {
                    $query->where('company_name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone_number', 'like', "%$search%")
                        ->orWhere('billing_address', 'like', "%$search%")
                        ->orWhere('billing_city', 'like', "%$search%")
                        ->orWhere('billing_state', 'like', "%$search%")
                        ->orWhere('billing_zip_code', 'like', "%$search%")
                        ->orWhere('billing_country', 'like', "%$search%")
                        ->orWhere('website', 'like', "%$search%")
                        ->orWhere('vat_number', 'like', "%$search%")
                        ->orWhere('status', 'like', "%$search%")
                        ->orWhere('is_active', 'like', "%$search%");
                });

            
            $customers = $customers->paginate($perPage)->withQueryString();
            dd($customers);
            return $this->handleResponse(CustomerResource::collection($customers), 'Customers retrieved successfully', 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 500);
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
            $request['user_id'] = $request->user()->id;
            $request['status'] === 'Actif' ? 'A' : 'I';
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
                $customerDate = [
                    'id' => $customer->id,
                    'company_name' => $customer->company_name,
                    'email' => $customer->email,
                    'phone_number' => $customer->phone_number,
                    'billing_address' => $customer->billing_address,
                    'billing_city' => $customer->billing_city,
                    'billing_state' => $customer->billing_state,
                    'billing_zip_code' => $customer->billing_zip_code,
                    'billing_country' => $customer->billing_country,
                    'website' => $customer->website,
                    'vat_number' => $customer->vat_number,
                    'status' => $customer->status,
                    'is_active' => $customer->is_active,
                    'created_by' => $customer->created_by,
                    'updated_by' => $customer->updated_by,
                    'created_at' => $customer->created_at,
                    'updated_at' => $customer->updated_at,
                ];
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
            $request['is_active'] = $request['status'] === 'Actif' ? 'A' : 'I';
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

    public function ListCustomers()
    {
        try {
            $customers = Customer::where('user_id', auth()->user()->id)->get();
            return $this->handleResponseNoPagination(CustomerResource::collection($customers), 'Customers retrieved successfully', 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 500);
        }
    }
}
