<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
class AuthController extends Controller
{
    public function register(Request $request)
    {
          $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
       

        try {
             $token = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json([
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    try {
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        return response()->json(['error' => 'Could not create token'], 500);
    }

    return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => JWTAuth::factory()->getTTL() * 60,
    ]);
}

        public function logout()
        {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to logout, please try again'], 500);
        }

        return response()->json(['message' => 'Successfully logged out']);
    }

  
public function getUser()
 {

    try {

       if (!JWTAuth::getToken()) {
           return response()->json(['error' => 'Token not provided'], 401);
        }

        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
           return response()->json(['error' => 'User not found'], 404);
        }

       return response()->json([
            'user' => $user,
           'token_valid' => true
        ]);


    } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
         return response()->json(['error' => 'Token expired'], 401);
    } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
         return response()->json(['error' => 'Token invalid'], 401);
    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        return response()->json(['error' => 'Token absent'], 401);
   } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to fetch user profile'], 500);
    }
 }

    public function updateUser(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        try {
            // Ensure we have an Eloquent User model instance
            $user = User::find(Auth::id());
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $user->fill($request->only(['name', 'email']));
            if ($user->isDirty()) {
                $user->save();
            }

            return response()->json($user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to update user'], 500);
        }
    }

}
