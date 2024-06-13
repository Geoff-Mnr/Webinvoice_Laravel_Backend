<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Models\Ticket;
use App\Models\User;
use App\Http\Resources\TicketResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TicketsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $ticket = Ticket::with('users')->get();
        return $this->handleResponseNoPagination(TicketResource::collection($ticket), 'Tickets retrieved successfully',200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);  
        $input = $request->all();
        $input['created_by'] = Auth::user()->id;
        $input['status'] = $request->status ?? 'N';
        $input['is_active'] = $request->is_active ?? '1';

        $ticket = Ticket::create($input);

        $ticket->users()->attach(auth()->user()->id, [
            'message' => $request->message ?? 'No message',
            'response' => $request->response ?? 'No response',
            'status' => $request->status ?? 'N',
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id
        ]);
        
        return $this->handleResponseNoPagination(new TicketResource($ticket->load('users')), 'Ticket created successfully', 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tickets $tickets)
    {
        $ticket = Ticket::with['user']->find($tickets->id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        try {
            
            // Mise à jour des champs du ticket
            $input = $request->only(['title', 'description']);
            $input['updated_by'] = Auth::user()->id;
            
            if (!empty($input['title']) || !empty($input['description'])) {
                $ticket->update($input);
            }

            // Récupérer les valeurs actuelles de la table pivot pour l'utilisateur
            $currentPivot = $ticket->users()->where('user_id', auth()->user()->id)->first()->pivot;

            // Mise à jour des champs de la table pivot
            $ticket->users()->updateExistingPivot(auth()->user()->id, [
                'message' => $request->message ?? $currentPivot->message,
                'response' => $request->response ?? $currentPivot->response,
                'status' => $request->status ?? $currentPivot->status,
                'updated_by' => auth()->user()->id
            ]);

            // Charger les utilisateurs associés au ticket avant de renvoyer la réponse
            return $this->handleResponseNoPagination(new TicketResource($ticket->load('users')), 'Ticket updated successfully', 200);
        } catch (ValidationException $e) {
            return $this->handleError($e->errors(), 422); // Unprocessable Entity for validation errors
        } catch (\Exception $e) {
            return $this->handleError('An error occurred while updating the ticket', 500); // Internal Server Error
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        try {
            $tickets->delete();
            return $this->handleResponseNoPagination(new TicketResource($tickets), 'Ticket deleted successfully', 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }
}
