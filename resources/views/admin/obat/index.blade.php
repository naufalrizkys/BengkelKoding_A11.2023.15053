<x-layouts.app title="Data Obat">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Data Obat</h2>

        <a href="{{ route('obat.create') }}" class="btn bg-[#2d4499] hover:bg-[#1e2d6b] text-white border-none rounded-lg px-5">
            <i class="fas fa-plus"></i>
            Tambah Obat
        </a>
    </div>

    @if(session('message'))
    <div class="alert alert-{{ session('type') }} mb-4 rounded-xl shadow-sm">
        <i class="fas fa-circle-{{ session('type') === 'success' ? 'check' : 'xmark' }}"></i>
        <span>{{ session('message') }}</span>
    </div>
    @endif

    {{-- Indikator stok menipis --}}
    @php $stokMenipis = $obats->filter(fn($o) => $o->isStokMenipis()); @endphp
    @if($stokMenipis->count())
    <div class="alert alert-warning mb-4 rounded-xl shadow-sm">
        <i class="fas fa-triangle-exclamation"></i>
        <span>
            Peringatan: Stok menipis untuk
            <strong>{{ $stokMenipis->pluck('nama_obat')->join(', ') }}</strong>
            (stok ≤ 10 unit).
        </span>
    </div>
    @endif

    <div class="card bg-base-100 shadow-md rounded-2 border">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead class="bg-slate-100 text-slate-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Nama Obat</th>
                            <th class="px-6 py-4">Kemasan</th>
                            <th class="px-6 py-4">Harga</th>
                            <th class="px-6 py-4 text-center">Stok</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($obats as $obat)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ $obat->nama_obat }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $obat->kemasan }}</td>
                            <td class="px-6 py-4 text-slate-500">Rp {{ number_format($obat->harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($obat->isStokHabis())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        <i class="fas fa-circle-xmark"></i> Habis
                                    </span>
                                @elseif($obat->isStokMenipis())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                        <i class="fas fa-triangle-exclamation"></i> {{ $obat->stok }} unit
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        <i class="fas fa-circle-check"></i> {{ $obat->stok }} unit
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('obat.stok', $obat->id) }}" class="btn btn-sm bg-emerald-500 hover:bg-emerald-600 text-white border-none rounded-lg px-4">
                                        <i class="fas fa-boxes-stacked"></i>
                                        Stok
                                    </a>
                                    <a href="{{ route('obat.edit', $obat->id) }}" class="btn btn-sm bg-amber-500 hover:bg-amber-600 text-white border-none rounded-lg px-4">
                                        <i class="fas fa-pen-to-square"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('obat.destroy', $obat->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Yakin ingin menghapus obat ini?')" class="btn btn-sm bg-red-500 hover:bg-red-600 text-white border-none rounded-lg px-4">
                                            <i class="fas fa-trash"></i>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-14 text-slate-400">
                                <i class="fas fa-inbox text-3xl mb-3 block"></i>
                                Belum ada data obat
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>
