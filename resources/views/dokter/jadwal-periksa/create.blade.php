<x-layouts.app title="Tambah Jadwal Periksa Dokter">
    <!-- ALERT FLASH MESSAGE -->
    <div class="container-fluid px-4 mt-4">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1 class="mb-4">Tambah Jadwal Periksa</h1>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('jadwal-periksa.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="hari" class="form-label">Hari</label>
                                <select name="hari" id="hari" class="form-select @error('hari') is-invalid @enderror"
                                    required>
                                    <option value="">Pilih Hari</option>
                                    @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                                        <option value="{{ $day }}" {{ old('hari') == $day ? 'selected' : '' }}>{{ $day }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hari')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="jam_mulai" class="form-label">Jam Mulai</label>
                                <input type="time" name="jam_mulai" id="jam_mulai"
                                    class="form-control @error('jam_mulai') is-invalid @enderror"
                                    value="{{ old('jam_mulai') }}" required>
                                @error('jam_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="jam_selesai" class="form-label">Jam Selesai</label>
                                <input type="time" name="jam_selesai" id="jam_selesai"
                                    class="form-control @error('jam_selesai') is-invalid @enderror"
                                    value="{{ old('jam_selesai') }}" required>
                                @error('jam_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="catatan" class="form-label">Catatan <span class="text-danger">*</span></label>
                                <textarea name="catatan" id="catatan"
                                    class="form-control @error('catatan') is-invalid @enderror"
                                    rows="4" placeholder="Masukkan catatan jadwal periksa" required>{{ old('catatan') }}</textarea>
                                <div id="warning-catatan" class="alert alert-warning alert-sm mt-2" style="display: none;">
                                    <i class="fas fa-exclamation-triangle"></i> Catatan harus diisi sebelum menyimpan
                                </div>
                                @error('catatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" id="btn-simpan" class="btn btn-success" disabled>
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <a href="{{ route('jadwal-periksa.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

<script>
    const catatanField = document.getElementById('catatan');
    const btnSimpan = document.getElementById('btn-simpan');
    const warningCatatan = document.getElementById('warning-catatan');

    function validateForm() {
        const catatan = catatanField.value.trim();

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

    // Validasi saat halaman dimuat (jika ada nilai lama)
    document.addEventListener('DOMContentLoaded', validateForm);
</script>
</x-layouts.app>
