<!-- Section 4: Data Ibu -->
<div class="section-card hidden" id="section-4">
    <h2 class="section-title">
        <i class="icofont-user-female"></i> Data Ibu Kandung
    </h2>

    <div class="row">
        <!-- Nama Ibu -->
        <div class="col-md-12 mb-3">
            <label for="nama_ibu" class="form-label">Nama Ibu</label>
            <input type="text" class="form-control" id="nama_ibu" name="nama_ibu"
                value="<?= old('nama_ibu') ?>" placeholder="Masukkan nama lengkap ibu">
        </div>

        <!-- NIK Ibu -->
        <div class="col-md-6 mb-3">
            <label for="nik_ibu" class="form-label">NIK Ibu</label>
            <input type="text" class="form-control numeric-only" id="nik_ibu" name="nik_ibu"
                value="<?= old('nik_ibu') ?>" placeholder="Masukkan NIK ibu" maxlength="20">
        </div>

        <!-- Tempat Lahir Ibu -->
        <div class="col-md-6 mb-3">
            <label for="tempat_lahir_ibu" class="form-label">Tempat Lahir Ibu</label>
            <input type="text" class="form-control" id="tempat_lahir_ibu" name="tempat_lahir_ibu"
                value="<?= old('tempat_lahir_ibu') ?>" placeholder="Masukkan tempat lahir ibu">
        </div>

        <!-- Tanggal Lahir Ibu -->
        <div class="col-md-6 mb-3">
            <label for="tanggal_lahir_ibu" class="form-label">Tanggal Lahir Ibu</label>
            <input type="date" class="form-control" id="tanggal_lahir_ibu" name="tanggal_lahir_ibu"
                value="<?= old('tanggal_lahir_ibu') ?>">
        </div>

        <!-- Status Ibu -->
        <div class="col-md-6 mb-3">
            <label for="status_ibu" class="form-label">Status Ibu</label>
            <select class="form-select" id="status_ibu" name="status_ibu">
                <option value="">Pilih Status</option>
                <option value="Masih Hidup" <?= old('status_ibu') === 'Masih Hidup' ? 'selected' : '' ?>>Masih Hidup</option>
                <option value="Sudah Meninggal" <?= old('status_ibu') === 'Sudah Meninggal' ? 'selected' : '' ?>>Sudah Meninggal</option>
                <option value="Tidak Diketahui" <?= old('status_ibu') === 'Tidak Diketahui' ? 'selected' : '' ?>>Tidak Diketahui</option>
            </select>
        </div>

        <!-- Pendidikan Ibu -->
        <div class="col-md-6 mb-3">
            <label for="pendidikan_ibu" class="form-label">Pendidikan Terakhir Ibu</label>
            <select class="form-select" id="pendidikan_ibu" name="pendidikan_ibu">
                <option value="">Pilih Pendidikan</option>
                <option value="Tidak Sekolah" <?= old('pendidikan_ibu') === 'Tidak Sekolah' ? 'selected' : '' ?>>Tidak Sekolah</option>
                <option value="SD/Sederajat" <?= old('pendidikan_ibu') === 'SD/Sederajat' ? 'selected' : '' ?>>SD/Sederajat</option>
                <option value="SMP/Sederajat" <?= old('pendidikan_ibu') === 'SMP/Sederajat' ? 'selected' : '' ?>>SMP/Sederajat</option>
                <option value="SMA/Sederajat" <?= old('pendidikan_ibu') === 'SMA/Sederajat' ? 'selected' : '' ?>>SMA/Sederajat</option>
                <option value="D1/D2/D3" <?= old('pendidikan_ibu') === 'D1/D2/D3' ? 'selected' : '' ?>>D1/D2/D3</option>
                <option value="D4/S1" <?= old('pendidikan_ibu') === 'D4/S1' ? 'selected' : '' ?>>D4/S1</option>
                <option value="S2" <?= old('pendidikan_ibu') === 'S2' ? 'selected' : '' ?>>S2</option>
                <option value="S3" <?= old('pendidikan_ibu') === 'S3' ? 'selected' : '' ?>>S3</option>
            </select>
        </div>

        <!-- Pekerjaan Ibu -->
        <div class="col-md-6 mb-3">
            <label for="pekerjaan_ibu" class="form-label">Pekerjaan Ibu</label>
            <input type="text" class="form-control" id="pekerjaan_ibu" name="pekerjaan_ibu"
                value="<?= old('pekerjaan_ibu') ?>" placeholder="Contoh: Ibu Rumah Tangga, PNS, Wiraswasta">
        </div>

        <!-- Penghasilan Ibu -->
        <div class="col-md-6 mb-3">
            <label for="penghasilan_ibu" class="form-label">Penghasilan Per Bulan</label>
            <select class="form-select" id="penghasilan_ibu" name="penghasilan_ibu">
                <option value="">Pilih Range Penghasilan</option>
                <option value="Tidak Berpenghasilan" <?= old('penghasilan_ibu') === 'Tidak Berpenghasilan' ? 'selected' : '' ?>>Tidak Berpenghasilan</option>
                <option value="< Rp 1.000.000" <?= old('penghasilan_ibu') === '< Rp 1.000.000' ? 'selected' : '' ?>>< Rp 1.000.000</option>
                <option value="Rp 1.000.000 - Rp 2.000.000" <?= old('penghasilan_ibu') === 'Rp 1.000.000 - Rp 2.000.000' ? 'selected' : '' ?>>Rp 1.000.000 - Rp 2.000.000</option>
                <option value="Rp 2.000.000 - Rp 5.000.000" <?= old('penghasilan_ibu') === 'Rp 2.000.000 - Rp 5.000.000' ? 'selected' : '' ?>>Rp 2.000.000 - Rp 5.000.000</option>
                <option value="Rp 5.000.000 - Rp 10.000.000" <?= old('penghasilan_ibu') === 'Rp 5.000.000 - Rp 10.000.000' ? 'selected' : '' ?>>Rp 5.000.000 - Rp 10.000.000</option>
                <option value="> Rp 10.000.000" <?= old('penghasilan_ibu') === '> Rp 10.000.000' ? 'selected' : '' ?>>> Rp 10.000.000</option>
            </select>
        </div>

        <!-- HP Ibu -->
        <div class="col-md-6 mb-3">
            <label for="hp_ibu" class="form-label">Nomor HP/WhatsApp Ibu</label>
            <input type="text" class="form-control numeric-only" id="hp_ibu" name="hp_ibu"
                value="<?= old('hp_ibu') ?>" placeholder="Contoh: 08123456789" maxlength="20">
        </div>

        <!-- Alamat Ibu -->
        <div class="col-md-12 mb-3">
            <label for="alamat_ibu" class="form-label">Alamat Ibu</label>
            <textarea class="form-control" id="alamat_ibu" name="alamat_ibu" rows="2"
                placeholder="Masukkan alamat lengkap ibu (kosongkan jika sama dengan alamat santri)"><?= old('alamat_ibu') ?></textarea>
        </div>
    </div>
</div>
