<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Models\Tickets;
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
        $tickets = Tickets::orderBy('created_at', 'desc')->get();
        return $this->handleResponseNoPagination(TicketResource::collection($tickets), 'Tickets retrieved successfully',200);
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
            'status' => 'required',
        ]);  
        $input = $request->all();
        $input['created_by'] = Auth::user()->id;
        $ticket = Tickets::create($input);
        return $this->handleResponseNoPagination(new TicketResource($ticket), 'Ticket created successfully', 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tickets $tickets)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tickets $tickets)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tickets $tickets)
    {
        //
    }
}
