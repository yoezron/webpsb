<!-- Section 1: Data Diri -->
<div class="section-card active" id="section-1">
    <h2 class="section-title">
        <i class="icofont-ui-user"></i> Data Diri Calon Santri
    </h2>

    <div class="row">
        <!-- NISN -->
        <div class="col-md-6 mb-3">
            <label for="nisn" class="form-label required">NISN</label>
            <input type="text" class="form-control numeric-only" id="nisn" name="nisn"
                value="<?= old('nisn') ?>" placeholder="Masukkan NISN (10 digit)" maxlength="10" required>
            <div class="form-text">Nomor Induk Siswa Nasional (wajib diisi, 10 digit).</div>
        </div>

        <!-- NIK -->
        <div class="col-md-6 mb-3">
            <label for="nik" class="form-label required">NIK</label>
            <input type="text" class="form-control numeric-only" id="nik" name="nik"
                value="<?= old('nik') ?>" placeholder="Masukkan NIK (16 digit)" maxlength="16" required>
            <div class="form-text">Nomor Induk Kependudukan (wajib diisi, 16 digit).</div>
        </div>

        <!-- Nama Lengkap -->
        <div class="col-md-12 mb-3">
            <label for="nama_lengkap" class="form-label required">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                value="<?= old('nama_lengkap') ?>" placeholder="Masukkan nama lengkap sesuai akta kelahiran" required>
        </div>

        <!-- Jenis Kelamin -->
        <div class="col-md-6 mb-3">
            <label for="jenis_kelamin" class="form-label required">Jenis Kelamin</label>
            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                <option value="">Pilih Jenis Kelamin</option>
                <option value="L" <?= old('jenis_kelamin') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                <option value="P" <?= old('jenis_kelamin') === 'P' ? 'selected' : '' ?>>Perempuan</option>
            </select>
        </div>

        <!-- Tempat Lahir -->
        <div class="col-md-6 mb-3">
            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir"
                value="<?= old('tempat_lahir') ?>" placeholder="Masukkan tempat lahir">
        </div>

        <!-- Tanggal Lahir -->
        <div class="col-md-6 mb-3">
            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                value="<?= old('tanggal_lahir') ?>">
        </div>

        <!-- Status Keluarga -->
        <div class="col-md-6 mb-3">
            <label for="status_keluarga" class="form-label">Status dalam Keluarga</label>
            <select class="form-select" id="status_keluarga" name="status_keluarga">
                <option value="">Pilih Status</option>
                <option value="Anak Kandung" <?= old('status_keluarga') === 'Anak Kandung' ? 'selected' : '' ?>>Anak Kandung</option>
                <option value="Anak Tiri" <?= old('status_keluarga') === 'Anak Tiri' ? 'selected' : '' ?>>Anak Tiri</option>
                <option value="Anak Angkat" <?= old('status_keluarga') === 'Anak Angkat' ? 'selected' : '' ?>>Anak Angkat</option>
            </select>
        </div>

        <!-- Anak Ke -->
        <div class="col-md-6 mb-3">
            <label for="anak_ke" class="form-label">Anak Ke-</label>
            <input type="number" class="form-control" id="anak_ke" name="anak_ke"
                value="<?= old('anak_ke') ?>" placeholder="Masukkan urutan anak" min="1" max="20">
        </div>

        <!-- Jumlah Saudara -->
        <div class="col-md-6 mb-3">
            <label for="jumlah_saudara" class="form-label">Jumlah Saudara Kandung</label>
            <input type="number" class="form-control" id="jumlah_saudara" name="jumlah_saudara"
                value="<?= old('jumlah_saudara') ?>" placeholder="Masukkan jumlah saudara" min="0" max="20">
        </div>

        <!-- Hobi -->
        <div class="col-md-6 mb-3">
            <label for="hobi" class="form-label required">Hobi</label>
            <select class="form-select" id="hobi" name="hobi" required>
                <option value="">Pilih Hobi</option>
                <option value="Olah Raga" <?= old('hobi') === 'Olah Raga' ? 'selected' : '' ?>>Olah Raga</option>
                <option value="Kesenian" <?= old('hobi') === 'Kesenian' ? 'selected' : '' ?>>Kesenian</option>
                <option value="Membaca" <?= old('hobi') === 'Membaca' ? 'selected' : '' ?>>Membaca</option>
                <option value="Menulis" <?= old('hobi') === 'Menulis' ? 'selected' : '' ?>>Menulis</option>
                <option value="Jalan-jalan" <?= old('hobi') === 'Jalan-jalan' ? 'selected' : '' ?>>Jalan-jalan</option>
                <option value="Lainnya" <?= old('hobi') === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
            </select>
        </div>

        <!-- Cita-cita -->
        <div class="col-md-6 mb-3">
            <label for="cita_cita" class="form-label required">Cita-cita</label>
            <select class="form-select" id="cita_cita" name="cita_cita" required>
                <option value="">Pilih Cita-cita</option>
                <option value="PNS" <?= old('cita_cita') === 'PNS' ? 'selected' : '' ?>>PNS</option>
                <option value="TNI/Polri" <?= old('cita_cita') === 'TNI/Polri' ? 'selected' : '' ?>>TNI/Polri</option>
                <option value="Guru/Dosen" <?= old('cita_cita') === 'Guru/Dosen' ? 'selected' : '' ?>>Guru/Dosen</option>
                <option value="Dokter" <?= old('cita_cita') === 'Dokter' ? 'selected' : '' ?>>Dokter</option>
                <option value="Politikus" <?= old('cita_cita') === 'Politikus' ? 'selected' : '' ?>>Politikus</option>
                <option value="Wiraswasta" <?= old('cita_cita') === 'Wiraswasta' ? 'selected' : '' ?>>Wiraswasta</option>
                <option value="Seniman/Artis" <?= old('cita_cita') === 'Seniman/Artis' ? 'selected' : '' ?>>Seniman/Artis</option>
                <option value="Ilmuwan" <?= old('cita_cita') === 'Ilmuwan' ? 'selected' : '' ?>>Ilmuwan</option>
                <option value="Agamawan" <?= old('cita_cita') === 'Agamawan' ? 'selected' : '' ?>>Agamawan</option>
                <option value="Lainnya" <?= old('cita_cita') === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
            </select>
        </div>

        <!-- Pendidikan Sebelumnya -->
        <div class="col-md-12 mb-3">
            <label class="form-label">Pendidikan Sebelumnya</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="pernah_paud" name="pernah_paud" value="1"
                    <?= old('pernah_paud') ? 'checked' : '' ?>>
                <label class="form-check-label" for="pernah_paud">Pernah PAUD</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="pernah_tk" name="pernah_tk" value="1"
                    <?= old('pernah_tk') ? 'checked' : '' ?>>
                <label class="form-check-label" for="pernah_tk">Pernah TK</label>
            </div>
        </div>

        <!-- Kebutuhan Disabilitas -->
        <div class="col-md-6 mb-3">
            <label for="kebutuhan_disabilitas" class="form-label">Kebutuhan Khusus/Disabilitas</label>
            <input type="text" class="form-control" id="kebutuhan_disabilitas" name="kebutuhan_disabilitas"
                value="<?= old('kebutuhan_disabilitas') ?>" placeholder="Kosongkan jika tidak ada">
        </div>

        <!-- Imunisasi -->
        <div class="col-md-6 mb-3">
            <label for="imunisasi" class="form-label">Riwayat Imunisasi</label>
            <input type="text" class="form-control" id="imunisasi" name="imunisasi"
                value="<?= old('imunisasi') ?>" placeholder="Contoh: Lengkap, Tidak Lengkap">
        </div>

        <!-- No HP -->
        <div class="col-md-6 mb-3">
            <label for="no_hp" class="form-label">Nomor HP/WhatsApp</label>
            <input type="text" class="form-control numeric-only" id="no_hp" name="no_hp"
                value="<?= old('no_hp') ?>" placeholder="Contoh: 08123456789" maxlength="20">
        </div>

        <!-- Ukuran Baju -->
        <div class="col-md-6 mb-3">
            <label for="ukuran_baju" class="form-label">Ukuran Baju</label>
            <select class="form-select" id="ukuran_baju" name="ukuran_baju">
                <option value="">Pilih Ukuran</option>
                <option value="S" <?= old('ukuran_baju') === 'S' ? 'selected' : '' ?>>S</option>
                <option value="M" <?= old('ukuran_baju') === 'M' ? 'selected' : '' ?>>M</option>
                <option value="L" <?= old('ukuran_baju') === 'L' ? 'selected' : '' ?>>L</option>
                <option value="XL" <?= old('ukuran_baju') === 'XL' ? 'selected' : '' ?>>XL</option>
                <option value="XXL" <?= old('ukuran_baju') === 'XXL' ? 'selected' : '' ?>>XXL</option>
            </select>
        </div>

        <!-- Prestasi -->
        <div class="col-md-12 mb-3">
            <label for="prestasi" class="form-label">Prestasi yang Pernah Diraih</label>
            <textarea class="form-control" id="prestasi" name="prestasi" rows="3"
                placeholder="Tuliskan prestasi yang pernah diraih (jika ada)"><?= old('prestasi') ?></textarea>
        </div>
    </div>
</div>
