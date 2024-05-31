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
            $query = Document::where('user_id', auth()->user()->id)
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
                'product_id' => 'required|array', 
                'due_date' => 'required',
                'document_date' => 'required',
                'price_htva' => 'required',
                'price_vvac' => 'required',
            ]);
            $request['user_id'] = auth()->user()->id;
            $request['reference_number'] = $this->generateReferenceNumber($request['documenttype_id']);
            $documentData = $request->except('product_id');
            $vat_rate = $request['price_vvac'];
            $vat_amount = $request['price_htva'] * $vat_rate / 100;

            $documentData['price_total'] = $request['price_htva'] + $vat_amount;
            $document = Document::create($documentData);

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
                'price_vvac' => 'required',
            ]);

            $document = Document::findOrfail($id);
            $documentData = $request->except('product_id');

            $vat_rate = $request['price_vvac'];
            $vat_amount = $request['price_htva'] * $vat_rate / 100;
            $documentData['price_total'] = $request['price_htva'] + $vat_amount;


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
        $prefix = 'DOCU-';
    
        if($documenttype_id == 1) { // Assuming 1 is the id for invoices
            $prefix = 'FACT-';
        }
        
        $documents = Document::where('user_id', auth()->user()->id)
                            ->where('reference_number', 'like', $prefix . '%')
                            ->get();
    
        $lastNumber = 0;
        foreach ($documents as $document) {
            $number = intval(str_replace($prefix, '', $document->reference_number));
            if ($number > $lastNumber) {
                $lastNumber = $number;
            }
        }
    
        return $prefix . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }
}

