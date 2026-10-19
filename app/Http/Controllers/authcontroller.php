<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class authcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Email atau passwordsalah.'
            ], 401);
        }
        $token = $user->createToken('latihan-postman')->plainTextToken;
        return response()->json([
            'token' => $token,
            'token_type' =>
                'Bearer'
        ]);
    }
    public function profil(Request $request)
    {
        return response()->json([
            'data' => $request->user()->only([
                'id',
                'name',

                'email'
            ]),
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout
berhasil.'
        ]);
    }
    public function register(Request $request)
    {
        // 1. Validasi input
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // butuh field password_confirmation
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        // 2. Buat user baru
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Buat token Sanctum untuk user yang baru mendaftar
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Return response JSON
        return response()->json([
            'success'      => true,
            'message'      => 'Registrasi berhasil',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'data'         => $user
        ], 201);
    }

    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
