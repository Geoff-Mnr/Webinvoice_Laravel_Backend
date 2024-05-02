<?php

namespace App\Http\Controllers\API;


use App\Models\Document;
use App\Models\Documenttype;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;

class DocumentsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('perPage', 10);

        try {
            $query = Document::where('user_id', auth()->user()->id)
                ->where(function($query) use ($search) {
                    $query->where('reference_number', 'like', "%$search%")
                        ->orWhere('document_date', 'like', "%$search%")
                        ->orWhere('due_date', 'like', "%$search%")
                        ->orWhere('price_htva', 'like', "%$search%")
                        ->orWhere('price_vvac', 'like', "%$search%")
                        ->orWhere('price_total', 'like', "%$search%")
                        ->orWhere('status', 'like', "%$search%")
                        ->orWhereHas('documenttypes', function($query) use ($search) {
                            $query->where('name', 'like', "%$search%");
                        });
                });

            $documents = $query->paginate($perPage)->withQueryString();
            return $this->handleResponse(200, 'Documents fetched successfully', $documents);
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
                'customer_id' => 'required',
                'documenttype_id' => 'required',
                'reference_number' => 'required',
                'document_date' => 'required',
                'due_date' => 'required',
                'price_htva' => 'required',
                'price_vvac' => 'required',
                'price_total' => 'required',
            ]);

            $document = Document::create($request->all());
            return $this->handleResponse(200, 'Document created successfully', $document, 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        try {
            return $this->handleResponse(200, 'Document retrieved successfully', $document, 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        try {
            $document->update($request->all());
            return $this->handleResponse(200, 'Document updated successfully', $document, 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        try {
            $document->delete();
            return $this->handleResponse(200, 'Document deleted successfully', null, 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }   
    }
}
