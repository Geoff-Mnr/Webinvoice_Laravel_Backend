<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class ProductsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->query('search');
        $perPage = request()->query('perPage', 10);

        try {
            $query = Product::where('user_id', auth()->user()->id)
                ->where(function($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('brand', 'like', "%$search%")
                        ->orWhere('ean_code', 'like', "%$search%");
                });

            $products = $query->paginate($perPage)->withQueryString();
            return $this->handleResponse(200, 'Products fetched successfully', $products);
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

            $product = Product::create($request->all());
            return $this->handleResponse(201, 'Product created successfully', $product);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        try {
            return $this->handleResponse(200, 'Product retrieved successfully', $product);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {
            $product->update($request->all());
            return $this->handleResponse(200, 'Product updated successfully', $product);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return $this->handleResponse(200, 'Product deleted successfully');
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }
    }
}
