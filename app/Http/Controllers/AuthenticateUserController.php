<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Session;
use Throwable;
use Validator;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class AuthenticateUserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param String $request['name']
     * @param String $request['email']
     * @param String $request['password']
     * @param String $request['password_confirmation']
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        //Validate data passed in form
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:32|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => [
                'required',
                'confirmed',
                'max:16',
                Password::min(6)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ]);

        //When validation fails send response
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }

        //Try to store validated data
        try {
            //Store a new user
            User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
            ]);

            //Successfull response
            return response()->json([
                'message' => 'New account created!',
            ], 201);
        } catch (Throwable $throwable) {
            //Server side error
            return response()->json([
                'message' => $throwable
            ], 500);
        }
    }

    /**
     * Create session for user.
     * 
     * @param String $request['email']
     * @param String $request['password']
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        //Save data needed to authentication
        $credentials = $request->only('email', 'password');

        try {
            //Invalid credentials 
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Invalid email or password.',
                ], 401);
            }
            //Valid credentials
            else {
                return response()->json([
                    'message' => 'Logged in.',
                ], 200);
            }
        } catch (Throwable $throwable) {
            //Server side error
            return response()->json([
                'message' => $throwable
            ], 500);
        }
    }

    /**
     * Destroy user session.
     *      
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            Session::flush();
            //Return success message
            return response()->json([
                'message' => 'Logged out',
            ], 200);
        } catch (Throwable $throwable) {
            //Server side error
            return response()->json([
                'message' => $throwable
            ], 500);
        }
    }
}
