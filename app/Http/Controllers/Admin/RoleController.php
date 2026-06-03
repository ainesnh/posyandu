<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role; // Pastikan model Role sudah dibuat
    use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('admin.userrole.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_role' => 'required|string|max:255|unique:userrole,nama_role',
            'keterangan' => 'nullable|string',
        ]);

        Role::create([
            'nama_role' => $request->nama_role,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'nama_role' => 'required|string|max:255|unique:userrole,nama_role,' . $id,
            'keterangan' => 'nullable|string',
        ]);

        $role->update([
            'nama_role' => $request->nama_role,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Data role berhasil diperbarui.');
    }

   public function destroy($id)
    {
        $role = Role::findOrFail($id);
        
        if ($role->users()->count() > 0) {
            return back()->with('error_swal', 'Role tidak bisa dihapus karena masih digunakan user!');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil dihapus.');
    }
}