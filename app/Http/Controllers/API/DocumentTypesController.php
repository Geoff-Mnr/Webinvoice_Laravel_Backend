<?php

namespace App\Http\Controllers\API;

use App\Models\Documenttype;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class DocumentTypesController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('perPage', 10);
    
        try {
            $query = Documenttype::where('is_active', 1)
                ->where(function($query) use ($search) {
                    $query->where('reference', 'like', "%$search%")
                        ->orWhere('name', 'like', "%$search%");
                });
    
            $documenttypes = $query->paginate($perPage)->withQueryString();
            return $this->handleResponse('Document types fetched successfully', $documenttypes);
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
                'reference' => 'required',
                'name' => 'required',
                'description' => 'required',
                'status' => 'required',
            ]);

            $documenttype = Documenttype::create($request->all());
            return $this->handleResponseNoPagination(200, 'Document type created successfully', $documenttype);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Documenttype $documenttype)
    {
        try {
            return $this->handleResponse(200, 'Document type fetched successfully', $documenttype);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }   
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Documenttype $documenttype)
    {
        try {
            $documenttype->update($request->all());
            return $this->handleResponseNoPagination('Document type updated successfully', $documenttype);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Documenttype $documenttype)
    {
        try {
            $documenttype->update(['is_active' => 0]);
            return $this->handleResponse(200, 'Document type deleted successfully', $documenttype);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }
    }
}
