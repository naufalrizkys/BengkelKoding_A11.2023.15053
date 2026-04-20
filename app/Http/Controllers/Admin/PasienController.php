<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasienController extends Controller
{
    public function index()
    {
        $pasiens = User::where('role', 'pasien')->with('poli')->get();

        return view('admin.pasien.index', compact('pasiens'));
    }

    public function create()
    {
        return view('admin.pasien.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'alamat' => 'required|string|max:255',
            'no_ktp' => 'required|string|max:50|unique:users,no_ktp',
            'no_hp' => 'required|string|max:20',
            'no_rm' => 'required|string|max:100',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'alamat' => $data['alamat'],
            'no_ktp' => $data['no_ktp'],
            'no_hp' => $data['no_hp'],
            'no_rm' => $data['no_rm'],
            'password' => Hash::make($data['password']),
            'role' => 'pasien',
        ]);

        return redirect()->route('pasien.index')
            ->with('message', 'Data Pasien Berhasil di tambahkan')
            ->with('type', 'success');
    }

    public function edit(User $pasien)
    {
        abort_unless($pasien->role === 'pasien', 404);

        return view('admin.pasien.edit', compact('pasien'));
    }

    public function update(Request $request, User $pasien)
    {
        abort_unless($pasien->role === 'pasien', 404);

        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $pasien->id,
            'alamat' => 'required|string|max:255',
            'no_ktp' => 'required|string|max:50|unique:users,no_ktp,' . $pasien->id,
            'no_hp' => 'required|string|max:20',
            'no_rm' => 'required|string|max:100',
            'password' => 'nullable|string|min:6',
        ]);

        $updateData = [
            'nama' => $data['nama'],
            'email' => $data['email'],
            'alamat' => $data['alamat'],
            'no_ktp' => $data['no_ktp'],
            'no_hp' => $data['no_hp'],
            'no_rm' => $data['no_rm'],
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $pasien->update($updateData);

        return redirect()->route('pasien.index')
            ->with('message', 'Data Pasien Berhasil di ubah')
            ->with('type', 'success');
    }

    public function destroy(User $pasien)
    {
        abort_unless($pasien->role === 'pasien', 404);

        $pasien->delete();

        return redirect()->route('pasien.index')
            ->with('message', 'Data Pasien Berhasil Di Hapus')
            ->with('type', 'success');
    }
}
