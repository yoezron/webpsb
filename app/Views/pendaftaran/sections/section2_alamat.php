<!-- Section 2: Data Alamat -->
<div class="section-card hidden" id="section-2">
    <h2 class="section-title">
        <i class="icofont-location-pin"></i> Data Tempat Tinggal
    </h2>

    <div class="row">
        <!-- Nomor KK -->
        <div class="col-md-6 mb-3">
            <label for="nomor_kk" class="form-label">Nomor Kartu Keluarga (KK)</label>
            <input type="text" class="form-control numeric-only" id="nomor_kk" name="nomor_kk"
                value="<?= old('nomor_kk') ?>" placeholder="Masukkan nomor KK" maxlength="20">
        </div>

        <!-- Nama Kepala Keluarga -->
        <div class="col-md-6 mb-3">
            <label for="nama_kepala_keluarga" class="form-label required">Nama Kepala Keluarga</label>
            <input type="text" class="form-control" id="nama_kepala_keluarga" name="nama_kepala_keluarga"
                value="<?= old('nama_kepala_keluarga') ?>" placeholder="Sesuai Kartu Keluarga" required>
        </div>

        <!-- Jenis Tempat Tinggal -->
        <div class="col-md-6 mb-3">
            <label for="jenis_tempat_tinggal" class="form-label">Jenis Tempat Tinggal</label>
            <select class="form-select" id="jenis_tempat_tinggal" name="jenis_tempat_tinggal">
                <option value="">Pilih Jenis</option>
                <option value="Milik Sendiri" <?= old('jenis_tempat_tinggal') === 'Milik Sendiri' ? 'selected' : '' ?>>Milik Sendiri</option>
                <option value="Rumah Orang Tua" <?= old('jenis_tempat_tinggal') === 'Rumah Orang Tua' ? 'selected' : '' ?>>Rumah Orang Tua</option>
                <option value="Rumah Saudara" <?= old('jenis_tempat_tinggal') === 'Rumah Saudara' ? 'selected' : '' ?>>Rumah Saudara</option>
                <option value="Rumah Dinas" <?= old('jenis_tempat_tinggal') === 'Rumah Dinas' ? 'selected' : '' ?>>Rumah Dinas</option>
                <option value="Sewa/Kontrak" <?= old('jenis_tempat_tinggal') === 'Sewa/Kontrak' ? 'selected' : '' ?>>Sewa/Kontrak</option>
                <option value="Lainnya" <?= old('jenis_tempat_tinggal') === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
            </select>
        </div>

        <!-- Alamat -->
        <div class="col-md-12 mb-3">
            <label for="alamat" class="form-label">Alamat Lengkap</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3"
                placeholder="Masukkan alamat lengkap (Jalan, Nomor Rumah)"><?= old('alamat') ?></textarea>
        </div>

        <!-- RT/RW -->
        <div class="col-md-6 mb-3">
            <label for="rt_rw" class="form-label required">RT/RW</label>
            <input type="text" class="form-control" id="rt_rw" name="rt_rw"
                value="<?= old('rt_rw') ?>" placeholder="Contoh: 001/002" required>
        </div>

        <!-- Desa -->
        <div class="col-md-6 mb-3">
            <label for="desa" class="form-label">Desa/Kelurahan</label>
            <input type="text" class="form-control" id="desa" name="desa"
                value="<?= old('desa') ?>" placeholder="Masukkan desa/kelurahan">
        </div>

        <!-- Kecamatan -->
        <div class="col-md-6 mb-3">
            <label for="kecamatan" class="form-label">Kecamatan</label>
            <input type="text" class="form-control" id="kecamatan" name="kecamatan"
                value="<?= old('kecamatan') ?>" placeholder="Masukkan kecamatan">
        </div>

        <!-- Kabupaten -->
        <div class="col-md-6 mb-3">
            <label for="kabupaten" class="form-label">Kabupaten/Kota</label>
            <input type="text" class="form-control" id="kabupaten" name="kabupaten"
                value="<?= old('kabupaten') ?>" placeholder="Masukkan kabupaten/kota">
        </div>

        <!-- Provinsi -->
        <div class="col-md-6 mb-3">
            <label for="provinsi" class="form-label">Provinsi</label>
            <input type="text" class="form-control" id="provinsi" name="provinsi"
                value="<?= old('provinsi') ?>" placeholder="Masukkan provinsi">
        </div>

        <!-- Kode Pos -->
        <div class="col-md-6 mb-3">
            <label for="kode_pos" class="form-label">Kode Pos</label>
            <input type="text" class="form-control numeric-only" id="kode_pos" name="kode_pos"
                value="<?= old('kode_pos') ?>" placeholder="Masukkan kode pos" maxlength="10">
        </div>

        <!-- Jarak ke Sekolah -->
        <div class="col-md-6 mb-3">
            <label for="jarak_ke_sekolah" class="form-label">Jarak ke Sekolah</label>
            <select class="form-select" id="jarak_ke_sekolah" name="jarak_ke_sekolah">
                <option value="">Pilih Jarak</option>
                <option value="< 1 km" <?= old('jarak_ke_sekolah') === '< 1 km' ? 'selected' : '' ?>>< 1 km</option>
                <option value="1-5 km" <?= old('jarak_ke_sekolah') === '1-5 km' ? 'selected' : '' ?>>1-5 km</option>
                <option value="5-10 km" <?= old('jarak_ke_sekolah') === '5-10 km' ? 'selected' : '' ?>>5-10 km</option>
                <option value="10-20 km" <?= old('jarak_ke_sekolah') === '10-20 km' ? 'selected' : '' ?>>10-20 km</option>
                <option value="> 20 km" <?= old('jarak_ke_sekolah') === '> 20 km' ? 'selected' : '' ?>>> 20 km</option>
            </select>
        </div>

        <!-- Waktu Tempuh -->
        <div class="col-md-6 mb-3">
            <label for="waktu_tempuh" class="form-label">Waktu Tempuh ke Sekolah</label>
            <select class="form-select" id="waktu_tempuh" name="waktu_tempuh">
                <option value="">Pilih Waktu Tempuh</option>
                <option value="< 15 menit" <?= old('waktu_tempuh') === '< 15 menit' ? 'selected' : '' ?>>< 15 menit</option>
                <option value="15-30 menit" <?= old('waktu_tempuh') === '15-30 menit' ? 'selected' : '' ?>>15-30 menit</option>
                <option value="30-60 menit" <?= old('waktu_tempuh') === '30-60 menit' ? 'selected' : '' ?>>30-60 menit</option>
                <option value="> 60 menit" <?= old('waktu_tempuh') === '> 60 menit' ? 'selected' : '' ?>>> 60 menit</option>
            </select>
        </div>

        <!-- Transportasi -->
        <div class="col-md-6 mb-3">
            <label for="transportasi" class="form-label">Moda Transportasi</label>
            <select class="form-select" id="transportasi" name="transportasi">
                <option value="">Pilih Transportasi</option>
                <option value="Jalan Kaki" <?= old('transportasi') === 'Jalan Kaki' ? 'selected' : '' ?>>Jalan Kaki</option>
                <option value="Sepeda" <?= old('transportasi') === 'Sepeda' ? 'selected' : '' ?>>Sepeda</option>
                <option value="Motor" <?= old('transportasi') === 'Motor' ? 'selected' : '' ?>>Motor</option>
                <option value="Mobil" <?= old('transportasi') === 'Mobil' ? 'selected' : '' ?>>Mobil</option>
                <option value="Angkutan Umum" <?= old('transportasi') === 'Angkutan Umum' ? 'selected' : '' ?>>Angkutan Umum</option>
                <option value="Ojek Online" <?= old('transportasi') === 'Ojek Online' ? 'selected' : '' ?>>Ojek Online</option>
                <option value="Lainnya" <?= old('transportasi') === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
            </select>
        </div>

        <!-- Email -->
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email"
                value="<?= old('email') ?>" placeholder="Masukkan email">
        </div>

        <!-- Media Sosial -->
        <div class="col-md-6 mb-3">
            <label for="media_sosial" class="form-label">Media Sosial</label>
            <input type="text" class="form-control" id="media_sosial" name="media_sosial"
                value="<?= old('media_sosial') ?>" placeholder="Contoh: @username_instagram">
        </div>

        <!-- Calon Siswa Tinggal Bersama -->
        <div class="col-md-6 mb-3">
            <label for="tinggal_bersama" class="form-label required">Calon Siswa Tinggal Bersama</label>
            <select class="form-select" id="tinggal_bersama" name="tinggal_bersama" required>
                <option value="">Pilih</option>
                <option value="Tinggal dengan ayah kandung" <?= old('tinggal_bersama') === 'Tinggal dengan ayah kandung' ? 'selected' : '' ?>>Tinggal dengan ayah kandung</option>
                <option value="Tinggal dengan Ibu Kandung" <?= old('tinggal_bersama') === 'Tinggal dengan Ibu Kandung' ? 'selected' : '' ?>>Tinggal dengan Ibu Kandung</option>
                <option value="Tinggal dengan Wali" <?= old('tinggal_bersama') === 'Tinggal dengan Wali' ? 'selected' : '' ?>>Tinggal dengan Wali</option>
                <option value="Ikut Saudara/Kerabat" <?= old('tinggal_bersama') === 'Ikut Saudara/Kerabat' ? 'selected' : '' ?>>Ikut Saudara/Kerabat</option>
                <option value="Kontrak/Kost" <?= old('tinggal_bersama') === 'Tinggal dengan Kontrak/Kost' ? 'selected' : '' ?>>Kontrak/Kost</option>
                <option value="Panti Asuhan" <?= old('tinggal_bersama') === 'Panti Asuhan' ? 'selected' : '' ?>>Panti Asuhan</option>
                <option value="Rumah Singgah" <?= old('tinggal_bersama') === 'Rumah Singgah' ? 'selected' : '' ?>>Rumah Singgah</option>
                <option value="Lainnya" <?= old('tinggal_bersama') === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
            </select>
        </div>
    </div>
</div>
