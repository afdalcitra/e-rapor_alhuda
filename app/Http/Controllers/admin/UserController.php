<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')
            ->orderBy('nama_lengkap')
            ->get();

        $yayasans = User::where('role', 'yayasan')
            ->orderBy('nama_lengkap')
            ->get();

        $gurus = User::where('role', 'guru')
            ->orderBy('nama_lengkap')
            ->get();

        return view('admin.users.index', compact(
            'admins',
            'yayasans',
            'gurus'
        ));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nipy' => [
                'required',
                'unique:users,nipy',
            ],

            'nama_lengkap' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'jabatan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'role' => [
                'required',
                'in:admin,yayasan,guru',
            ],

            'status' => [
                'required',
                'in:aktif,tidak_aktif',
            ],

            'password' => [
                'required',
                'confirmed',
                'min:8',
            ],
        ]);

        User::create($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(
        Request $request,
        User $user
    ) {
        $validated = $request->validate([

            'nipy' => [
                'required',
                Rule::unique('users')
                    ->ignore($user->id),
            ],

            'nama_lengkap' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'jabatan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'role' => [
                'required',
                'in:admin,yayasan,guru',
            ],

            'status' => [
                'required',
                'in:aktif,tidak_aktif',
            ],

            'password' => [
                'nullable',
                'confirmed',
                'min:8',
            ],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }
}

