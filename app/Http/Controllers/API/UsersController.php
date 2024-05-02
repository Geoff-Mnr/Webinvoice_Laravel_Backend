<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;



class UsersController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->q;
        $perPage = $request->query('perPage', 10);
        try {
            $users = User::where('username', 'LIKE', "%$search%")
                ->orWhere('first_name', 'LIKE', "%$search%")
                ->orWhere('last_name', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%")
                ->orWhere('phone_number', 'LIKE', "%$search%")
                ->orWhere('address', 'LIKE', "%$search%")
                ->orWhere('city', 'LIKE', "%$search%")
                ->orWhere('Countries', 'LIKE', "%$search%")
                ->orWhere(function ($query) use ($search) {
                    $query->whereHas('roles', function ($query) use ($search) {
                        $query->where('name', 'LIKE', "%$search%");
                    });
                })
                ->paginate($perPage)->withQueryString()
                ->with('roles')
                ->get();

            return $this->sendResponse($users, 'Users retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Server Error.', $e->getMessage(), 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'role_id' => ['required', 'exists:roles,id'],
                'username' => ['required', 'unique:users'],
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'confirm_password' => 'required|same:password',
            ]);

            $input ['password'] = bcrypt($request->password);

            $user = User::create($request->all());
            return $this->handleResponse(200, 'User created successfully', $user);
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
            $user = User::where('id', $id)->with('roles')->first();
            if ($user) {
                return $this->handleResponseNoPagination($user, 'User retrieved successfully.');
            } else {
                return $this->handleError('User not found.', 400);
            }
            } catch (\Exception $e) {
                return $this->handleError($e->getMessage(), 400);
            }
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        try {
            $user = User::find($id);
            if ($user) {
                $request->validate([
                    'role_id' => ['required', 'exists:roles,id'],
                    'username' => ['required', 'unique:users,username,' . $id],
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|email',
                    'password' => 'required',
                    'confirm_password' => 'required|same:password',
                ]);
    
                $input = $request->all();
                $input['password'] = bcrypt($request->password);
    
                $user->update($input);
                return $this->handleResponse(200, 'User updated successfully', $user);
            } else {
                return $this->handleError('User not found.', 400);
            }
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::find($id);
            if ($user) {
                $user->delete();
                return $this->handleResponse(200, 'User deleted successfully', $user);
            } else {
                return $this->handleError('User not found.', 400);
            }
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    public function restore(string $id)
    {
        try {
            $user = User::withTrashed()->find($id);
            if ($user) {
                $user->restore();
                return $this->handleResponse(200, 'User restored successfully', $user);
            } else {
                return $this->handleError('User not found.', 400);
            }
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }






}
