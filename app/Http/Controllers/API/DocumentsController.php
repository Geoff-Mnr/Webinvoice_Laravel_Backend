<?php

namespace App\Http\Controllers\API;


use App\Models\Document;
use App\Models\Documenttype;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use carbon\carbon;
use Illuminate\Support\Facades\Log;

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
            $query = Document::where('user_id', auth()->user()->id)
                ->with ('documenttype')
                ->where(function($query) use ($search) {
                    $query->where('reference_number', 'like', "%$search%")
                        ->orWhere('document_date', 'like', "%$search%")
                        ->orWhere('due_date', 'like', "%$search%")
                        ->orWhere('price_htva', 'like', "%$search%")
                        ->orWhere('price_vvat', 'like', "%$search%")
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
                    'product' => $document->products,
                    'documenttype_id' => $document->documenttype_id,
                    'documenttype' => $document->documenttype->name,
                    'reference_number' => $document->reference_number,
                    'document_date' => Carbon::parse($document->document_date)->format('d/m/Y'),
                    'due_date' => Carbon::parse($document->due_date)->format('d/m/Y'),
                    'price_htva' => $document->price_htva,
                    'price_vvat' => $document->price_vvat,
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
                'product_id' => 'required|array', 
                'due_date' => 'required',
                'document_date' => 'required',
                'price_htva' => 'required',
                'price_vvat' => 'required',
            ]);
            $request['user_id'] = auth()->user()->id;
            $request['reference_number'] = $this->generateReferenceNumber($request['documenttype_id']);
            $request['created_by'] = auth()->user()->id;
            $documentData = $request->except('product_id');
            $document = Document::create($documentData);

            $notFoundProducts = [];
            foreach ($request->product_id as $productId) {
                $product = Product::find($productId); 
                Log::info("Product ID: $productId");
                if ($product && $product->id) {
                    $document->products()->attach($product->id, [
                        'selling_price' => $product->selling_price ?? 0,
                        'buying_price' => $product->buying_price ?? 0,
                        'quantity' => $product->quantity ?? 0,
                        'price_htva' => $product->price_htva ?? 0,
                        'price_vvat' => $product->price_vvat ?? 0,
                        'price_total' => $product->price_total ?? 0,
                        'discount' => $product->discount ?? 0,
                        'margin' => $product->margin ?? 0,
                        'comment' => $product->comment,
                        'description' => $product->description,
                        'status' => 'N',
                        'is_active' => true,
                        'created_by' => auth()->user()->id,
                        'updated_by' => null,
                        'created_at' => Carbon::now(),
                        'updated_at' => null,
                    ]);
                }
                else {
                    $notFoundProducts[] = $productId;
                }
            }

            if (!empty($notFoundProducts)) {
                return $this->handleError('The following products were not found: ' . implode(', ', $notFoundProducts), 400);
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
            $document = Document::where('user_id', auth()->user()->id)->where('id', $document->id)->with('products')->first();
            return $this->handleResponseNoPagination(200, 'Document retrieved successfully', $document, 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'documenttype_id' => 'required',
                'customer_id' => 'required',
                'product_id' => 'required|array', 
                'due_date' => 'required',
                'document_date' => 'required',
                'price_htva' => 'required',
                'price_vvat' => 'required',
            ]);

            $document = Document::findOrfail($id);
            $documentData = $request->except('product_id');
            $document->update($documentData);
            $document->products()->detach();
            $notFoundProducts = [];
            foreach ($request->product_id as $productId) {
                $product = Product::find($productId); // Change this line
                if ($product) {
                    $product->documents()->attach($document->id);
                } else {
                    $notFoundProducts[] = $productId;
                }
            }

            if (!empty($notFoundProducts)) {
                return $this->handleError('The following products were not found: ' . implode(', ', $notFoundProducts), 400);
            }
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


    /**
     * Generate a reference number for a document
     */
    
    private function generateReferenceNumber($documenttype_id){
        $prefix = $documenttype_id == 1 ? 'FACT-' : 'DOCU-';
     $lastDocument = Document::where('reference_number', 'like', $prefix . '%')
                        ->orderBy('reference_number', 'desc')
                        ->first();
        if ($lastDocument) {
            $lastNumber = intval(str_replace($prefix, '', $lastDocument->reference_number));
        } else {
            $lastNumber = 0;
        }
        return $prefix . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }
}
