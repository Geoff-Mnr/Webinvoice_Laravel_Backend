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
    public function index(Request $request)
    {
        $search = $request->q;
        $perPage = $request->input('perPage', 10);

        try {
            $query = Product::when($search, function($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('brand', 'like', "%$search%")
                        ->orWhere('ean_code', 'like', "%$search%");
                });

            $products = $query->paginate($perPage)->withQueryString();
            $products->getCollection()->transform(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'brand' => $product->brand,
                    'ean_code' => $product->ean_code,
                    'stock' => $product->stock,
                    'buying_price' => $product->buying_price,
                    'selling_price' => $product->selling_price,
                    'discount' => $product->discount,
                    'comment' => $product->comment,
                    'description' => $product->description,
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                ];
            });
            return $this->handleResponse('Products fetched successfully', $products);
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
            return $this->handleResponseNoPagination('Product created successfully', $product);
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
            return $this->handleResponseNoPagination('Product updated successfully', $product);
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

    public function ListProducts()
    {
        try {
            $products = Product::all();
            return $this->handleResponseNoPagination('Products fetched successfully', $products, 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }
    }
}
