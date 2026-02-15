<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'alamat' => 'nullable|string'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422);
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'user',          // default
        'status' => 'inactive',    // menunggu aktivasi
        'alamat' => $request->alamat
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Register berhasil, menunggu aktivasi admin',
        'data' => $user
    ], 201);
}

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Email atau password salah'
            ], 401);
        }

        if ($user->status !== 'active') {
            return response()->json([
                'status' => false,
                'message' => 'Akun tidak aktif'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function me()
{
    return response()->json([
        'status' => true,
        'data' => auth()->user()
    ]);
}


    public function updateProfile(Request $request)
{
    $user = auth()->user();

    $validator = Validator::make($request->all(), [
        'name' => 'sometimes|required|string|max:255',
        'email' => 'sometimes|required|email|max:255|unique:users,email,' . $user->id,
        'alamat' => 'nullable|string',
        'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    // Handle upload foto profile
    if ($request->hasFile('foto_profile')) {

        // Hapus foto lama kalau ada
        if ($user->foto_profile && Storage::exists($user->foto_profile)) {
            Storage::delete($user->foto_profile);
        }

        $path = $request->file('foto_profile')->store('profile', 'public');

        $user->foto_profile = $path;
    }

    // Update data
    $user->update([
        'name' => $request->name ?? $user->name,
        'email' => $request->email ?? $user->email,
        'alamat' => $request->alamat ?? $user->alamat,
        'foto_profile' => $user->foto_profile
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Profile berhasil diperbarui',
        'data' => $user
    ]);
}

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout berhasil'
        ]);
    }


public function changePassword(Request $request)
{
    $request->validate([
        'old_password' => 'required',
        'password' => 'required|min:6|confirmed'
    ]);

    $user = auth()->user();

    // Cek password lama
    if (!Hash::check($request->old_password, $user->password)) {
        return response()->json([
            'status' => false,
            'message' => 'Password lama salah'
        ], 400);
    }

    // Update password
    $user->update([
        'password' => Hash::make($request->password)
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Password berhasil diperbarui'
    ]);
}

public function listUsers(Request $request)
{
    $users = User::where('role', 'user')
        ->latest()
        ->paginate(10);

    return response()->json([
        'status' => true,
        'data' => $users
    ]);
}

public function activateUser($id)
{
    $user = User::find($id);

    if (!$user || $user->role !== 'user') {
        return response()->json([
            'status' => false,
            'message' => 'User tidak ditemukan'
        ], 404);
    }

    $user->update([
        'status' => 'active'
    ]);

    return response()->json([
        'status' => true,
        'message' => 'User berhasil diaktifkan'
    ]);
}

public function blockUser($id)
{
    $user = User::find($id);

    if (!$user || $user->role !== 'user') {
        return response()->json([
            'status' => false,
            'message' => 'User tidak ditemukan'
        ], 404);
    }

    $user->update([
        'status' => 'blocked'
    ]);

    // Optional: paksa logout semua token
    $user->tokens()->delete();

    return response()->json([
        'status' => true,
        'message' => 'User berhasil diblokir'
    ]);
}



}
