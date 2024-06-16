<?php

namespace App\Http\Controllers\API;

use App\Models\Documenttype;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Resources\DocumentTypeResource;

class DocumentTypesController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->q;
        $perPage = $request->input('per_page', 10);
        // Afficher les types de documents de l'utilisateur connecté par page
        try {
            $documenttypes = Documenttype::where('name', 'LIKE', "%$search%");
            $documenttypes = $documenttypes->paginate($perPage)->withQueryString();
            return $this->handleResponse(DocumentTypeResource::collection($documenttypes), 'Document types retrieved successfully', 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Valider les données de la requête
        try {
            $request->validate([
                'name' => 'required',
            ]);
            // Ajouter l'ID de l'utilisateur connecté
            $request['user_id'] = auth()->user()->id;
            // Générer une référence pour le type de document
            $request['reference'] = $this->generateReference();
            // Vérifier si le statut est actif ou inactif
            $request['is_active'] = $request['status'] === 'Actif' ? 'A' : 'I';

            // Créer un nouveau type de document
            $documenttype = Documenttype::create($request->all());
            return $this->handleResponseNoPagination('Document type created successfully', $documenttype);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
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
            return $this->handleError($e->getMessage(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Documenttype $documenttype)
    {
        try {
            $request->validate([
                'name' => 'required',
            ]);
            // Vérifier si le statut est actif ou inactif
            $request['is_active'] = $request['status'] === 'Actif' ? 'A' : 'I';
            // Mettre à jour le type de document
            $documenttype->update($request->all());
            return $this->handleResponseNoPagination('Document type updated successfully', $documenttype);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Documenttype $documenttype)
    {
        try {
            // Supprimer le type de document
            $documenttype->delete();
            return $this->handleResponseNoPagination('Document type deleted successfully', $documenttype);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }


    /**
     * List all document types
     */
    public function ListDocumentTypes()
    {
        try {
            // Récupérer tous les types de documents actifs
            $documentTypes = Documenttype::where('is_active', 'A')->get();
            return $this->handleResponseNoPagination(DocumentTypeResource::collection($documentTypes), 'Document types retrieved successfully', 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    /**
     * Generate reference 
     */
    public function generateReference()
    {
        // Récupérer le dernier type de document
        $lastDocumentType = Documenttype::latest()->first();
        // Récupérer l'ID du dernier type de document
        $lastDocumentTypeId = $lastDocumentType ? $lastDocumentType->id : 0;
        // Générer une référence pour le type de document
        return 'TDOC-' . str_pad($lastDocumentTypeId + 1, 2, '0', STR_PAD_LEFT);
    }
}
