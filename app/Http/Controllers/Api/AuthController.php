<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Permissions\V1\Abilities;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;
    
    public function login(LoginRequest $request)
    {
        if(!Auth::attempt($request->validated())) {
            return $this->error('Invalid credentials', 401);
        }
        
        $user = Auth::user();

        return $this->success('Authenticated', [
            'token' => $user->createToken(
                'API token for '.$user->email,
                Abilities::getAbilities($user)
                )->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success('Logged out successfully.');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
        ]);

        return $this->success('User registered successfully.', [
            'user' => $user,
            'token' => $user->createToken(
                'API token for '.$user->email,
                Abilities::getAbilities($user)
                )->plainTextToken,
        ],201);
    }
}