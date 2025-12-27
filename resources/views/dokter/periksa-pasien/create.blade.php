<x-layouts.app title="Periksa Pasien">
    {{-- ALERT FLASH MESSAGE --}}
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <h1 class="mb-4">Periksa Pasien</h1>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('periksa-pasien.store') }}" method="POST">
                            @csrf

                            <input type="hidden" name="id_daftar_poli" value="{{ $id }}">

                            <div class="form-group mb-3">
                                <label for="obat" class="form-label">Pilih Obat</label>
                                <div class="input-group">
                                    <select id="select-obat" class="form-select">
                                        <option value="">-- Pilih Obat --</option>
                                        @foreach ($obats as $obat)
                                            <option value="{{ $obat->id }}" data-nama="{{ $obat->nama_obat }}"
                                                data-harga="{{ $obat->harga }}" data-stok="{{ $obat->stok }}"
                                                {{ $obat->stok <= 0 ? 'disabled' : '' }}>
                                                {{ $obat->nama_obat }} - Rp{{ number_format($obat->harga) }} (Stok: {{ $obat->stok }})
                                                {{ $obat->stok <= 0 ? '- HABIS' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="number" id="input-kuantitas" class="form-control" placeholder="Qty" min="1" value="1" style="max-width: 80px;">
                                    <button type="button" class="btn btn-outline-secondary" id="btn-tambah-obat">Tambah</button>
                                </div>
                                @if ($obats->where('stok', '<=', 0)->count() > 0)
                                    <small class="text-danger">
                                        <i class="fas fa-exclamation-circle"></i> Obat yang habis tidak bisa dipilih
                                    </small>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <label for="catatan" class="form-label">Catatan <span class="text-danger">*</span></label>
                                <textarea name="catatan" id="catatan" class="form-control" rows="3" placeholder="Masukkan catatan periksa" required>{{ old('catatan') }}</textarea>
                                <div id="warning-catatan" class="alert alert-warning alert-sm mt-2" style="display: none;">
                                    <i class="fas fa-exclamation-triangle"></i> Catatan harus diisi sebelum menyimpan
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>Obat Terpilih</label>
                                <ul id="obat-terpilih" class="list-group mb-2"></ul>
                                <input type="hidden" name="biaya_periksa" id="biaya_periksa" value="0">
                                <input type="hidden" name="obat_json" id="obat_json">
                            </div>

                            <div class="form-group mb-3">
                                <label>Rincian Biaya</label>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Biaya Konsultasi:</span>
                                            <strong>Rp 150.000</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Harga Obat:</span>
                                            <strong id="total-obat">Rp 0</strong>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-bold">TOTAL BIAYA:</span>
                                            <strong id="total-harga" class="fs-5">Rp 150.000</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" id="btn-simpan" class="btn btn-success" disabled>Simpan</button>
                            <a href="{{ route('periksa-pasien.index') }}" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

<script>
    const selectObat = document.getElementById('select-obat');
    const inputKuantitas = document.getElementById('input-kuantitas');
    const btnTambahObat = document.getElementById('btn-tambah-obat');
    const listObat = document.getElementById('obat-terpilih');
    const inputBiaya = document.getElementById('biaya_periksa');
    const inputObatJson = document.getElementById('obat_json');
    const totalObatEl = document.getElementById('total-obat');
    const totalHargaEl = document.getElementById('total-harga');

    const BIAYA_KONSULTASI = 150000;
    let daftarObat = [];

    btnTambahObat.addEventListener('click', () => {
        const selectedOption = selectObat.options[selectObat.selectedIndex];
        const id = selectedOption.value;
        const nama = selectedOption.dataset.nama;
        const harga = parseInt(selectedOption.dataset.harga || 0);
        const stok = parseInt(selectedOption.dataset.stok || 0);
        const kuantitas = parseInt(inputKuantitas.value || 1);

        if (!id) {
            alert('Pilih obat terlebih dahulu!');
            return;
        }

        // Validasi stok
        if (stok <= 0) {
            alert(`❌ Obat "${nama}" habis! Stok tidak tersedia.`);
            selectObat.selectedIndex = 0;
            inputKuantitas.value = 1;
            return;
        }

        // Validasi kuantitas
        if (kuantitas <= 0) {
            alert('Kuantitas harus minimal 1');
            return;
        }

        // Cek apakah obat sudah ada di list
        const existingObat = daftarObat.find(o => o.id == id);
        if (existingObat) {
            // Update kuantitas jika obat sudah ada
            existingObat.kuantitas += kuantitas;
        } else {
            // Tambah obat baru
            daftarObat.push({
                id,
                nama,
                harga,
                stok,
                kuantitas
            });
        }

        renderObat();
        selectObat.selectedIndex = 0;
        inputKuantitas.value = 1;
    });

    // Allow Enter key to add obat
    inputKuantitas.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            btnTambahObat.click();
        }
    });

    function renderObat() {
        listObat.innerHTML = '';
        let total = 0;

        daftarObat.forEach((obat, index) => {
            const subtotal = obat.harga * obat.kuantitas;
            total += subtotal;

            const item = document.createElement('li');
            item.className = 'list-group-item';
            item.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${obat.nama}</strong><br>
                        <small class="text-muted">Rp ${obat.harga.toLocaleString()} x ${obat.kuantitas} = Rp ${subtotal.toLocaleString()}</small>
                    </div>
                    <div>
                        <input type="number" class="form-control form-control-sm" style="width: 70px;" min="1" value="${obat.kuantitas}"
                            onchange="ubahKuantitas(${index}, this.value)">
                        <button type="button" class="btn btn-sm btn-danger mt-1" onclick="hapusObat(${index})">Hapus</button>
                    </div>
                </div>
            `;
            listObat.appendChild(item);
        });

        const grandTotal = BIAYA_KONSULTASI + total;
        inputBiaya.value = grandTotal;
        totalObatEl.textContent = `Rp ${total.toLocaleString()}`;
        totalHargaEl.textContent = `Rp ${grandTotal.toLocaleString()}`;
        inputObatJson.value = JSON.stringify(daftarObat.map(o => ({id: o.id, kuantitas: o.kuantitas})));
    }

    function ubahKuantitas(index, nilai) {
        const kuantitas = parseInt(nilai || 1);
        if (kuantitas <= 0) {
            alert('Kuantitas harus minimal 1');
            daftarObat[index].kuantitas = 1;
        } else {
            daftarObat[index].kuantitas = kuantitas;
        }
        renderObat();
    }

    function hapusObat(index) {
        daftarObat.splice(index, 1);
        renderObat();
    }

    // Validasi catatan
    const catatanField = document.getElementById('catatan');
    const btnSimpan = document.getElementById('btn-simpan');
    const warningCatatan = document.getElementById('warning-catatan');

    function validateForm() {
        const catatan = catatanField.value.trim();

        // Hanya validasi catatan (wajib)
        if (catatan === '') {
            btnSimpan.disabled = true;
            warningCatatan.style.display = 'block';
        } else {
            btnSimpan.disabled = false;
            warningCatatan.style.display = 'none';
        }
    }

    // Validasi saat input berubah
    catatanField.addEventListener('input', validateForm);
    catatanField.addEventListener('change', validateForm);

    // Validasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', validateForm);
</script>
