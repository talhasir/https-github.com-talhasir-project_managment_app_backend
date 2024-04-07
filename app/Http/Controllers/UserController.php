<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();
        $queryParams =  $request->query();

        if (isset($queryParams['filters']['name'])) {
            $searchTerm = $queryParams['filters']['name'][0];
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }
        if (isset($queryParams['filters']['email'])) {
            $searchTerm = $queryParams['filters']['email'][0];
            $query->where('email', 'like', '%' . $searchTerm . '%');
        }
        // if (isset($queryParams['filters']['created_by'])) {
        //     $searchTerm = $queryParams['filters']['name'][0];
        //     $query->where('name', 'like', '%' . $searchTerm . '%');
        // }

        $user = $query->orderBy('id', 'desc')->paginate(10);

        return response()->json([
            'users' => [    
                'data' =>  UserResource::collection($user),
                'queryParams' => $queryParams,
                'pagination' => [
                    'total' => $user->total(),
                    'per_page' => $user->perPage(),
                    'current_page' => $user->currentPage(),
                    'last_page' => $user->lastPage(),
                    'from' => $user->firstItem(),
                    'to' => $user->lastItem(),
                ],
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = $request->validated();
        $user['password'] = bcrypt($user['password']);
        $userStored = User::create($user);

        if ($userStored) {
            return response(['Success' => 'User created successfully']);
        } else {
            return response(['Error' => 'User was not created']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return ['user' => new UserResource($user)];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
        ]);

        $user = User::find($request->id);
        $user['name'] = $request->input('name');
        $user['email'] = $request->input('email');
        $user->save();
        return ['user updated'];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if($user->delete()){
           return ['success' => 'deleted user successfully'];
        }
    }
}
