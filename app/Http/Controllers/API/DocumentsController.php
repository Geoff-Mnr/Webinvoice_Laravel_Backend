<?php

namespace App\Http\Controllers\API;


use App\Models\Document;
use App\Models\Documenttype;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class DocumentsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->q;
        $perPage = $request->input('perPage', 10);

        try {
            $query = Document::where('is_active', 1)
                ->with ('documenttype')
                ->where(function($query) use ($search) {
                    $query->where('reference_number', 'like', "%$search%")
                        ->orWhere('document_date', 'like', "%$search%")
                        ->orWhere('due_date', 'like', "%$search%")
                        ->orWhere('price_htva', 'like', "%$search%")
                        ->orWhere('price_vvac', 'like', "%$search%")
                        ->orWhere('price_total', 'like', "%$search%")
                        ->orWhere('status', 'like', "%$search%")
                        ->orWhereHas('documenttype', function($query) use ($search) {
                            $query->where('name', 'like', "%$search%")
                                ->orWhere('description', 'like', "%$search%");
                });
            });

            $documents = $query->paginate($perPage)->withQueryString();
            $documents->getCollection()->transform(function ($document) {
                return [
                    'id' => $document->id,
                    'customer_id' => $document->customer_id,
                    'product_id' => $document->product_id,
                    'documenttype' => $document->documenttype->name,
                    'reference_number' => $document->reference_number,
                    'due_date' => $document->due_date,
                    'price_htva' => $document->price_htva,
                    'price_vvac' => $document->price_vvac,
                    'price_total' => $document->price_total,
                    'status' => $document->status,
                    'created_by' => $document->created_by,
                    'updated_by' => $document->updated_by,
                    'created_at' => $document->created_at->format('Y-m-d'),
                    'updated_at' => $document->updated_at->format('Y-m-d'),
                ];
            });
            return $this->handleResponse('Documents fetched successfully', $documents);
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
                'documenttype_id' => 'required',
                'customer_id' => 'required',
                'product_id' => 'required',
                'reference_number' => 'required',
                'document_date' => 'required',
                'due_date' => 'required',
                'price_htva' => 'required',
                'price_vvac' => 'required',
                'price_total' => 'required',
            ]);

            $document = Document::create($request->all());
            return $this->handleResponseNoPagination('Document created successfully', $document);
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
            return $this->handleResponseNoPagination('Document updated successfully', $document, 200);
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
            return $this->handleResponse('Document deleted successfully', null, 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }   
    }

}
