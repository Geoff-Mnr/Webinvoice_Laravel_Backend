<?php

namespace App\Http\Controllers\API;


use App\Models\Document;
use App\Models\Documenttype;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use carbon\carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\DocumentResource;

class DocumentsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->q;
        $customer_id = $request->customer_id;
        $documenttype_id = $request->documenttype_id;
        $product_id = $request->product_id;
        $perPage = $request->input('per_page', 10);

        try {
            $query = Document::where('user_id', auth()->user()->id)
                ->with ([ 'customer', 'documenttype', 'products' ])
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
            return $this->handleResponse(DocumentResource::collection($documents),'Documents retrieved successfully', 200);
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
            // Validation des champs requis
            $request->validate([
                'documenttype_id' => 'required',
                'customer_id' => 'required',
                'selectedProducts' => 'required|array', // Validation du tableau de produits sélectionnés
                'due_date' => 'required',
                'document_date' => 'required',
                'price_htva' => 'required',
                'price_vvat' => 'required',
            ]);

            // Ajout des informations de l'utilisateur et génération du numéro de référence
            $request['user_id'] = auth()->user()->id;
            $request['reference_number'] = $this->generateReferenceNumber($request['documenttype_id']);
            $request['created_by'] = auth()->user()->id;
            $documentData = $request->except('selectedProducts');
            
            // Création du document
            $document = Document::create($documentData);

            $notFoundProducts = [];
            foreach ($request->selectedProducts as $selectedProduct) {
                $product = Product::find($selectedProduct['product_id']); 
                Log::info("Product ID: {$selectedProduct['product_id']}");
                if ($product && $product->id) {
                    $document->products()->attach($product->id, [
                        'selling_price' => $product->selling_price ?? 0,
                        'buying_price' => $product->buying_price ?? 0,
                        'quantity' => $selectedProduct['quantity'] ?? 0,
                        'price_total' => $selectedProduct['price_total'] ?? 0,
                        'discount' => $selectedProduct['discount'] ?? 0,
                        'margin' => $product->margin ?? 0,
                        'comment' => $product->comment,
                        'description' => $product->description,
                        'status' => 'N',
                        'is_active' => true,
                        'created_by' => auth()->user()->id,
                        'updated_by' => null,
                        'created_at' => now(),
                        'updated_at' => null,
                    ]);
                } else {
                    $notFoundProducts[] = $selectedProduct['product_id'];
                }
            }

            // Gestion des produits non trouvés
            if (!empty($notFoundProducts)) {
                return $this->handleError('The following products were not found: ' . implode(', ', $notFoundProducts), 400);
            }
            
            return $this->handleResponseNoPagination('Document created successfully', $document, 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
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
    public function update(Request $request, $documentId)
    {
        try {
            // Validation des champs requis
            $request->validate([
                'documenttype_id' => 'required',
                'customer_id' => 'required',
                'selectedProducts' => 'required|array', // Validation du tableau de produits sélectionnés
                'due_date' => 'required',
                'document_date' => 'required',
                'price_htva' => 'required',
                'price_vvat' => 'required',
            ]);

            // Récupération du document
            $document = Document::findOrFail($documentId);
            
            // Mise à jour des informations du document
            $document->update($request->except('selectedProducts'));

            // Préparation des données pour sync
            $productSyncData = [];
            foreach ($request->selectedProducts as $selectedProduct) {
                $productSyncData[$selectedProduct['product_id']] = [
                    'selling_price' => $selectedProduct['selling_price'] ?? 0,
                    'buying_price' => $selectedProduct['buying_price'] ?? 0,
                    'quantity' => $selectedProduct['quantity'] ?? 0,
                    'price_total' => $selectedProduct['price_total'] ?? 0,
                    'discount' => $selectedProduct['discount'] ?? 0,
                    'margin' => $selectedProduct['margin'] ?? 0,
                    'comment' => $selectedProduct['comment'] ?? null,
                    'description' => $selectedProduct['description'] ?? null,
                    'status' => $selectedProduct['status'] ?? 'N',
                    'is_active' => $selectedProduct['is_active'] ?? true,
                    'created_by' => $product->pivot->created_by ?? auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Synchronisation des produits dans la table pivot
            $document->products()->sync($productSyncData);

            return $this->handleResponseNoPagination('Document updated successfully', $document, 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
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
                        ->where('user_id', auth()->user()->id)
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
