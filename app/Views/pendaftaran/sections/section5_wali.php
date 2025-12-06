<!-- Section 5: Data Wali -->
<div class="section-card hidden" id="section-5">
    <h2 class="section-title">
        <i class="icofont-users"></i> Data Wali (Jika Ada)
    </h2>

    <div class="alert alert-info">
        <i class="icofont-info-circle"></i> Kosongkan jika tidak memiliki wali atau wali sama dengan orang tua
    </div>

    <div class="row">
        <!-- Nama Wali -->
        <div class="col-md-12 mb-3">
            <label for="nama_wali" class="form-label">Nama Wali</label>
            <input type="text" class="form-control" id="nama_wali" name="nama_wali"
                value="<?= old('nama_wali') ?>" placeholder="Masukkan nama lengkap wali">
        </div>

        <!-- NIK Wali -->
        <div class="col-md-6 mb-3">
            <label for="nik_wali" class="form-label">NIK Wali</label>
            <input type="text" class="form-control numeric-only" id="nik_wali" name="nik_wali"
                value="<?= old('nik_wali') ?>" placeholder="Masukkan NIK wali" maxlength="20">
        </div>

        <!-- Tahun Lahir Wali -->
        <div class="col-md-6 mb-3">
            <label for="tahun_lahir_wali" class="form-label">Tahun Lahir Wali</label>
            <input type="number" class="form-control" id="tahun_lahir_wali" name="tahun_lahir_wali"
                value="<?= old('tahun_lahir_wali') ?>" placeholder="Contoh: 1980" min="1940" max="<?= date('Y') ?>">
        </div>

        <!-- Pendidikan Wali -->
        <div class="col-md-6 mb-3">
            <label for="pendidikan_wali" class="form-label">Pendidikan Terakhir Wali</label>
            <select class="form-select" id="pendidikan_wali" name="pendidikan_wali">
                <option value="">Pilih Pendidikan</option>
                <option value="Tidak Sekolah" <?= old('pendidikan_wali') === 'Tidak Sekolah' ? 'selected' : '' ?>>Tidak Sekolah</option>
                <option value="SD/Sederajat" <?= old('pendidikan_wali') === 'SD/Sederajat' ? 'selected' : '' ?>>SD/Sederajat</option>
                <option value="SMP/Sederajat" <?= old('pendidikan_wali') === 'SMP/Sederajat' ? 'selected' : '' ?>>SMP/Sederajat</option>
                <option value="SMA/Sederajat" <?= old('pendidikan_wali') === 'SMA/Sederajat' ? 'selected' : '' ?>>SMA/Sederajat</option>
                <option value="D1/D2/D3" <?= old('pendidikan_wali') === 'D1/D2/D3' ? 'selected' : '' ?>>D1/D2/D3</option>
                <option value="D4/S1" <?= old('pendidikan_wali') === 'D4/S1' ? 'selected' : '' ?>>D4/S1</option>
                <option value="S2" <?= old('pendidikan_wali') === 'S2' ? 'selected' : '' ?>>S2</option>
                <option value="S3" <?= old('pendidikan_wali') === 'S3' ? 'selected' : '' ?>>S3</option>
            </select>
        </div>

        <!-- Pekerjaan Wali -->
        <div class="col-md-6 mb-3">
            <label for="pekerjaan_wali" class="form-label">Pekerjaan Wali</label>
            <input type="text" class="form-control" id="pekerjaan_wali" name="pekerjaan_wali"
                value="<?= old('pekerjaan_wali') ?>" placeholder="Contoh: Wiraswasta, PNS">
        </div>

        <!-- Penghasilan Wali -->
        <div class="col-md-6 mb-3">
            <label for="penghasilan_wali" class="form-label">Penghasilan Per Bulan</label>
            <select class="form-select" id="penghasilan_wali" name="penghasilan_wali">
                <option value="">Pilih Range Penghasilan</option>
                <option value="< Rp 1.000.000" <?= old('penghasilan_wali') === '< Rp 1.000.000' ? 'selected' : '' ?>>< Rp 1.000.000</option>
                <option value="Rp 1.000.000 - Rp 2.000.000" <?= old('penghasilan_wali') === 'Rp 1.000.000 - Rp 2.000.000' ? 'selected' : '' ?>>Rp 1.000.000 - Rp 2.000.000</option>
                <option value="Rp 2.000.000 - Rp 5.000.000" <?= old('penghasilan_wali') === 'Rp 2.000.000 - Rp 5.000.000' ? 'selected' : '' ?>>Rp 2.000.000 - Rp 5.000.000</option>
                <option value="Rp 5.000.000 - Rp 10.000.000" <?= old('penghasilan_wali') === 'Rp 5.000.000 - Rp 10.000.000' ? 'selected' : '' ?>>Rp 5.000.000 - Rp 10.000.000</option>
                <option value="> Rp 10.000.000" <?= old('penghasilan_wali') === '> Rp 10.000.000' ? 'selected' : '' ?>>> Rp 10.000.000</option>
            </select>
        </div>

        <!-- HP Wali -->
        <div class="col-md-6 mb-3">
            <label for="hp_wali" class="form-label">Nomor HP/WhatsApp Wali</label>
            <input type="text" class="form-control numeric-only" id="hp_wali" name="hp_wali"
                value="<?= old('hp_wali') ?>" placeholder="Contoh: 08123456789" maxlength="20">
        </div>
    </div>
</div>
