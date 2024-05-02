<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RolesController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $roles = Role::all();
            return $this->handleResponse(200, 'Roles fetched successfully', $roles);
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
                'name' => 'required',
                'description' => 'required',
            ]);

            $role = Role::create($request->all());
            return $this->handleResponse(201, 'Role created successfully', $role);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        try{
            $role = Role::where('id', $role->id)->with('users')->first();
            if ($role) {
                return $this->handleResponseNoPagination('Role retrieved successfully', $role, 200);
            } else {
                return $this->handleError('Role not found', 404);
            } 
            } catch (\Exception $e) {
                return $this->handleError($e->getMessage(), 400);
            }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        try {
            $request->validate([
                'name' => 'required',
                'description' => 'required',
            ]);

            $role->update($request->all());
            return $this->handleResponse('Role updated successfully', $role, 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(),400);
        }
    }

}
