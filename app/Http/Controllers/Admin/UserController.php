<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all_active');

        $query = User::with('role'); 

        if ($status == 'all_active') {
            $query->whereIn('isactive', [0, 1]);
        } elseif ($status == 'archived') {
            $query->where('isactive', -1);
        }

        $users = $query->latest()->get();

        return view('admin.users.index', compact('users', 'status'));
    }

    public function create()
    {
        $user = new \App\Models\User();
        $roles = \App\Models\Role::all(); 
        return view('admin.users.create', compact('user', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'role_id'  => 'required|exists:userrole,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'role_id'   => $request->role_id,
            'isactive' => $request->has('isactive') ? true : false,
            'password'  => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role_id'  => 'required|exists:userrole,id', // Ganti sesuai nama tabel
            'password' => 'nullable|string|min:8|confirmed', // Nullable agar boleh kosong
        ]);

        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'role_id'   => $request->role_id,
            'isactive' => $request->has('isactive') ? true : false,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function deactivate(User $user)
    {
        if (auth()->id() == $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Aksi ditolak: Anda tidak bisa menonaktifkan akun sendiri!'
            ], 403);
        }

        $user->isactive = ($user->isactive == 1) ? 0 : 1;
        $user->save();

        return response()->json([
            'success' => true,
            'new_status' => $user->isactive
        ]);
    }

    public function archive(User $user)
    {
        if (auth()->id() == $user->id) {
            return back()->with('error', 'Tidak bisa mengarsipkan akun sendiri.');
        }

        $user->update(['isactive' => -1]);

        return back()->with('success', 'User telah dipindahkan ke daftar arsip.');
    }

    public function restore(User $user)
    {
        $user->update(['isactive' => 1]);
        return back()->with('success', 'User berhasil dipulihkan.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() == $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus secara permanen.');
    }
}