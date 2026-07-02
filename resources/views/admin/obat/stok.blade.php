<x-layouts.app title="Kelola Stok Obat">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('obat.index') }}" class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 transition">
            <i class="fas fa-arrow-left text-xs"></i>
        </a>
        <h2 class="text-xl font-bold text-slate-800">Kelola Stok Obat</h2>
    </div>

    {{-- Info Obat --}}
    <div class="card bg-base-100 shadow-sm rounded-2xl border border-slate-200 mb-6">
        <div class="card-body p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Nama Obat</p>
                    <p class="font-semibold text-slate-800">{{ $obat->nama_obat }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Kemasan</p>
                    <p class="font-semibold text-slate-800">{{ $obat->kemasan }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Harga</p>
                    <p class="font-semibold text-slate-800">Rp {{ number_format($obat->harga, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Stok Saat Ini</p>
                    @if($obat->isStokHabis())
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-sm font-bold bg-red-100 text-red-700">
                            <i class="fas fa-circle-xmark"></i> Habis
                        </span>
                    @elseif($obat->isStokMenipis())
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-sm font-bold bg-amber-100 text-amber-700">
                            <i class="fas fa-triangle-exclamation"></i> {{ $obat->stok }} unit
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-sm font-bold bg-green-100 text-green-700">
                            <i class="fas fa-circle-check"></i> {{ $obat->stok }} unit
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-error mb-4 rounded-xl shadow-sm">
        <i class="fas fa-circle-xmark"></i>
        <span>{{ $errors->first() }}</span>
    </div>
    @endif

    {{-- Form Penyesuaian Stok --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-7">
            <h3 class="text-base font-bold text-slate-700 mb-5">Penyesuaian Stok Manual</h3>

            <form action="{{ route('obat.stok.adjust', $obat->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="grid gap-5 md:grid-cols-2">
                    {{-- Pilih Aksi --}}
                    <div class="form-control">
                        <label class="label pb-1">
                            <span class="text-sm font-semibold text-gray-700">Aksi <span class="text-red-500">*</span></span>
                        </label>
                        <div class="flex gap-3">
                            <label class="flex items-center gap-2 cursor-pointer px-4 py-2.5 border-2 rounded-lg flex-1 transition has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50">
                                <input type="radio" name="aksi" value="tambah" class="radio radio-success radio-sm"
                                    {{ old('aksi', 'tambah') === 'tambah' ? 'checked' : '' }}>
                                <span class="text-sm font-semibold text-slate-700">
                                    <i class="fas fa-plus-circle text-emerald-500"></i> Tambah Stok
                                </span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer px-4 py-2.5 border-2 rounded-lg flex-1 transition has-[:checked]:border-red-400 has-[:checked]:bg-red-50">
                                <input type="radio" name="aksi" value="kurangi" class="radio radio-error radio-sm"
                                    {{ old('aksi') === 'kurangi' ? 'checked' : '' }}>
                                <span class="text-sm font-semibold text-slate-700">
                                    <i class="fas fa-minus-circle text-red-500"></i> Kurangi Stok
                                </span>
                            </label>
                        </div>
                    </div>

                    {{-- Jumlah --}}
                    <div class="form-control">
                        <label class="label pb-1">
                            <span class="text-sm font-semibold text-gray-700">Jumlah <span class="text-red-500">*</span></span>
                        </label>
                        <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" min="1"
                            placeholder="Masukkan jumlah..."
                            class="input input-bordered w-full rounded-lg text-sm @error('jumlah') input-error @enderror"
                            required>
                        @error('jumlah')
                            <label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex items-center gap-2 px-6 py-2.5 bg-[#2d4499] hover:bg-[#1e2d6b] text-white rounded-lg text-sm font-semibold transition">
                        <i class="fas fa-save"></i>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('obat.index') }}" class="flex items-center gap-2 px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-lg text-sm font-semibold transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
