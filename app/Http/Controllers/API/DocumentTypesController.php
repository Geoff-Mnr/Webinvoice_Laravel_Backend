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
        $search = $request->q;
        $perPage = $request->input('perPage', 10);
    
        try {
            $query = Documenttype::where('is_active', 'A')
                ->where(function($query) use ($search) {
                    $query->where('reference', 'like', "%$search%")
                        ->orWhere('name', 'like', "%$search%");
                });
    
            $documenttypes = $query->paginate($perPage)->withQueryString();
            $documenttypes->getCollection()->transform(function ($documenttype) {
                return [
                    'id' => $documenttype->id,
                    'reference' => $documenttype->reference,
                    'name' => $documenttype->name,
                    'status' => $documenttype->status == 'A' ? 'Actif' : 'Inactif',
                    'description' => $documenttype->description,
                    'created_by' => $documenttype->created_by,
                    'updated_by' => $documenttype->updated_by,
                    'created_at' => $documenttype->created_at->format('Y-m-d'),
                    'updated_at' => $documenttype->updated_at->format('Y-m-d'),
                ];
            });
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
                'name' => 'required',
            ]);
            $request['user_id'] = auth()->user()->id;
            $request['reference'] = $this->generateReference();
            $request['is_active'] = $request['status'] === 'Actif' ? 'A' : 'I';
            $documenttype = Documenttype::create($request->all());
            return $this->handleResponseNoPagination('Document type created successfully', $documenttype);
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
            $documenttype = Documenttype::where('user_id', auth()->user()->id)->where('id', $id)->first();
            return $this->handleResponse('Document type fetched successfully', $documenttype);
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
            $request['is_active'] = $request['status'] === 'Actif' ? 'A' : 'I';
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


    /**
     * List all document types
     */
    public function ListDocumentTypes()
    {
        try {
            $documentTypes = Documenttype:: where('is_active', 'A')->get();
            return $this->handleResponseNoPagination('Document types fetched successfully', $documentTypes, 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }
    }

    /**
     * Generate reference 
     */
    public function generateReference()
    {
        $lastDocumentType = Documenttype::latest()->first();
        $lastDocumentTypeId = $lastDocumentType ? $lastDocumentType->id : 0;
        return 'TDOC-' . str_pad($lastDocumentTypeId + 1, 2, '0', STR_PAD_LEFT);
    }
}
