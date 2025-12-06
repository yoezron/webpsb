<!-- Section 3: Data Ayah -->
<div class="section-card hidden" id="section-3">
    <h2 class="section-title">
        <i class="icofont-user-male"></i> Data Ayah Kandung
    </h2>

    <div class="row">
        <!-- Nama Ayah -->
        <div class="col-md-12 mb-3">
            <label for="nama_ayah" class="form-label">Nama Ayah</label>
            <input type="text" class="form-control" id="nama_ayah" name="nama_ayah"
                value="<?= old('nama_ayah') ?>" placeholder="Masukkan nama lengkap ayah">
        </div>

        <!-- NIK Ayah -->
        <div class="col-md-6 mb-3">
            <label for="nik_ayah" class="form-label">NIK Ayah</label>
            <input type="text" class="form-control numeric-only" id="nik_ayah" name="nik_ayah"
                value="<?= old('nik_ayah') ?>" placeholder="Masukkan NIK ayah" maxlength="20">
        </div>

        <!-- Tempat Lahir Ayah -->
        <div class="col-md-6 mb-3">
            <label for="tempat_lahir_ayah" class="form-label">Tempat Lahir Ayah</label>
            <input type="text" class="form-control" id="tempat_lahir_ayah" name="tempat_lahir_ayah"
                value="<?= old('tempat_lahir_ayah') ?>" placeholder="Masukkan tempat lahir ayah">
        </div>

        <!-- Tanggal Lahir Ayah -->
        <div class="col-md-6 mb-3">
            <label for="tanggal_lahir_ayah" class="form-label">Tanggal Lahir Ayah</label>
            <input type="date" class="form-control" id="tanggal_lahir_ayah" name="tanggal_lahir_ayah"
                value="<?= old('tanggal_lahir_ayah') ?>">
        </div>

        <!-- Status Ayah -->
        <div class="col-md-6 mb-3">
            <label for="status_ayah" class="form-label">Status Ayah</label>
            <select class="form-select" id="status_ayah" name="status_ayah">
                <option value="">Pilih Status</option>
                <option value="Masih Hidup" <?= old('status_ayah') === 'Masih Hidup' ? 'selected' : '' ?>>Masih Hidup</option>
                <option value="Sudah Meninggal" <?= old('status_ayah') === 'Sudah Meninggal' ? 'selected' : '' ?>>Sudah Meninggal</option>
                <option value="Tidak Diketahui" <?= old('status_ayah') === 'Tidak Diketahui' ? 'selected' : '' ?>>Tidak Diketahui</option>
            </select>
        </div>

        <!-- Pendidikan Ayah -->
        <div class="col-md-6 mb-3">
            <label for="pendidikan_ayah" class="form-label">Pendidikan Terakhir Ayah</label>
            <select class="form-select" id="pendidikan_ayah" name="pendidikan_ayah">
                <option value="">Pilih Pendidikan</option>
                <option value="Tidak Sekolah" <?= old('pendidikan_ayah') === 'Tidak Sekolah' ? 'selected' : '' ?>>Tidak Sekolah</option>
                <option value="SD/Sederajat" <?= old('pendidikan_ayah') === 'SD/Sederajat' ? 'selected' : '' ?>>SD/Sederajat</option>
                <option value="SMP/Sederajat" <?= old('pendidikan_ayah') === 'SMP/Sederajat' ? 'selected' : '' ?>>SMP/Sederajat</option>
                <option value="SMA/Sederajat" <?= old('pendidikan_ayah') === 'SMA/Sederajat' ? 'selected' : '' ?>>SMA/Sederajat</option>
                <option value="D1/D2/D3" <?= old('pendidikan_ayah') === 'D1/D2/D3' ? 'selected' : '' ?>>D1/D2/D3</option>
                <option value="D4/S1" <?= old('pendidikan_ayah') === 'D4/S1' ? 'selected' : '' ?>>D4/S1</option>
                <option value="S2" <?= old('pendidikan_ayah') === 'S2' ? 'selected' : '' ?>>S2</option>
                <option value="S3" <?= old('pendidikan_ayah') === 'S3' ? 'selected' : '' ?>>S3</option>
            </select>
        </div>

        <!-- Pekerjaan Ayah -->
        <div class="col-md-6 mb-3">
            <label for="pekerjaan_ayah" class="form-label">Pekerjaan Ayah</label>
            <input type="text" class="form-control" id="pekerjaan_ayah" name="pekerjaan_ayah"
                value="<?= old('pekerjaan_ayah') ?>" placeholder="Contoh: Wiraswasta, PNS, Petani">
        </div>

        <!-- Penghasilan Ayah -->
        <div class="col-md-6 mb-3">
            <label for="penghasilan_ayah" class="form-label">Penghasilan Per Bulan</label>
            <select class="form-select" id="penghasilan_ayah" name="penghasilan_ayah">
                <option value="">Pilih Range Penghasilan</option>
                <option value="< Rp 1.000.000" <?= old('penghasilan_ayah') === '< Rp 1.000.000' ? 'selected' : '' ?>>< Rp 1.000.000</option>
                <option value="Rp 1.000.000 - Rp 2.000.000" <?= old('penghasilan_ayah') === 'Rp 1.000.000 - Rp 2.000.000' ? 'selected' : '' ?>>Rp 1.000.000 - Rp 2.000.000</option>
                <option value="Rp 2.000.000 - Rp 5.000.000" <?= old('penghasilan_ayah') === 'Rp 2.000.000 - Rp 5.000.000' ? 'selected' : '' ?>>Rp 2.000.000 - Rp 5.000.000</option>
                <option value="Rp 5.000.000 - Rp 10.000.000" <?= old('penghasilan_ayah') === 'Rp 5.000.000 - Rp 10.000.000' ? 'selected' : '' ?>>Rp 5.000.000 - Rp 10.000.000</option>
                <option value="> Rp 10.000.000" <?= old('penghasilan_ayah') === '> Rp 10.000.000' ? 'selected' : '' ?>>> Rp 10.000.000</option>
            </select>
        </div>

        <!-- HP Ayah -->
        <div class="col-md-6 mb-3">
            <label for="hp_ayah" class="form-label">Nomor HP/WhatsApp Ayah</label>
            <input type="text" class="form-control numeric-only" id="hp_ayah" name="hp_ayah"
                value="<?= old('hp_ayah') ?>" placeholder="Contoh: 08123456789" maxlength="20">
        </div>

        <!-- Alamat Ayah -->
        <div class="col-md-12 mb-3">
            <label for="alamat_ayah" class="form-label">Alamat Ayah</label>
            <textarea class="form-control" id="alamat_ayah" name="alamat_ayah" rows="2"
                placeholder="Masukkan alamat lengkap ayah (kosongkan jika sama dengan alamat santri)"><?= old('alamat_ayah') ?></textarea>
        </div>
    </div>
</div>
