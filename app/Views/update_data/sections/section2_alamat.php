<!-- Section 2: Data Alamat -->
<div class="section-card hidden" id="section-2">
    <h2 class="section-title">
        <i class="icofont-location-pin"></i> Data Alamat
    </h2>

    <div class="row">
        <!-- Nomor KK -->
        <div class="col-md-6 mb-3">
            <label for="nomor_kk" class="form-label">Nomor Kartu Keluarga</label>
            <input type="text" class="form-control numeric-only" id="nomor_kk" name="nomor_kk"
                value="<?= old('nomor_kk', $alamat['nomor_kk'] ?? '') ?>" placeholder="Masukkan No. KK" maxlength="16">
        </div>

        <!-- Nama Kepala Keluarga (Sprint 2) -->
        <div class="col-md-6 mb-3">
            <label for="nama_kepala_keluarga" class="form-label">Nama Kepala Keluarga</label>
            <input type="text" class="form-control" id="nama_kepala_keluarga" name="nama_kepala_keluarga"
                value="<?= old('nama_kepala_keluarga', $alamat['nama_kepala_keluarga'] ?? '') ?>" placeholder="Sesuai Kartu Keluarga">
        </div>

        <!-- Jenis Tempat Tinggal -->
        <div class="col-md-6 mb-3">
            <label for="jenis_tempat_tinggal" class="form-label">Jenis Tempat Tinggal</label>
            <select class="form-select" id="jenis_tempat_tinggal" name="jenis_tempat_tinggal">
                <option value="">Pilih Jenis Tempat Tinggal</option>
                <option value="Milik Sendiri" <?= old('jenis_tempat_tinggal', $alamat['jenis_tempat_tinggal'] ?? '') === 'Milik Sendiri' ? 'selected' : '' ?>>Milik Sendiri</option>
                <option value="Rumah Orang Tua" <?= old('jenis_tempat_tinggal', $alamat['jenis_tempat_tinggal'] ?? '') === 'Rumah Orang Tua' ? 'selected' : '' ?>>Rumah Orang Tua</option>
                <option value="Rumah Saudara" <?= old('jenis_tempat_tinggal', $alamat['jenis_tempat_tinggal'] ?? '') === 'Rumah Saudara' ? 'selected' : '' ?>>Rumah Saudara</option>
                <option value="Rumah Dinas" <?= old('jenis_tempat_tinggal', $alamat['jenis_tempat_tinggal'] ?? '') === 'Rumah Dinas' ? 'selected' : '' ?>>Rumah Dinas</option>
                <option value="Sewa/Kontrak" <?= old('jenis_tempat_tinggal', $alamat['jenis_tempat_tinggal'] ?? '') === 'Sewa/Kontrak' ? 'selected' : '' ?>>Sewa/Kontrak</option>
                <option value="Lainnya" <?= old('jenis_tempat_tinggal', $alamat['jenis_tempat_tinggal'] ?? '') === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
            </select>
        </div>

        <!-- Alamat Lengkap -->
        <div class="col-md-12 mb-3">
            <label for="alamat" class="form-label">Alamat Lengkap</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3"
                placeholder="Masukkan alamat lengkap (Jalan, No. Rumah)"><?= old('alamat', $alamat['alamat'] ?? '') ?></textarea>
        </div>

        <!-- RT/RW (Sprint 2) -->
        <div class="col-md-6 mb-3">
            <label for="rt_rw" class="form-label">RT/RW</label>
            <input type="text" class="form-control" id="rt_rw" name="rt_rw"
                value="<?= old('rt_rw', $alamat['rt_rw'] ?? '') ?>" placeholder="Contoh: 001/002">
        </div>

        <!-- Desa/Kelurahan -->
        <div class="col-md-6 mb-3">
            <label for="desa" class="form-label">Desa/Kelurahan</label>
            <input type="text" class="form-control" id="desa" name="desa"
                value="<?= old('desa', $alamat['desa'] ?? '') ?>" placeholder="Masukkan desa/kelurahan">
        </div>

        <!-- Kecamatan -->
        <div class="col-md-6 mb-3">
            <label for="kecamatan" class="form-label">Kecamatan</label>
            <input type="text" class="form-control" id="kecamatan" name="kecamatan"
                value="<?= old('kecamatan', $alamat['kecamatan'] ?? '') ?>" placeholder="Masukkan kecamatan">
        </div>

        <!-- Kabupaten/Kota -->
        <div class="col-md-6 mb-3">
            <label for="kabupaten" class="form-label">Kabupaten/Kota</label>
            <input type="text" class="form-control" id="kabupaten" name="kabupaten"
                value="<?= old('kabupaten', $alamat['kabupaten'] ?? '') ?>" placeholder="Masukkan kabupaten/kota">
        </div>

        <!-- Provinsi -->
        <div class="col-md-6 mb-3">
            <label for="provinsi" class="form-label">Provinsi</label>
            <input type="text" class="form-control" id="provinsi" name="provinsi"
                value="<?= old('provinsi', $alamat['provinsi'] ?? '') ?>" placeholder="Masukkan provinsi">
        </div>

        <!-- Kode Pos -->
        <div class="col-md-6 mb-3">
            <label for="kode_pos" class="form-label">Kode Pos</label>
            <input type="text" class="form-control numeric-only" id="kode_pos" name="kode_pos"
                value="<?= old('kode_pos', $alamat['kode_pos'] ?? '') ?>" placeholder="Masukkan kode pos" maxlength="5">
        </div>

        <!-- Tinggal Bersama (Sprint 2) -->
        <div class="col-md-6 mb-3">
            <label for="tinggal_bersama" class="form-label">Calon Siswa Tinggal Bersama</label>
            <select class="form-select" id="tinggal_bersama" name="tinggal_bersama">
                <option value="">Pilih</option>
                <option value="Tinggal dengan ayah kandung" <?= old('tinggal_bersama', $alamat['tinggal_bersama'] ?? '') === 'Tinggal dengan ayah kandung' ? 'selected' : '' ?>>Tinggal dengan ayah kandung</option>
                <option value="Tinggal dengan Ibu Kandung" <?= old('tinggal_bersama', $alamat['tinggal_bersama'] ?? '') === 'Tinggal dengan Ibu Kandung' ? 'selected' : '' ?>>Tinggal dengan Ibu Kandung</option>
                <option value="Tinggal dengan Wali" <?= old('tinggal_bersama', $alamat['tinggal_bersama'] ?? '') === 'Tinggal dengan Wali' ? 'selected' : '' ?>>Tinggal dengan Wali</option>
                <option value="Ikut Saudara/Kerabat" <?= old('tinggal_bersama', $alamat['tinggal_bersama'] ?? '') === 'Ikut Saudara/Kerabat' ? 'selected' : '' ?>>Ikut Saudara/Kerabat</option>
                <option value="Kontrak/Kost" <?= old('tinggal_bersama', $alamat['tinggal_bersama'] ?? '') === 'Tinggal dengan Kontrak/Kost' ? 'selected' : '' ?>>Kontrak/Kost</option>
                <option value="Panti Asuhan" <?= old('tinggal_bersama', $alamat['tinggal_bersama'] ?? '') === 'Panti Asuhan' ? 'selected' : '' ?>>Panti Asuhan</option>
                <option value="Rumah Singgah" <?= old('tinggal_bersama', $alamat['tinggal_bersama'] ?? '') === 'Rumah Singgah' ? 'selected' : '' ?>>Rumah Singgah</option>
                <option value="Lainnya" <?= old('tinggal_bersama', $alamat['tinggal_bersama'] ?? '') === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
            </select>
        </div>

        <!-- Jarak ke Sekolah -->
        <div class="col-md-6 mb-3">
            <label for="jarak_ke_sekolah" class="form-label">Jarak ke Sekolah</label>
            <select class="form-select" id="jarak_ke_sekolah" name="jarak_ke_sekolah">
                <option value="">Pilih Jarak</option>
                <option value="< 1 km" <?= old('jarak_ke_sekolah', $alamat['jarak_ke_sekolah'] ?? '') === '< 1 km' ? 'selected' : '' ?>>< 1 km</option>
                <option value="1-5 km" <?= old('jarak_ke_sekolah', $alamat['jarak_ke_sekolah'] ?? '') === '1-5 km' ? 'selected' : '' ?>>1-5 km</option>
                <option value="5-10 km" <?= old('jarak_ke_sekolah', $alamat['jarak_ke_sekolah'] ?? '') === '5-10 km' ? 'selected' : '' ?>>5-10 km</option>
                <option value="10-20 km" <?= old('jarak_ke_sekolah', $alamat['jarak_ke_sekolah'] ?? '') === '10-20 km' ? 'selected' : '' ?>>10-20 km</option>
                <option value="> 20 km" <?= old('jarak_ke_sekolah', $alamat['jarak_ke_sekolah'] ?? '') === '> 20 km' ? 'selected' : '' ?>>> 20 km</option>
            </select>
        </div>

        <!-- Waktu Tempuh -->
        <div class="col-md-6 mb-3">
            <label for="waktu_tempuh" class="form-label">Waktu Tempuh</label>
            <select class="form-select" id="waktu_tempuh" name="waktu_tempuh">
                <option value="">Pilih Waktu Tempuh</option>
                <option value="< 15 menit" <?= old('waktu_tempuh', $alamat['waktu_tempuh'] ?? '') === '< 15 menit' ? 'selected' : '' ?>>< 15 menit</option>
                <option value="15-30 menit" <?= old('waktu_tempuh', $alamat['waktu_tempuh'] ?? '') === '15-30 menit' ? 'selected' : '' ?>>15-30 menit</option>
                <option value="30-60 menit" <?= old('waktu_tempuh', $alamat['waktu_tempuh'] ?? '') === '30-60 menit' ? 'selected' : '' ?>>30-60 menit</option>
                <option value="> 60 menit" <?= old('waktu_tempuh', $alamat['waktu_tempuh'] ?? '') === '> 60 menit' ? 'selected' : '' ?>>> 60 menit</option>
            </select>
        </div>

        <!-- Transportasi -->
        <div class="col-md-6 mb-3">
            <label for="transportasi" class="form-label">Transportasi ke Sekolah</label>
            <select class="form-select" id="transportasi" name="transportasi">
                <option value="">Pilih Transportasi</option>
                <option value="Jalan Kaki" <?= old('transportasi', $alamat['transportasi'] ?? '') === 'Jalan Kaki' ? 'selected' : '' ?>>Jalan Kaki</option>
                <option value="Sepeda" <?= old('transportasi', $alamat['transportasi'] ?? '') === 'Sepeda' ? 'selected' : '' ?>>Sepeda</option>
                <option value="Motor" <?= old('transportasi', $alamat['transportasi'] ?? '') === 'Motor' ? 'selected' : '' ?>>Motor</option>
                <option value="Mobil" <?= old('transportasi', $alamat['transportasi'] ?? '') === 'Mobil' ? 'selected' : '' ?>>Mobil</option>
                <option value="Angkutan Umum" <?= old('transportasi', $alamat['transportasi'] ?? '') === 'Angkutan Umum' ? 'selected' : '' ?>>Angkutan Umum</option>
                <option value="Ojek Online" <?= old('transportasi', $alamat['transportasi'] ?? '') === 'Ojek Online' ? 'selected' : '' ?>>Ojek Online</option>
                <option value="Lainnya" <?= old('transportasi', $alamat['transportasi'] ?? '') === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
            </select>
        </div>

        <!-- Email -->
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email"
                value="<?= old('email', $alamat['email'] ?? '') ?>" placeholder="Masukkan email">
        </div>

        <!-- Media Sosial -->
        <div class="col-md-6 mb-3">
            <label for="media_sosial" class="form-label">Media Sosial</label>
            <input type="text" class="form-control" id="media_sosial" name="media_sosial"
                value="<?= old('media_sosial', $alamat['media_sosial'] ?? '') ?>" placeholder="Instagram/Facebook/dll">
        </div>
    </div>
</div>
