<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeriksaPasienController extends Controller
{
    public function index()
    {
        $dokterId = Auth::id();

        $daftarPasien = DaftarPoli::with(['pasien', 'jadwalPeriksa', 'periksas'])
            ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId);
            })
            ->orderBy('no_antrian')
            ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPasien'));
    }

    public function create($id)
    {
        $daftarPoli = DaftarPoli::with('pasien')->findOrFail($id);
        // Kirim SEMUA obat ke view, termasuk yang stok habis
        // agar JS bisa menampilkan error saat dokter memilih obat habis
        $allObats = Obat::orderBy('nama_obat')->get();
        return view('dokter.periksa-pasien.create', compact('allObats', 'id', 'daftarPoli'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'obat_json'     => 'required',
            'catatan'       => 'nullable|string',
            'biaya_periksa' => 'required|integer',
        ]);

        $obatIds = json_decode($request->obat_json, true);

        if (empty($obatIds)) {
            return back()->withErrors(['obat_json' => 'Pilih minimal satu obat untuk resep.'])->withInput();
        }

        // Validasi stok semua obat sebelum menyimpan
        $obatDipilih = Obat::whereIn('id', $obatIds)->get()->keyBy('id');

        // Hitung kemunculan setiap obat (jika ada duplikat)
        $obatCount = array_count_values($obatIds);

        $errors = [];
        foreach ($obatCount as $idObat => $jumlah) {
            $obat = $obatDipilih->get($idObat);
            if (!$obat) {
                $errors[] = "Obat dengan ID {$idObat} tidak ditemukan.";
            } elseif ($obat->stok < $jumlah) {
                $errors[] = "Stok {$obat->nama_obat} tidak mencukupi. Tersedia: {$obat->stok} unit.";
            }
        }

        if (!empty($errors)) {
            return back()->withErrors(['stok' => $errors])->withInput();
        }

        // Simpan dalam transaksi database agar konsisten
        DB::transaction(function () use ($request, $obatIds, $obatDipilih, $obatCount) {
            $periksa = Periksa::create([
                'id_daftar_poli' => $request->id_daftar_poli,
                'tgl_periksa'    => now(),
                'catatan'        => $request->catatan,
                'biaya_periksa'  => $request->biaya_periksa + 150000,
            ]);

            foreach ($obatIds as $idObat) {
                DetailPeriksa::create([
                    'id_periksa' => $periksa->id,
                    'id_obat'    => $idObat,
                ]);
            }

            // Kurangi stok secara otomatis untuk setiap obat yang diresepkan
            foreach ($obatCount as $idObat => $jumlah) {
                Obat::where('id', $idObat)->decrement('stok', $jumlah);
            }
        });

        return redirect()->route('periksa-pasien.index')
            ->with('message', 'Data periksa berhasil disimpan dan stok obat telah diperbarui.')
            ->with('type', 'success');
    }
}
