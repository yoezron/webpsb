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
                value="<?= old('nama_ibu', $ibu['nama_ibu'] ?? '') ?>" placeholder="Masukkan nama lengkap ibu">
        </div>

        <!-- NIK Ibu -->
        <div class="col-md-6 mb-3">
            <label for="nik_ibu" class="form-label">NIK Ibu</label>
            <input type="text" class="form-control numeric-only" id="nik_ibu" name="nik_ibu"
                value="<?= old('nik_ibu', $ibu['nik_ibu'] ?? '') ?>" placeholder="Masukkan NIK ibu" maxlength="20">
        </div>

        <!-- Tempat Lahir Ibu -->
        <div class="col-md-6 mb-3">
            <label for="tempat_lahir_ibu" class="form-label">Tempat Lahir Ibu</label>
            <input type="text" class="form-control" id="tempat_lahir_ibu" name="tempat_lahir_ibu"
                value="<?= old('tempat_lahir_ibu', $ibu['tempat_lahir_ibu'] ?? '') ?>" placeholder="Masukkan tempat lahir ibu">
        </div>

        <!-- Tanggal Lahir Ibu -->
        <div class="col-md-6 mb-3">
            <label for="tanggal_lahir_ibu" class="form-label">Tanggal Lahir Ibu</label>
            <input type="date" class="form-control" id="tanggal_lahir_ibu" name="tanggal_lahir_ibu"
                value="<?= old('tanggal_lahir_ibu', $ibu['tanggal_lahir_ibu'] ?? '') ?>">
        </div>

        <!-- Status Ibu -->
        <div class="col-md-6 mb-3">
            <label for="status_ibu" class="form-label">Status Ibu</label>
            <select class="form-select" id="status_ibu" name="status_ibu">
                <option value="">Pilih Status</option>
                <option value="Masih Hidup" <?= old('status_ibu', $ibu['status_ibu'] ?? '') === 'Masih Hidup' ? 'selected' : '' ?>>Masih Hidup</option>
                <option value="Sudah Meninggal" <?= old('status_ibu', $ibu['status_ibu'] ?? '') === 'Sudah Meninggal' ? 'selected' : '' ?>>Sudah Meninggal</option>
                <option value="Tidak Diketahui" <?= old('status_ibu', $ibu['status_ibu'] ?? '') === 'Tidak Diketahui' ? 'selected' : '' ?>>Tidak Diketahui</option>
            </select>
        </div>

        <!-- Pendidikan Ibu -->
        <div class="col-md-6 mb-3">
            <label for="pendidikan_ibu" class="form-label">Pendidikan Terakhir Ibu</label>
            <select class="form-select" id="pendidikan_ibu" name="pendidikan_ibu">
                <option value="">Pilih Pendidikan</option>
                <option value="Tidak Sekolah" <?= old('pendidikan_ibu', $ibu['pendidikan_ibu'] ?? '') === 'Tidak Sekolah' ? 'selected' : '' ?>>Tidak Sekolah</option>
                <option value="SD/MI" <?= old('pendidikan_ibu', $ibu['pendidikan_ibu'] ?? '') === 'SD/MI' ? 'selected' : '' ?>>SD/MI</option>
                <option value="SMP/MTs" <?= old('pendidikan_ibu', $ibu['pendidikan_ibu'] ?? '') === 'SMP/MTs' ? 'selected' : '' ?>>SMP/MTs</option>
                <option value="SMA/MA/SMK" <?= old('pendidikan_ibu', $ibu['pendidikan_ibu'] ?? '') === 'SMA/MA/SMK' ? 'selected' : '' ?>>SMA/MA/SMK</option>
                <option value="D1" <?= old('pendidikan_ibu', $ibu['pendidikan_ibu'] ?? '') === 'D1' ? 'selected' : '' ?>>D1</option>
                <option value="D2" <?= old('pendidikan_ibu', $ibu['pendidikan_ibu'] ?? '') === 'D2' ? 'selected' : '' ?>>D2</option>
                <option value="D3" <?= old('pendidikan_ibu', $ibu['pendidikan_ibu'] ?? '') === 'D3' ? 'selected' : '' ?>>D3</option>
                <option value="D4/S1" <?= old('pendidikan_ibu', $ibu['pendidikan_ibu'] ?? '') === 'D4/S1' ? 'selected' : '' ?>>D4/S1</option>
                <option value="S2" <?= old('pendidikan_ibu', $ibu['pendidikan_ibu'] ?? '') === 'S2' ? 'selected' : '' ?>>S2</option>
                <option value="S3" <?= old('pendidikan_ibu', $ibu['pendidikan_ibu'] ?? '') === 'S3' ? 'selected' : '' ?>>S3</option>
            </select>
        </div>

        <!-- Pekerjaan Ibu -->
        <div class="col-md-6 mb-3">
            <label for="pekerjaan_ibu" class="form-label required">Pekerjaan Utama Ibu</label>
            <select class="form-select" id="pekerjaan_ibu" name="pekerjaan_ibu" required>
                <option value="">Pilih Pekerjaan</option>
                <option value="Tidak Bekerja" <?= old('pekerjaan_ibu', $ibu['pekerjaan_ibu'] ?? '') === 'Tidak Bekerja' ? 'selected' : '' ?>>Tidak Bekerja</option>
                <option value="Pensiun" <?= old('pekerjaan_ibu', $ibu['pekerjaan_ibu'] ?? '') === 'Pensiun' ? 'selected' : '' ?>>Pensiun</option>
                <option value="PNS" <?= old('pekerjaan_ibu', $ibu['pekerjaan_ibu'] ?? '') === 'PNS' ? 'selected' : '' ?>>PNS</option>
                <option value="TNI/Polri" <?= old('pekerjaan_ibu', $ibu['pekerjaan_ibu'] ?? '') === 'TNI/Polri' ? 'selected' : '' ?>>TNI/Polri</option>
                <option value="Guru/Dosen" <?= old('pekerjaan_ibu', $ibu['pekerjaan_ibu'] ?? '') === 'Guru/Dosen' ? 'selected' : '' ?>>Guru/Dosen</option>
                <option value="Pegawai Swasta" <?= old('pekerjaan_ibu', $ibu['pekerjaan_ibu'] ?? '') === 'Pegawai Swasta' ? 'selected' : '' ?>>Pegawai Swasta</option>
                <option value="Wiraswasta" <?= old('pekerjaan_ibu', $ibu['pekerjaan_ibu'] ?? '') === 'Wiraswasta' ? 'selected' : '' ?>>Wiraswasta</option>
                <option value="Pengacara/Jaksa/Hakim/Notaris" <?= old('pekerjaan_ibu', $ibu['pekerjaan_ibu'] ?? '') === 'Pengacara/Jaksa/Hakim/Notaris' ? 'selected' : '' ?>>Pengacara/Jaksa/Hakim/Notaris</option>
                <option value="Seniman/Pelukis/Artis/Sejenis" <?= old('pekerjaan_ibu', $ibu['pekerjaan_ibu'] ?? '') === 'Seniman/Pelukis/Artis/Sejenis' ? 'selected' : '' ?>>Seniman/Pelukis/Artis/Sejenis</option>
                <option value="Dokter/Bidan/Perawat" <?= old('pekerjaan_ibu', $ibu['pekerjaan_ibu'] ?? '') === 'Dokter/Bidan/Perawat' ? 'selected' : '' ?>>Dokter/Bidan/Perawat</option>
                <option value="Pilot/Pramugara" <?= old('pekerjaan_ibu', $ibu['pekerjaan_ibu'] ?? '') === 'Pilot/Pramugara' ? 'selected' : '' ?>>Pilot/Pramugara</option>
                <option value="Pedagang" <?= old('pekerjaan_ibu', $ibu['pekerjaan_ibu'] ?? '') === 'Pedagang' ? 'selected' : '' ?>>Pedagang</option>
                <option value="Petani/Peternak" <?= old('pekerjaan_ibu', $ibu['pekerjaan_ibu'] ?? '') === 'Petani/Peternak' ? 'selected' : '' ?>>Petani/Peternak</option>
                <option value="Nelayan" <?= old('pekerjaan_ibu', $ibu['pekerjaan_ibu'] ?? '') === 'Nelayan' ? 'selected' : '' ?>>Nelayan</option>
                <option value="Buruh (Tani/Pabrik/Bangunan)" <?= old('pekerjaan_ibu', $ibu['pekerjaan_ibu'] ?? '') === 'Buruh (Tani/Pabrik/Bangunan)' ? 'selected' : '' ?>>Buruh (Tani/Pabrik/Bangunan)</option>
                <option value="Sopir/Masinis/Kondektur" <?= old('pekerjaan_ibu', $ibu['pekerjaan_ibu'] ?? '') === 'Sopir/Masinis/Kondektur' ? 'selected' : '' ?>>Sopir/Masinis/Kondektur</option>
                <option value="Politikus" <?= old('pekerjaan_ibu', $ibu['pekerjaan_ibu'] ?? '') === 'Politikus' ? 'selected' : '' ?>>Politikus</option>
                <option value="Lainnya" <?= old('pekerjaan_ibu', $ibu['pekerjaan_ibu'] ?? '') === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
            </select>
        </div>

        <!-- Penghasilan Ibu -->
        <div class="col-md-6 mb-3">
            <label for="penghasilan_ibu" class="form-label required">Penghasilan Per Bulan</label>
            <select class="form-select" id="penghasilan_ibu" name="penghasilan_ibu" required>
                <option value="">Pilih Range Penghasilan</option>
                <option value="Dibawah 800.000" <?= old('penghasilan_ibu', $ibu['penghasilan_ibu'] ?? '') === 'Dibawah 800.000' ? 'selected' : '' ?>>Dibawah Rp 800.000</option>
                <option value="800.001-1.200.000" <?= old('penghasilan_ibu', $ibu['penghasilan_ibu'] ?? '') === '800.001-1.200.000' ? 'selected' : '' ?>>Rp 800.001 - Rp 1.200.000</option>
                <option value="1.200.001-1.800.000" <?= old('penghasilan_ibu', $ibu['penghasilan_ibu'] ?? '') === '1.200.001-1.800.000' ? 'selected' : '' ?>>Rp 1.200.001 - Rp 1.800.000</option>
                <option value="1.800.001-2.500.000" <?= old('penghasilan_ibu', $ibu['penghasilan_ibu'] ?? '') === '1.800.001-2.500.000' ? 'selected' : '' ?>>Rp 1.800.001 - Rp 2.500.000</option>
                <option value="2.500.001-3.500.000" <?= old('penghasilan_ibu', $ibu['penghasilan_ibu'] ?? '') === '2.500.001-3.500.000' ? 'selected' : '' ?>>Rp 2.500.001 - Rp 3.500.000</option>
                <option value="3.500.001-4.800.000" <?= old('penghasilan_ibu', $ibu['penghasilan_ibu'] ?? '') === '3.500.001-4.800.000' ? 'selected' : '' ?>>Rp 3.500.001 - Rp 4.800.000</option>
                <option value="4.800.001-6.500.000" <?= old('penghasilan_ibu', $ibu['penghasilan_ibu'] ?? '') === '4.800.001-6.500.000' ? 'selected' : '' ?>>Rp 4.800.001 - Rp 6.500.000</option>
                <option value="6.500.001-10.000.000" <?= old('penghasilan_ibu', $ibu['penghasilan_ibu'] ?? '') === '6.500.001-10.000.000' ? 'selected' : '' ?>>Rp 6.500.001 - Rp 10.000.000</option>
                <option value="10.000.001-20.000.000" <?= old('penghasilan_ibu', $ibu['penghasilan_ibu'] ?? '') === '10.000.001-20.000.000' ? 'selected' : '' ?>>Rp 10.000.001 - Rp 20.000.000</option>
                <option value="Diatas 20.000.000" <?= old('penghasilan_ibu', $ibu['penghasilan_ibu'] ?? '') === 'Diatas 20.000.000' ? 'selected' : '' ?>>Diatas Rp 20.000.000</option>
            </select>
        </div>

        <!-- HP Ibu -->
        <div class="col-md-6 mb-3">
            <label for="hp_ibu" class="form-label">Nomor HP/WhatsApp Ibu <span class="text-danger">*</span></label>
            <input type="text" class="form-control numeric-only" id="hp_ibu" name="hp_ibu"
                value="<?= old('hp_ibu', $ibu['hp_ibu'] ?? '') ?>" placeholder="Contoh: 08123456789" maxlength="20" required>
        </div>

        <!-- Alamat Ibu -->
        <div class="col-md-12 mb-3">
            <label for="alamat_ibu" class="form-label">Alamat Ibu</label>
            <textarea class="form-control" id="alamat_ibu" name="alamat_ibu" rows="2"
                placeholder="Masukkan alamat lengkap ibu (kosongkan jika sama dengan alamat santri)"><?= old('alamat_ibu', $ibu['alamat_ibu'] ?? '') ?></textarea>
        </div>
    </div>
</div>
