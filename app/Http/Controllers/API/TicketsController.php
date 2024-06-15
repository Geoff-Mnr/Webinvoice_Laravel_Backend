<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Models\Ticket;
use App\Models\User;
use App\Http\Resources\TicketResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class TicketsController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tickets = Ticket::with(['users', 'createdBy'])->orderBy('created_at', 'desc')->get();

        return $this->handleResponseNoPagination(TicketResource::collection($tickets), 'Tickets retrieved successfully', 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
            ]);

            $input = $request->all();
            $input['created_by'] = Auth::user()->id;
            $input['status'] = $request->status ?? 'N';
            $input['is_active'] = $request->is_active ?? '1';

            $ticket = Ticket::create($input);

            // Attacher l'utilisateur authentifié au ticket dans la table pivot
            $ticket->users()->attach(Auth::user()->id, [
                'message' => 'Ticket créé',
                'response' => '',
                'status' => $input['status'],
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            // Charger les relations après la création du ticket
            $ticket->load('users');

            return $this->handleResponseNoPagination(new TicketResource($ticket), 'Ticket created successfully', 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Ticket $tickets)
    {
        $ticket = Ticket::with('users')->find($tickets->id);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        try {
            // Valider les données entrantes
            $request->validate([
                'title' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'message' => 'nullable|string|max:500',
                'response' => 'nullable|string|max:500',
                'status' => 'nullable|string|in:N,O,C', // Assuming N, O, C are valid status codes
            ]);

            // Récupérer l'utilisateur authentifié
            $user = Auth::user();

            // Mise à jour des champs du ticket
            $input = $request->only(['title', 'description']);
            $input['updated_by'] = $user->id;

            if (!empty($input['title']) || !empty($input['description'])) {
                $ticket->update($input);
            }

            // Vérifier si l'utilisateur est déjà associé au ticket
            $currentPivot = $ticket->users()->where('user_id', $user->id)->first();

            if ($currentPivot) {
                // Mise à jour des champs de la table pivot pour l'utilisateur authentifié
                $ticket->users()->updateExistingPivot($user->id, [
                    'message' => $request->input('message', $currentPivot->pivot->message),
                    'response' => $request->input('response', $currentPivot->pivot->response),
                    'status' => $request->input('status', $currentPivot->pivot->status),
                    'updated_by' => $user->id,
                    'updated_at' => now(),
                ]);
            } else {
                // Associer l'utilisateur au ticket si non associé
                $ticket->users()->attach($user->id, [
                    'message' => $request->input('message', 'No message'),
                    'response' => $request->input('response', 'No response'),
                    'status' => $request->input('status', 'N'),
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                    'updated_at' => now(),
                ]);
            }

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
            $ticket->delete();
            return $this->handleResponseNoPagination(new TicketResource($ticket), 'Ticket deleted successfully', 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    public function getTicketsByUser(Request $request)
    {
        try {
            $user = Auth::user();
            $tickets = Ticket::where("created_by", $user->id)
                ->with(['users' => function ($query) {
                    $query->orderBy('ticket_user.created_at', 'asc');
                }])
                ->orderBy('created_at', 'desc')
                ->get();

            return $this->handleResponseNoPagination(TicketResource::collection($tickets), 'Tickets retrieved successfully', 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    public function addMessage(Request $request, Ticket $ticket)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:500',
            ]);

            $user = Auth::user();

            // Ajouter un nouveau message dans la table pivot
            $ticket->users()->attach($user->id, [
                'message' => $request->input('message'),
                'response' => $request->input('response', 'No response'),
                'status' => $request->input('status', 'N'),
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Recharger le ticket avec les utilisateurs et ordonner par la date de création
            $ticket->load(['users' => function ($query) {
                $query->orderBy('ticket_user.created_at', 'asc');
            }]);

            return $this->handleResponseNoPagination(new TicketResource($ticket), 'Message ajouté au ticket avec succès', 200);
        } catch (\Exception $e) {
            return $this->handleError('Une erreur s\'est produite lors de l\'ajout du message au ticket', 500);
        }
    }
}
