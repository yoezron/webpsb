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
                <option value="SD/MI" <?= old('pendidikan_wali') === 'SD/MI' ? 'selected' : '' ?>>SD/MI</option>
                <option value="SMP/MTs" <?= old('pendidikan_wali') === 'SMP/MTs' ? 'selected' : '' ?>>SMP/MTs</option>
                <option value="SMA/MA/SMK" <?= old('pendidikan_wali') === 'SMA/MA/SMK' ? 'selected' : '' ?>>SMA/MA/SMK</option>
                <option value="D1" <?= old('pendidikan_wali') === 'D1' ? 'selected' : '' ?>>D1</option>
                <option value="D2" <?= old('pendidikan_wali') === 'D2' ? 'selected' : '' ?>>D2</option>
                <option value="D3" <?= old('pendidikan_wali') === 'D3' ? 'selected' : '' ?>>D3</option>
                <option value="D4/S1" <?= old('pendidikan_wali') === 'D4/S1' ? 'selected' : '' ?>>D4/S1</option>
                <option value="S2" <?= old('pendidikan_wali') === 'S2' ? 'selected' : '' ?>>S2</option>
                <option value="S3" <?= old('pendidikan_wali') === 'S3' ? 'selected' : '' ?>>S3</option>
            </select>
        </div>

        <!-- Pekerjaan Wali -->
        <div class="col-md-6 mb-3">
            <label for="pekerjaan_wali" class="form-label">Pekerjaan Utama Wali</label>
            <select class="form-select" id="pekerjaan_wali" name="pekerjaan_wali">
                <option value="">Pilih Pekerjaan</option>
                <option value="Tidak Bekerja" <?= old('pekerjaan_wali') === 'Tidak Bekerja' ? 'selected' : '' ?>>Tidak Bekerja</option>
                <option value="Pensiun" <?= old('pekerjaan_wali') === 'Pensiun' ? 'selected' : '' ?>>Pensiun</option>
                <option value="PNS" <?= old('pekerjaan_wali') === 'PNS' ? 'selected' : '' ?>>PNS</option>
                <option value="TNI/Polri" <?= old('pekerjaan_wali') === 'TNI/Polri' ? 'selected' : '' ?>>TNI/Polri</option>
                <option value="Guru/Dosen" <?= old('pekerjaan_wali') === 'Guru/Dosen' ? 'selected' : '' ?>>Guru/Dosen</option>
                <option value="Pegawai Swasta" <?= old('pekerjaan_wali') === 'Pegawai Swasta' ? 'selected' : '' ?>>Pegawai Swasta</option>
                <option value="Wiraswasta" <?= old('pekerjaan_wali') === 'Wiraswasta' ? 'selected' : '' ?>>Wiraswasta</option>
                <option value="Pengacara/Jaksa/Hakim/Notaris" <?= old('pekerjaan_wali') === 'Pengacara/Jaksa/Hakim/Notaris' ? 'selected' : '' ?>>Pengacara/Jaksa/Hakim/Notaris</option>
                <option value="Seniman/Pelukis/Artis/Sejenis" <?= old('pekerjaan_wali') === 'Seniman/Pelukis/Artis/Sejenis' ? 'selected' : '' ?>>Seniman/Pelukis/Artis/Sejenis</option>
                <option value="Dokter/Bidan/Perawat" <?= old('pekerjaan_wali') === 'Dokter/Bidan/Perawat' ? 'selected' : '' ?>>Dokter/Bidan/Perawat</option>
                <option value="Pilot/Pramugara" <?= old('pekerjaan_wali') === 'Pilot/Pramugara' ? 'selected' : '' ?>>Pilot/Pramugara</option>
                <option value="Pedagang" <?= old('pekerjaan_wali') === 'Pedagang' ? 'selected' : '' ?>>Pedagang</option>
                <option value="Petani/Peternak" <?= old('pekerjaan_wali') === 'Petani/Peternak' ? 'selected' : '' ?>>Petani/Peternak</option>
                <option value="Nelayan" <?= old('pekerjaan_wali') === 'Nelayan' ? 'selected' : '' ?>>Nelayan</option>
                <option value="Buruh (Tani/Pabrik/Bangunan)" <?= old('pekerjaan_wali') === 'Buruh (Tani/Pabrik/Bangunan)' ? 'selected' : '' ?>>Buruh (Tani/Pabrik/Bangunan)</option>
                <option value="Sopir/Masinis/Kondektur" <?= old('pekerjaan_wali') === 'Sopir/Masinis/Kondektur' ? 'selected' : '' ?>>Sopir/Masinis/Kondektur</option>
                <option value="Politikus" <?= old('pekerjaan_wali') === 'Politikus' ? 'selected' : '' ?>>Politikus</option>
                <option value="Lainnya" <?= old('pekerjaan_wali') === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
            </select>
        </div>

        <!-- Penghasilan Wali -->
        <div class="col-md-6 mb-3">
            <label for="penghasilan_wali" class="form-label">Penghasilan Per Bulan</label>
            <select class="form-select" id="penghasilan_wali" name="penghasilan_wali">
                <option value="">Pilih Range Penghasilan</option>
                <option value="< 1 juta" <?= old('penghasilan_wali') === '< 1 juta' ? 'selected' : '' ?>>< Rp 1.000.000</option>
                <option value="1-2 juta" <?= old('penghasilan_wali') === '1-2 juta' ? 'selected' : '' ?>>Rp 1.000.000 - Rp 2.000.000</option>
                <option value="2-3 juta" <?= old('penghasilan_wali') === '2-3 juta' ? 'selected' : '' ?>>Rp 2.000.000 - Rp 3.000.000</option>
                <option value="3-5 juta" <?= old('penghasilan_wali') === '3-5 juta' ? 'selected' : '' ?>>Rp 3.000.000 - Rp 5.000.000</option>
                <option value="5-10 juta" <?= old('penghasilan_wali') === '5-10 juta' ? 'selected' : '' ?>>Rp 5.000.000 - Rp 10.000.000</option>
                <option value="> 10 juta" <?= old('penghasilan_wali') === '> 10 juta' ? 'selected' : '' ?>>> Rp 10.000.000</option>
                <option value="Tidak Berpenghasilan" <?= old('penghasilan_wali') === 'Tidak Berpenghasilan' ? 'selected' : '' ?>>Tidak Berpenghasilan</option>
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
