<x-layouts.app title="Edit Obat">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('obat.index') }}" class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 transition">
            <i class="fas fa-arrow-left text-xs"></i>
        </a>
        <h2 class="text-xl font-bold text-slate-800">Edit Obat</h2>
    </div>

    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-7">
            <form action="{{ route('obat.update', $obat->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="form-control">
                        <label class="label pb-1"><span class="text-sm font-semibold text-gray-700">Nama Obat <span class="text-red-500">*</span></span></label>
                        <input type="text" name="nama_obat" value="{{ old('nama_obat', $obat->nama_obat) }}" placeholder="Masukkan nama obat..." class="input input-bordered w-full rounded-lg text-sm @error('nama_obat') input-error @enderror" required>
                        @error('nama_obat')<label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>@enderror
                    </div>

                    <div class="form-control">
                        <label class="label pb-1"><span class="text-sm font-semibold text-gray-700">Kemasan <span class="text-red-500">*</span></span></label>
                        <input type="text" name="kemasan" value="{{ old('kemasan', $obat->kemasan) }}" placeholder="Contoh: Tablet, Kapsul, Sirup..." class="input input-bordered w-full rounded-lg text-sm @error('kemasan') input-error @enderror" required>
                        @error('kemasan')<label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>@enderror
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2 mt-5">
                    <div class="form-control">
                        <label class="label pb-1"><span class="text-sm font-semibold text-gray-700">Harga <span class="text-red-500">*</span></span></label>
                        <input type="number" name="harga" value="{{ old('harga', $obat->harga) }}" placeholder="Masukkan harga..." step="0.01" min="0" class="input input-bordered w-full rounded-lg text-sm @error('harga') input-error @enderror" required>
                        @error('harga')<label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>@enderror
                    </div>

                    <div class="form-control">
                        <label class="label pb-1"><span class="text-sm font-semibold text-gray-700">Stok <span class="text-red-500">*</span></span></label>
                        <input type="number" name="stok" value="{{ old('stok', $obat->stok) }}" placeholder="Masukkan jumlah stok..." min="0" class="input input-bordered w-full rounded-lg text-sm @error('stok') input-error @enderror" required>
                        @error('stok')<label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>@enderror
                        <label class="label pt-1">
                            <span class="label-text-alt text-slate-400">
                                Untuk penyesuaian stok manual, gunakan tombol
                                <a href="{{ route('obat.stok', $obat->id) }}" class="text-blue-500 underline">Kelola Stok</a>.
                            </span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex items-center gap-2 px-6 py-2.5 bg-[#2d4499] hover:bg-[#1e2d6b] text-white rounded-lg text-sm font-semibold transition">
                        <i class="fas fa-save"></i>
                        Perbarui
                    </button>
                    <a href="{{ route('obat.index') }}" class="flex items-center gap-2 px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-lg text-sm font-semibold transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
