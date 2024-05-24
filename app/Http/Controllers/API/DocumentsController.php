<?php

namespace App\Http\Controllers\API;


use App\Models\Document;
use App\Models\Documenttype;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use carbon\carbon;

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
                    'customer' => $document->customer->company_name,
                    'product_id' => $document->product_id,
                    'product' => $document->products->pluck('name'),
                    'documenttype_id' => $document->documenttype_id,
                    'documenttype' => $document->documenttype->name,
                    'reference_number' => $document->reference_number,
                    'document_date' => Carbon::parse($document->document_date)->format('d/m/Y'),
                    'due_date' => Carbon::parse($document->due_date)->format('d/m/Y'),
                    'price_htva' => $document->price_htva,
                    'price_vvac' => $document->price_vvac,
                    'price_total' => $document->price_total,
                    'status' => $document->status,
                    'created_by' => $document->created_by,
                    'updated_by' => $document->updated_by,
                    'created_at' => Carbon::parse($document->created_at)->format('d/m/Y H:i:s'),
                    'updated_at' => Carbon::parse($document->updated_at)->format('d/m/Y H:i:s'),
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
                'due_date' => 'required',
                'document_date' => 'required',
                'price_htva' => 'required',
                'price_vvac' => 'required',
                'price_total' => 'required',
            ]);

            $document = Document::create($request->all());

            foreach ($request->product_id as $productId) {
                $product = Product::find($request->product_id);
                    if ($product) {
                    $product->documents()->attach($document->id);
                    } else {
                        return $this->handleError('Product not found', 404);
                    }
            }
            return $this->handleResponseNoPagination('Document created successfully', $document, 200);
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

            $document->products()->sync($request->product_id);
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
