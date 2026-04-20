<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DokterController extends Controller
{
    public function index()
    {
        $dokters = User::where('role', 'dokter')->with('poli')->get();

        return view('admin.dokter.index', compact('dokters'));
    }

    public function create()
    {
        $polis = Poli::all();

        return view('admin.dokter.create', compact('polis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'alamat' => 'required|string|max:255',
            'no_ktp' => 'required|string|max:50',
            'no_hp' => 'required|string|max:20',
            'no_rm' => 'required|string|max:100',
            'id_poli' => 'required|exists:poli,id',
            'password' => 'required|string|min:6',
        ]);

        $validated['role'] = 'dokter';
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('dokters.index')->with('success', 'Dokter berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $dokter = User::where('role', 'dokter')->findOrFail($id);
        $polis = Poli::all();

        return view('admin.dokter.edit', compact('dokter', 'polis'));
    }

    public function update(Request $request, string $id)
    {
        $dokter = User::where('role', 'dokter')->findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $dokter->id,
            'alamat' => 'required|string|max:255',
            'no_ktp' => 'required|string|max:50',
            'no_hp' => 'required|string|max:20',
            'no_rm' => 'required|string|max:100',
            'id_poli' => 'required|exists:poli,id',
            'password' => 'nullable|string|min:6',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $dokter->update($validated);

        return redirect()->route('dokters.index')->with('success', 'Dokter berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $dokter = User::where('role', 'dokter')->findOrFail($id);
        $dokter->delete();

        return redirect()->route('dokters.index')->with('success', 'Dokter berhasil dihapus!');
    }
}
