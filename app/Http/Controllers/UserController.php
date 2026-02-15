<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'role' => 'required|in:admin,user',
        'status' => 'required|in:active,inactive,blocked',
        'alamat' => 'nullable|string',
        'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'foto_ktp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = [
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'status' => $request->status,
        'alamat' => $request->alamat,
    ];

    // Upload foto profile
    if ($request->hasFile('foto_profile')) {
        $data['foto_profile'] = $request->file('foto_profile')
            ->store('profiles', 'public');
    }

    // Upload foto KTP
    if ($request->hasFile('foto_ktp')) {
        $data['foto_ktp'] = $request->file('foto_ktp')
            ->store('ktp', 'public');
    }

    User::create($data);

    return redirect()->route('users.index')
        ->with('success', 'User berhasil ditambahkan');
}

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role' => 'required|in:admin,user',
        'status' => 'required|in:active,inactive,blocked',
        'alamat' => 'nullable|string',
        'password' => 'nullable|min:6',
        'foto_profile' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'foto_ktp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = [
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'status' => $request->status,
        'alamat' => $request->alamat,
    ];

    // Update password jika diisi
    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    // Update foto profile
    if ($request->hasFile('foto_profile')) {

        // hapus lama
        if ($user->foto_profile) {
            Storage::disk('public')->delete($user->foto_profile);
        }

        $data['foto_profile'] = $request->file('foto_profile')
            ->store('profiles', 'public');
    }

    // Update foto KTP
    if ($request->hasFile('foto_ktp')) {

        if ($user->foto_ktp) {
            Storage::disk('public')->delete($user->foto_ktp);
        }

        $data['foto_ktp'] = $request->file('foto_ktp')
            ->store('ktp', 'public');
    }

    $user->update($data);

    return redirect()->route('users.index')
        ->with('success', 'User berhasil diupdate');
}


    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus');
    }
}
