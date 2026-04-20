<x-layouts.app title="Edit Pasien">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('pasien.index') }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>

        <h2 class="text-2xl font-bold text-slate-800">Edit Pasien</h2>
    </div>

    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-8">
            <form action="{{ route('pasien.update', $pasien->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $pasien->nama) }}" placeholder="Masukkan nama pasien..." class="w-full px-4 py-2 rounded-lg border-2 border-slate-300 focus:border-primary focus:outline-none @error('nama') border-red-500 @enderror" required>
                        @error('nama')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $pasien->email) }}" placeholder="Masukkan email pasien..." class="w-full px-4 py-2 rounded-lg border-2 border-slate-300 focus:border-primary focus:outline-none @error('email') border-red-500 @enderror" required>
                        @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat <span class="text-red-500">*</span></label>
                        <input type="text" name="alamat" value="{{ old('alamat', $pasien->alamat) }}" placeholder="Masukkan alamat..." class="w-full px-4 py-2 rounded-lg border-2 border-slate-300 focus:border-primary focus:outline-none @error('alamat') border-red-500 @enderror" required>
                        @error('alamat')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">No KTP <span class="text-red-500">*</span></label>
                        <input type="text" name="no_ktp" value="{{ old('no_ktp', $pasien->no_ktp) }}" placeholder="Masukkan nomor KTP..." class="w-full px-4 py-2 rounded-lg border-2 border-slate-300 focus:border-primary focus:outline-none @error('no_ktp') border-red-500 @enderror" required>
                        @error('no_ktp')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">No HP <span class="text-red-500">*</span></label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $pasien->no_hp) }}" placeholder="Masukkan nomor handphone..." class="w-full px-4 py-2 rounded-lg border-2 border-slate-300 focus:border-primary focus:outline-none @error('no_hp') border-red-500 @enderror" required>
                        @error('no_hp')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">No RM <span class="text-red-500">*</span></label>
                        <input type="text" name="no_rm" value="{{ old('no_rm', $pasien->no_rm) }}" placeholder="Masukkan nomor RM..." class="w-full px-4 py-2 rounded-lg border-2 border-slate-300 focus:border-primary focus:outline-none @error('no_rm') border-red-500 @enderror" required>
                        @error('no_rm')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah..." class="w-full px-4 py-2 rounded-lg border-2 border-slate-300 focus:border-primary focus:outline-none @error('password') border-red-500 @enderror">
                    @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#2d4499] hover:bg-[#1e2d6b] text-white font-semibold text-sm transition">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                    <a href="{{ route('pasien.index') }}" class="px-6 py-2.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm transition">Batal</a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
