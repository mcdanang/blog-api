<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Exception;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validate the request data
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/[a-z]/',      // must contain at least one lowercase letter
                    'regex:/[A-Z]/',      // must contain at least one uppercase letter
                    'regex:/[0-9]/',      // must contain at least one number
                    'regex:/[@$!%*?&#]/'  // must contain a special character
                ],
            ]);

            // Create a new user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Return success response with token
            return response()->json([
                'user' => $user,
                'token' => $user->createToken('api-token')->plainTextToken
            ], 201); // 201 Created

        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity

        } catch (Exception $e) {
            // Handle any unexpected errors
            return response()->json([
                'message' => 'An error occurred while registering',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    public function login(Request $request)
    {
        try {
            // Validate the login request
            $validated = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required', 'string'],
            ]);

            // Find the user by email
            $user = User::where('email', $validated['email'])->first();

            // Check if user exists and password matches
            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return response()->json(['message' => 'Invalid credentials'], 401); // 401 Unauthorized
            }

            // Return success response with token
            return response()->json([
                'user' => $user,
                'token' => $user->createToken('api-token')->plainTextToken
            ], 200); // 200 OK

        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity

        } catch (Exception $e) {
            // Handle any unexpected errors
            return response()->json([
                'message' => 'An error occurred while logging in',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    public function logout(Request $request)
    {
        try {
            // Check if the user is authenticated
            if (!$request->user()) {
                return response()->json([
                    'message' => 'No user is currently authenticated.'
                ], 401); // 401 Unauthorized
            }

            // Delete the current access token
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Logged out successfully'], 200); // 200 OK

        } catch (Exception $e) {
            // Handle any unexpected errors
            return response()->json([
                'message' => 'An error occurred while logging out',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    public function show($id)
    {
        try {
            // Find the user by ID or throw 404 if not found
            $user = User::findOrFail($id);

            return response()->json($user, 200); // 200 OK

        } catch (Exception $e) {
            // Handle case where user is not found or other errors
            return response()->json([
                'message' => 'User not found',
                'error' => $e->getMessage()
            ], 404); // 404 Not Found
        }
    }
}
