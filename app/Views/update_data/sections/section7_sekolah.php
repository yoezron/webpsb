<!-- Section 7: Asal Sekolah -->
<div class="section-card hidden" id="section-7">
    <h2 class="section-title">
        <i class="icofont-university"></i> Data Asal Sekolah
    </h2>

    <div class="row">
        <!-- Nama Asal Sekolah -->
        <div class="col-md-12 mb-3">
            <label for="nama_asal_sekolah" class="form-label required">Nama Sekolah Asal</label>
            <input type="text" class="form-control" id="nama_asal_sekolah" name="nama_asal_sekolah"
                value="<?= old('nama_asal_sekolah', $sekolah['nama_asal_sekolah'] ?? '') ?>" placeholder="Masukkan nama sekolah asal" required>
        </div>

        <!-- Jenjang Sekolah -->
        <div class="col-md-6 mb-3">
            <label for="jenjang_sekolah" class="form-label">Jenjang Sekolah</label>
            <select class="form-select" id="jenjang_sekolah" name="jenjang_sekolah">
                <option value="">Pilih Jenjang</option>
                <option value="SD" <?= old('jenjang_sekolah', $sekolah['jenjang_sekolah'] ?? '') === 'SD' ? 'selected' : '' ?>>SD</option>
                <option value="MI" <?= old('jenjang_sekolah', $sekolah['jenjang_sekolah'] ?? '') === 'MI' ? 'selected' : '' ?>>MI</option>
                <option value="SMP" <?= old('jenjang_sekolah', $sekolah['jenjang_sekolah'] ?? '') === 'SMP' ? 'selected' : '' ?>>SMP</option>
                <option value="MTs" <?= old('jenjang_sekolah', $sekolah['jenjang_sekolah'] ?? '') === 'MTs' ? 'selected' : '' ?>>MTs</option>
                <option value="Paket A" <?= old('jenjang_sekolah', $sekolah['jenjang_sekolah'] ?? '') === 'Paket A' ? 'selected' : '' ?>>Paket A</option>
                <option value="Paket B" <?= old('jenjang_sekolah', $sekolah['jenjang_sekolah'] ?? '') === 'Paket B' ? 'selected' : '' ?>>Paket B</option>
                <option value="Lainnya" <?= old('jenjang_sekolah', $sekolah['jenjang_sekolah'] ?? '') === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
            </select>
        </div>

        <!-- Status Sekolah -->
        <div class="col-md-6 mb-3">
            <label for="status_sekolah" class="form-label">Status Sekolah</label>
            <select class="form-select" id="status_sekolah" name="status_sekolah">
                <option value="">Pilih Status</option>
                <option value="Negeri" <?= old('status_sekolah', $sekolah['status_sekolah'] ?? '') === 'Negeri' ? 'selected' : '' ?>>Negeri</option>
                <option value="Swasta" <?= old('status_sekolah', $sekolah['status_sekolah'] ?? '') === 'Swasta' ? 'selected' : '' ?>>Swasta</option>
            </select>
        </div>

        <!-- NPSN -->
        <div class="col-md-6 mb-3">
            <label for="npsn" class="form-label">NPSN</label>
            <input type="text" class="form-control numeric-only" id="npsn" name="npsn"
                value="<?= old('npsn', $sekolah['npsn'] ?? '') ?>" placeholder="Nomor Pokok Sekolah Nasional" maxlength="8">
        </div>

        <!-- Lokasi Sekolah -->
        <div class="col-md-6 mb-3">
            <label for="lokasi_sekolah" class="form-label">Lokasi Sekolah</label>
            <input type="text" class="form-control" id="lokasi_sekolah" name="lokasi_sekolah"
                value="<?= old('lokasi_sekolah', $sekolah['lokasi_sekolah'] ?? '') ?>" placeholder="Kota/Kabupaten sekolah">
        </div>

        <!-- Asal Jenjang -->
        <div class="col-md-12 mb-3">
            <label for="asal_jenjang" class="form-label">Keterangan Tambahan</label>
            <input type="text" class="form-control" id="asal_jenjang" name="asal_jenjang"
                value="<?= old('asal_jenjang', $sekolah['asal_jenjang'] ?? '') ?>" placeholder="Keterangan tambahan (opsional)">
        </div>
    </div>
</div>
