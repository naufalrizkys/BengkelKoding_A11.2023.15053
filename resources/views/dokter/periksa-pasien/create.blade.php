<x-layouts.app title="Periksa Pasien">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('periksa-pasien.index') }}"
            class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Periksa Pasien</h2>
            <p class="text-sm text-slate-500">{{ $daftarPoli->pasien->nama }} &mdash; Antrian #{{ $daftarPoli->no_antrian }}</p>
        </div>
    </div>

    {{-- Error validasi stok dari server (fallback) --}}
    @if($errors->has('stok'))
    <div class="alert alert-error mb-4 rounded-xl shadow-sm">
        <i class="fas fa-circle-xmark flex-shrink-0"></i>
        <div>
            <p class="font-semibold">Gagal menyimpan — stok obat tidak mencukupi:</p>
            <ul class="list-disc list-inside mt-1 text-sm">
                @foreach($errors->get('stok') as $msg)
                    @if(is_array($msg))
                        @foreach($msg as $m)<li>{{ $m }}</li>@endforeach
                    @else
                        <li>{{ $msg }}</li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    @if($errors->has('obat_json'))
    <div class="alert alert-error mb-4 rounded-xl shadow-sm">
        <i class="fas fa-circle-xmark"></i>
        <span>{{ $errors->first('obat_json') }}</span>
    </div>
    @endif

    {{-- Banner obat stok habis --}}
    @php $stokHabis = $allObats->filter(fn($o) => $o->isStokHabis()); @endphp
    @if($stokHabis->count())
    <div class="alert alert-warning mb-4 rounded-xl shadow-sm">
        <i class="fas fa-triangle-exclamation flex-shrink-0"></i>
        <span>
            Stok habis:
            <strong>{{ $stokHabis->pluck('nama_obat')->join(', ') }}</strong>
            &mdash; tidak dapat diresepkan.
        </span>
    </div>
    @endif

    {{-- Card Form --}}
    <div class="card bg-base-100 shadow-sm rounded-2xl border border-slate-200">
        <div class="card-body p-8">
            <form action="{{ route('periksa-pasien.store') }}" method="POST" id="form-periksa">
                @csrf
                <input type="hidden" name="id_daftar_poli" value="{{ $id }}">

                {{-- Pilih Obat --}}
                <div class="form-control mb-2">
                    <label class="label pb-1">
                        <span class="text-sm font-semibold text-gray-700">
                            Pilih Obat <span class="text-red-500">*</span>
                        </span>
                    </label>

                    <select id="select-obat" class="select select-bordered w-full rounded-lg border-2 px-4">
                        <option value="">-- Pilih Obat --</option>
                        @foreach ($allObats as $obat)
                            <option
                                value="{{ $obat->id }}"
                                data-nama="{{ $obat->nama_obat }}"
                                data-harga="{{ $obat->harga }}"
                                data-stok="{{ $obat->stok }}"
                                data-habis="{{ $obat->isStokHabis() ? '1' : '0' }}"
                                data-menipis="{{ $obat->isStokMenipis() ? '1' : '0' }}"
                                @if($obat->isStokHabis()) class="text-red-400" @endif>
                                {{ $obat->nama_obat }} — Rp{{ number_format($obat->harga) }}
                                @if($obat->isStokHabis())
                                    ❌ (Stok Habis)
                                @elseif($obat->isStokMenipis())
                                    ⚠️ (Stok: {{ $obat->stok }})
                                @else
                                    (Stok: {{ $obat->stok }})
                                @endif
                            </option>
                        @endforeach
                    </select>

                    {{-- Error real-time stok habis --}}
                    <div id="error-stok"
                        class="hidden mt-2 flex items-start gap-2 px-4 py-3 bg-red-50 border border-red-300 rounded-lg text-sm text-red-700 font-medium">
                        <i class="fas fa-circle-xmark mt-0.5 flex-shrink-0"></i>
                        <span id="error-stok-msg"></span>
                    </div>
                </div>

                {{-- Obat Terpilih --}}
                <div class="form-control mt-5 mb-5">
                    <label class="label pb-1">
                        <span class="text-sm font-semibold text-gray-700">Obat Terpilih</span>
                    </label>

                    <div id="obat-terpilih-wrapper"
                        class="min-h-[52px] rounded-lg border border-dashed border-slate-300 bg-slate-50 p-3">
                        <ul id="obat-terpilih" class="flex flex-col gap-2"></ul>
                        <p id="placeholder-kosong" class="text-sm text-slate-400 italic text-center py-2">
                            Belum ada obat dipilih.
                        </p>
                    </div>

                    <input type="hidden" name="biaya_periksa" id="biaya_periksa" value="0">
                    <input type="hidden" name="obat_json" id="obat_json" value="[]">
                </div>

                {{-- Total Harga --}}
                <div class="form-control mb-5">
                    <label class="label pb-1">
                        <span class="text-sm font-semibold text-gray-700">Total Harga Obat</span>
                    </label>
                    <div class="input input-bordered w-full rounded-lg flex items-center bg-slate-50 text-slate-700 font-bold"
                        id="total-harga">
                        Rp 0
                    </div>
                </div>

                {{-- Catatan --}}
                <div class="form-control mb-8">
                    <label class="label pb-1">
                        <span class="text-sm font-semibold text-gray-700">
                            Catatan <span class="text-slate-400 font-normal">(Opsional)</span>
                        </span>
                    </label>
                    <textarea name="catatan" rows="4" placeholder="Masukkan catatan..."
                        class="textarea textarea-bordered w-full border-2 px-4 py-2 rounded-lg resize-none">{{ old('catatan') }}</textarea>
                </div>

                {{-- Tombol --}}
                <div class="flex gap-3">
                    <button type="submit"
                        class="btn bg-[#2d4499] hover:bg-[#1e2d6b] text-white border-none rounded-lg px-6">
                        <i class="fas fa-save"></i>
                        Simpan
                    </button>
                    <a href="{{ route('periksa-pasien.index') }}"
                        class="btn btn-ghost bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-lg px-6">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const selectObat    = document.getElementById('select-obat');
        const listObat      = document.getElementById('obat-terpilih');
        const placeholder   = document.getElementById('placeholder-kosong');
        const inputBiaya    = document.getElementById('biaya_periksa');
        const inputObatJson = document.getElementById('obat_json');
        const totalHargaEl  = document.getElementById('total-harga');
        const errorBox      = document.getElementById('error-stok');
        const errorMsg      = document.getElementById('error-stok-msg');

        let daftarObat = [];
        let errorTimer = null;

        function showError(msg) {
            errorMsg.textContent = msg;
            errorBox.classList.remove('hidden');
            // Highlight dropdown merah sementara
            selectObat.classList.add('select-error');
            clearTimeout(errorTimer);
            errorTimer = setTimeout(() => {
                errorBox.classList.add('hidden');
                selectObat.classList.remove('select-error');
            }, 4000);
        }

        function hideError() {
            errorBox.classList.add('hidden');
            selectObat.classList.remove('select-error');
            clearTimeout(errorTimer);
        }

        selectObat.addEventListener('change', () => {
            const opt = selectObat.options[selectObat.selectedIndex];
            const id  = opt.value;

            // Reset pilihan dropdown segera
            selectObat.selectedIndex = 0;

            if (!id) return;

            const nama    = opt.dataset.nama;
            const harga   = parseInt(opt.dataset.harga || 0);
            const stok    = parseInt(opt.dataset.stok || 0);
            const habis   = opt.dataset.habis === '1';
            const menipis = opt.dataset.menipis === '1';

            // ✅ Stok habis → tampilkan error, JANGAN tambah ke resep
            if (habis) {
                showError(`Stok "${nama}" sudah habis. Tidak dapat diresepkan. Hubungi admin untuk pengisian ulang.`);
                return;
            }

            // Duplikat → tampilkan error
            if (daftarObat.some(o => o.id === id)) {
                showError(`"${nama}" sudah ada dalam daftar resep.`);
                return;
            }

            hideError();
            daftarObat.push({ id, nama, harga, stok, menipis });
            renderObat();
        });

        function renderObat() {
            listObat.innerHTML = '';

            if (daftarObat.length === 0) {
                placeholder.style.display = '';
                inputBiaya.value    = 0;
                totalHargaEl.textContent = 'Rp 0';
                inputObatJson.value = '[]';
                return;
            }

            placeholder.style.display = 'none';
            let total = 0;

            daftarObat.forEach((obat, index) => {
                total += obat.harga;

                const badge = obat.menipis
                    ? `<span class="inline-flex items-center gap-1 text-xs font-medium text-amber-700 bg-amber-100 border border-amber-200 px-2 py-0.5 rounded-full">
                           <i class="fas fa-triangle-exclamation text-[10px]"></i> Stok: ${obat.stok}
                       </span>`
                    : `<span class="text-xs text-slate-400">(Stok: ${obat.stok})</span>`;

                const li = document.createElement('li');
                li.className = 'flex items-center justify-between px-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm text-slate-700 shadow-sm';
                li.innerHTML = `
                    <span class="flex items-center gap-2 flex-wrap">
                        <span class="font-medium">${obat.nama}</span>
                        <span class="text-slate-300">|</span>
                        <span class="font-semibold text-[#2d4499]">Rp ${obat.harga.toLocaleString('id-ID')}</span>
                        ${badge}
                    </span>
                    <button type="button" onclick="hapusObat(${index})"
                        class="btn btn-sm bg-red-500 hover:bg-red-600 text-white border-none rounded-lg px-3 ml-3 flex-shrink-0">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
                listObat.appendChild(li);
            });

            inputBiaya.value         = total;
            totalHargaEl.textContent = `Rp ${total.toLocaleString('id-ID')}`;
            inputObatJson.value      = JSON.stringify(daftarObat.map(o => o.id));
        }

        function hapusObat(index) {
            daftarObat.splice(index, 1);
            renderObat();
        }
    </script>

</x-layouts.app>
