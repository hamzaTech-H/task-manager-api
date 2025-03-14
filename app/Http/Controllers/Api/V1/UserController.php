<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\UserFilter;
use App\Http\Requests\Api\V1\ReplaceUserRequest;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserFilter $filters)
    {
        return UserResource::collection(User::filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
            $this->authorize('store', User::class);

            return new UserResource(User::create($request->mappedAttributes()));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        if ($this->include('tasks')) {
            return new UserResource($user->load('tasks'));
        }
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
            $this->authorize('update', $user);
            
            $user->update($request->mappedAttributes());
    
            return new UserResource($user);
    }

    public function replace(ReplaceUserRequest $request,User $user) 
    {
            $this->authorize('replace', $user);

            $user->update($request->mappedAttributes());

            return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
            $this->authorize('delete', $user);

            $user->delete();

            return $this->success('user successfully deleted');
    }
}