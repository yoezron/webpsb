<!-- Section 5: Data Wali -->
<div class="section-card hidden" id="section-5">
    <h2 class="section-title">
        <i class="icofont-ui-user-group"></i> Data Wali (Opsional)
    </h2>

    <div class="alert alert-info mb-4">
        <i class="icofont-info-circle"></i> Data wali hanya perlu diisi jika berbeda dengan orang tua atau jika orang tua tidak ada.
    </div>

    <div class="row">
        <!-- Nama Wali -->
        <div class="col-md-6 mb-3">
            <label for="nama_wali" class="form-label">Nama Lengkap Wali</label>
            <input type="text" class="form-control" id="nama_wali" name="nama_wali"
                value="<?= old('nama_wali', $wali['nama_wali'] ?? '') ?>" placeholder="Masukkan nama lengkap wali">
        </div>

        <!-- NIK Wali -->
        <div class="col-md-6 mb-3">
            <label for="nik_wali" class="form-label">NIK Wali</label>
            <input type="text" class="form-control numeric-only" id="nik_wali" name="nik_wali"
                value="<?= old('nik_wali', $wali['nik_wali'] ?? '') ?>" placeholder="Masukkan NIK wali" maxlength="16">
        </div>

        <!-- Tahun Lahir Wali -->
        <div class="col-md-6 mb-3">
            <label for="tahun_lahir_wali" class="form-label">Tahun Lahir</label>
            <input type="text" class="form-control numeric-only" id="tahun_lahir_wali" name="tahun_lahir_wali"
                value="<?= old('tahun_lahir_wali', $wali['tahun_lahir_wali'] ?? '') ?>" placeholder="Contoh: 1980" maxlength="4">
        </div>

        <!-- Pendidikan Wali -->
        <div class="col-md-6 mb-3">
            <label for="pendidikan_wali" class="form-label">Pendidikan Terakhir</label>
            <select class="form-select" id="pendidikan_wali" name="pendidikan_wali">
                <option value="">Pilih Pendidikan</option>
                <option value="Tidak Sekolah" <?= old('pendidikan_wali', $wali['pendidikan_wali'] ?? '') === 'Tidak Sekolah' ? 'selected' : '' ?>>Tidak Sekolah</option>
                <option value="SD/MI" <?= old('pendidikan_wali', $wali['pendidikan_wali'] ?? '') === 'SD/MI' ? 'selected' : '' ?>>SD/MI</option>
                <option value="SMP/MTs" <?= old('pendidikan_wali', $wali['pendidikan_wali'] ?? '') === 'SMP/MTs' ? 'selected' : '' ?>>SMP/MTs</option>
                <option value="SMA/MA/SMK" <?= old('pendidikan_wali', $wali['pendidikan_wali'] ?? '') === 'SMA/MA/SMK' ? 'selected' : '' ?>>SMA/MA/SMK</option>
                <option value="D1" <?= old('pendidikan_wali', $wali['pendidikan_wali'] ?? '') === 'D1' ? 'selected' : '' ?>>D1</option>
                <option value="D2" <?= old('pendidikan_wali', $wali['pendidikan_wali'] ?? '') === 'D2' ? 'selected' : '' ?>>D2</option>
                <option value="D3" <?= old('pendidikan_wali', $wali['pendidikan_wali'] ?? '') === 'D3' ? 'selected' : '' ?>>D3</option>
                <option value="D4/S1" <?= old('pendidikan_wali', $wali['pendidikan_wali'] ?? '') === 'D4/S1' ? 'selected' : '' ?>>D4/S1</option>
                <option value="S2" <?= old('pendidikan_wali', $wali['pendidikan_wali'] ?? '') === 'S2' ? 'selected' : '' ?>>S2</option>
                <option value="S3" <?= old('pendidikan_wali', $wali['pendidikan_wali'] ?? '') === 'S3' ? 'selected' : '' ?>>S3</option>
            </select>
        </div>

        <!-- Pekerjaan Wali -->
        <div class="col-md-6 mb-3">
            <label for="pekerjaan_wali" class="form-label">Pekerjaan</label>
            <input type="text" class="form-control" id="pekerjaan_wali" name="pekerjaan_wali"
                value="<?= old('pekerjaan_wali', $wali['pekerjaan_wali'] ?? '') ?>" placeholder="Masukkan pekerjaan wali">
        </div>

        <!-- Penghasilan Wali -->
        <div class="col-md-6 mb-3">
            <label for="penghasilan_wali" class="form-label">Penghasilan per Bulan</label>
            <select class="form-select" id="penghasilan_wali" name="penghasilan_wali">
                <option value="">Pilih Penghasilan</option>
                <option value="< 1 juta" <?= old('penghasilan_wali', $wali['penghasilan_wali'] ?? '') === '< 1 juta' ? 'selected' : '' ?>>< 1 juta</option>
                <option value="1-2 juta" <?= old('penghasilan_wali', $wali['penghasilan_wali'] ?? '') === '1-2 juta' ? 'selected' : '' ?>>1-2 juta</option>
                <option value="2-3 juta" <?= old('penghasilan_wali', $wali['penghasilan_wali'] ?? '') === '2-3 juta' ? 'selected' : '' ?>>2-3 juta</option>
                <option value="3-5 juta" <?= old('penghasilan_wali', $wali['penghasilan_wali'] ?? '') === '3-5 juta' ? 'selected' : '' ?>>3-5 juta</option>
                <option value="5-10 juta" <?= old('penghasilan_wali', $wali['penghasilan_wali'] ?? '') === '5-10 juta' ? 'selected' : '' ?>>5-10 juta</option>
                <option value="> 10 juta" <?= old('penghasilan_wali', $wali['penghasilan_wali'] ?? '') === '> 10 juta' ? 'selected' : '' ?>>> 10 juta</option>
                <option value="Tidak Berpenghasilan" <?= old('penghasilan_wali', $wali['penghasilan_wali'] ?? '') === 'Tidak Berpenghasilan' ? 'selected' : '' ?>>Tidak Berpenghasilan</option>
            </select>
        </div>

        <!-- No HP Wali -->
        <div class="col-md-6 mb-3">
            <label for="hp_wali" class="form-label">No. HP/WhatsApp</label>
            <input type="text" class="form-control numeric-only" id="hp_wali" name="hp_wali"
                value="<?= old('hp_wali', $wali['hp_wali'] ?? '') ?>" placeholder="Masukkan no. HP wali" maxlength="20">
        </div>
    </div>
</div>
