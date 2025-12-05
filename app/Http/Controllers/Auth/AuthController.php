<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
  public function register(Request $request) : JsonResponse
  {
      $validated = $request->validate([
          'name' => 'required|string',
          'email' => 'required|string|email|unique:users',
          'password' => 'required|string|min:6',
      ]);

      $user = User::create([
          'name' => $validated['name'],
          'email' => $validated['email'],
          'password' => bcrypt($validated['password']),
      ]);



     return response()->json(['user' => $user],201);
  }

  public function login(Request $request) : JsonResponse
  {
      $credentials = $request->only('email', 'password');
      if(!$token = auth()->attempt($credentials)) {
          return response()->json(['error' => 'Unauthorized'], 401);
      }
      return response()->json(['token' => $token]);
  }

  public function me() : JsonResponse
  {
      return response()->json(auth()->user(), 200);
  }

  public function logout() : JsonResponse
  {
    auth()->logout();
    return response()->json(['message' => 'Successfully logged out'] , 200);
  }

  public function refresh() : JsonResponse
  {
      $token = auth()->refresh();
      return response()->json(['token' => $token], 200);
  }

}
