<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index()
    {
        $obats = Obat::all();

        return view('admin.obat.index', compact('obats'));
    }

    public function create()
    {
        return view('admin.obat.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_obat' => 'required|string|max:255',
            'kemasan'   => 'required|string|max:100',
            'harga'     => 'required|numeric|min:0',
            'stok'      => 'required|integer|min:0',
        ]);

        Obat::create($data);

        return redirect()->route('obat.index')
            ->with('message', 'Data Obat Berhasil di tambahkan')
            ->with('type', 'success');
    }

    public function edit(Obat $obat)
    {
        return view('admin.obat.edit', compact('obat'));
    }

    public function update(Request $request, Obat $obat)
    {
        $data = $request->validate([
            'nama_obat' => 'required|string|max:255',
            'kemasan'   => 'required|string|max:100',
            'harga'     => 'required|numeric|min:0',
            'stok'      => 'required|integer|min:0',
        ]);

        $obat->update($data);

        return redirect()->route('obat.index')
            ->with('message', 'Data Obat Berhasil di ubah')
            ->with('type', 'success');
    }

    public function destroy(Obat $obat)
    {
        $obat->delete();

        return redirect()->route('obat.index')
            ->with('message', 'Data Obat Berhasil Di Hapus')
            ->with('type', 'success');
    }

    /**
     * Tampilkan form penyesuaian stok (tambah/kurangi manual).
     */
    public function stokForm(Obat $obat)
    {
        return view('admin.obat.stok', compact('obat'));
    }

    /**
     * Proses penyesuaian stok manual oleh admin.
     */
    public function adjustStok(Request $request, Obat $obat)
    {
        $data = $request->validate([
            'aksi'   => 'required|in:tambah,kurangi',
            'jumlah' => 'required|integer|min:1',
        ]);

        if ($data['aksi'] === 'tambah') {
            $obat->increment('stok', $data['jumlah']);
            $pesan = "Stok {$obat->nama_obat} berhasil ditambah {$data['jumlah']} unit.";
        } else {
            if ($data['jumlah'] > $obat->stok) {
                return back()->withErrors([
                    'jumlah' => "Jumlah pengurangan ({$data['jumlah']}) melebihi stok yang tersedia ({$obat->stok}).",
                ])->withInput();
            }
            $obat->decrement('stok', $data['jumlah']);
            $pesan = "Stok {$obat->nama_obat} berhasil dikurangi {$data['jumlah']} unit.";
        }

        return redirect()->route('obat.index')
            ->with('message', $pesan)
            ->with('type', 'success');
    }
}
