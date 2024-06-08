<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\UserResource;



class UsersController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->q;
        $role_id = $request->role_id;
        $perPage = $request->query('perPage', 10);
        try {
            $query = User::with('role')
                ->where(function ($query) use ($search) {
                    $query->where('username', 'like', "%$search%")
                        ->orWhere('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone_number', 'like', "%$search%")
                        ->orWhere('address', 'like', "%$search%")
                        ->orWhere('city', 'like', "%$search%")
                        ->orWhere('country', 'like', "%$search%")
                        ->orWhere('zip_code', 'like', "%$search%")
                        ->orWhereHas('role', function ($query) use ($search) {
                            $query->where('name', 'like', "%$search%")
                                ->orWhere('description', 'like', "%$search%");
                        });
                });
            if ($role_id) {
                $query->where('role_id', $role_id);
            }
            $users = $query->paginate($perPage)->withQueryString();
            return $this->handleResponse(UserResource::collection($users), 'Users retrieved successfully', 200);
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
            return $this->handleResponse('User created successfully', $user, 200);
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
                return $this->handleResponseNoPagination($user, 'User retrieved successfully.', 200);
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
                $input = $request->all();
                if ($request->filled('password')){
                    $input['password'] = bcrypt($request->password);
                }  
                if ($request->hasFile('profile_picture')) {
                    $oldImage = public_path('images/profile_pictures/') . $user->profile_picture;
                    if (file_exists($oldImage)) {
                        unlink($oldImage);
                    }
                    $file = $request->file('profile_picture');
                    $fileName = time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images/profile_pictures/'), $fileName);
                    $input['profile_picture'] = $fileName;
                } 
                $user->update($input);
                return $this->handleResponseNoPagination('User updated successfully', $user, 200);
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
                return $this->handleResponse(200, 'User restored successfully', $user, 200);
            } else {
                return $this->handleError('User not found.', 400);
            }
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }

    public function getUserProfile (Request $request)
    {
        try {
            $user = $request->user()->load('role');
            return $this->handleResponseNoPagination(UserResource::make($user), 'User profile retrieved successfully', 200);
        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
    }
}

