<x-layouts.app title="Tambah Pasien">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('pasien.index') }}" class="flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 transition">
            <i class="fas fa-arrow-left text-xs"></i>
        </a>
        <h2 class="text-xl font-bold text-slate-800">Tambah Pasien</h2>
    </div>

    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-7">
            <form action="{{ route('pasien.store') }}" method="POST">
                @csrf

                <div class="grid gap-5 md:grid-cols-2">
                    <div class="form-control">
                        <label class="label pb-1"><span class="text-sm font-semibold text-gray-700">Nama <span class="text-red-500">*</span></span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama pasien..." class="input input-bordered w-full rounded-lg text-sm @error('nama') input-error @enderror" required>
                        @error('nama')<label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>@enderror
                    </div>

                    <div class="form-control">
                        <label class="label pb-1"><span class="text-sm font-semibold text-gray-700">Email <span class="text-red-500">*</span></span></label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email pasien..." class="input input-bordered w-full rounded-lg text-sm @error('email') input-error @enderror" required>
                        @error('email')<label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>@enderror
                    </div>

                    <div class="form-control">
                        <label class="label pb-1"><span class="text-sm font-semibold text-gray-700">Alamat <span class="text-red-500">*</span></span></label>
                        <input type="text" name="alamat" value="{{ old('alamat') }}" placeholder="Masukkan alamat..." class="input input-bordered w-full rounded-lg text-sm @error('alamat') input-error @enderror" required>
                        @error('alamat')<label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>@enderror
                    </div>

                    <div class="form-control">
                        <label class="label pb-1"><span class="text-sm font-semibold text-gray-700">No KTP <span class="text-red-500">*</span></span></label>
                        <input type="text" name="no_ktp" value="{{ old('no_ktp') }}" placeholder="Masukkan nomor KTP..." class="input input-bordered w-full rounded-lg text-sm @error('no_ktp') input-error @enderror" required>
                        @error('no_ktp')<label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>@enderror
                    </div>

                    <div class="form-control">
                        <label class="label pb-1"><span class="text-sm font-semibold text-gray-700">No HP <span class="text-red-500">*</span></span></label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" placeholder="Masukkan nomor handphone..." class="input input-bordered w-full rounded-lg text-sm @error('no_hp') input-error @enderror" required>
                        @error('no_hp')<label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>@enderror
                    </div>

                    <div class="form-control">
                        <label class="label pb-1"><span class="text-sm font-semibold text-gray-700">No RM <span class="text-red-500">*</span></span></label>
                        <input type="text" name="no_rm" value="{{ old('no_rm') }}" placeholder="Masukkan nomor RM..." class="input input-bordered w-full rounded-lg text-sm @error('no_rm') input-error @enderror" required>
                        @error('no_rm')<label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>@enderror
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2 mt-5">
                    <div class="form-control">
                        <label class="label pb-1"><span class="text-sm font-semibold text-gray-700">Password <span class="text-red-500">*</span></span></label>
                        <input type="password" name="password" placeholder="Masukkan password..." class="input input-bordered w-full rounded-lg text-sm @error('password') input-error @enderror" required>
                        @error('password')<label class="label pt-1"><span class="label-text-alt text-red-500">{{ $message }}</span></label>@enderror
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit" class="flex items-center gap-2 px-6 py-2.5 bg-[#2d4499] hover:bg-[#1e2d6b] text-white rounded-lg text-sm font-semibold transition">
                        <i class="fas fa-save"></i>
                        Simpan
                    </button>
                    <a href="{{ route('pasien.index') }}" class="flex items-center gap-2 px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-lg text-sm font-semibold transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
