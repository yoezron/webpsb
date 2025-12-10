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
                value="<?= old('nisn', $pendaftar['nisn'] ?? '') ?>" placeholder="Masukkan NISN" maxlength="10" required>
            <div class="form-text">NISN wajib diisi (10 digit).</div>
        </div>

        <!-- NIK -->
        <div class="col-md-6 mb-3">
            <label for="nik" class="form-label">NIK</label>
            <input type="text" class="form-control numeric-only" id="nik" name="nik"
                value="<?= old('nik', $pendaftar['nik'] ?? '') ?>" placeholder="Masukkan NIK" maxlength="16">
        </div>

        <!-- Nama Lengkap -->
        <div class="col-md-12 mb-3">
            <label for="nama_lengkap" class="form-label required">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                value="<?= old('nama_lengkap', $pendaftar['nama_lengkap'] ?? '') ?>" placeholder="Masukkan nama lengkap sesuai akta kelahiran" required>
        </div>

        <!-- Jenis Kelamin -->
        <div class="col-md-6 mb-3">
            <label for="jenis_kelamin" class="form-label required">Jenis Kelamin</label>
            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                <option value="">Pilih Jenis Kelamin</option>
                <option value="L" <?= old('jenis_kelamin', $pendaftar['jenis_kelamin'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                <option value="P" <?= old('jenis_kelamin', $pendaftar['jenis_kelamin'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
            </select>
        </div>

        <!-- Tempat Lahir -->
        <div class="col-md-6 mb-3">
            <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir"
                value="<?= old('tempat_lahir', $pendaftar['tempat_lahir'] ?? '') ?>" placeholder="Masukkan tempat lahir">
        </div>

        <!-- Tanggal Lahir -->
        <div class="col-md-6 mb-3">
            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                value="<?= old('tanggal_lahir', $pendaftar['tanggal_lahir'] ?? '') ?>">
        </div>

        <!-- Status Keluarga -->
        <div class="col-md-6 mb-3">
            <label for="status_keluarga" class="form-label">Status dalam Keluarga</label>
            <select class="form-select" id="status_keluarga" name="status_keluarga">
                <option value="">Pilih Status</option>
                <option value="Anak Kandung" <?= old('status_keluarga', $pendaftar['status_keluarga'] ?? '') === 'Anak Kandung' ? 'selected' : '' ?>>Anak Kandung</option>
                <option value="Anak Tiri" <?= old('status_keluarga', $pendaftar['status_keluarga'] ?? '') === 'Anak Tiri' ? 'selected' : '' ?>>Anak Tiri</option>
                <option value="Anak Angkat" <?= old('status_keluarga', $pendaftar['status_keluarga'] ?? '') === 'Anak Angkat' ? 'selected' : '' ?>>Anak Angkat</option>
            </select>
        </div>

        <!-- Anak Ke -->
        <div class="col-md-6 mb-3">
            <label for="anak_ke" class="form-label">Anak Ke-</label>
            <input type="number" class="form-control" id="anak_ke" name="anak_ke"
                value="<?= old('anak_ke', $pendaftar['anak_ke'] ?? '') ?>" placeholder="Masukkan urutan anak" min="1" max="20">
        </div>

        <!-- Jumlah Saudara -->
        <div class="col-md-6 mb-3">
            <label for="jumlah_saudara" class="form-label">Jumlah Saudara Kandung</label>
            <input type="number" class="form-control" id="jumlah_saudara" name="jumlah_saudara"
                value="<?= old('jumlah_saudara', $pendaftar['jumlah_saudara'] ?? '') ?>" placeholder="Masukkan jumlah saudara" min="0" max="20">
        </div>

        <!-- Hobi -->
        <div class="col-md-6 mb-3">
            <label for="hobi" class="form-label">Hobi</label>
            <input type="text" class="form-control" id="hobi" name="hobi"
                value="<?= old('hobi', $pendaftar['hobi'] ?? '') ?>" placeholder="Masukkan hobi">
        </div>

        <!-- Cita-cita -->
        <div class="col-md-6 mb-3">
            <label for="cita_cita" class="form-label">Cita-cita</label>
            <input type="text" class="form-control" id="cita_cita" name="cita_cita"
                value="<?= old('cita_cita', $pendaftar['cita_cita'] ?? '') ?>" placeholder="Masukkan cita-cita">
        </div>

        <!-- Pendidikan Sebelumnya -->
        <div class="col-md-12 mb-3">
            <label class="form-label">Pendidikan Sebelumnya</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="pernah_paud" name="pernah_paud" value="1"
                    <?= old('pernah_paud', $pendaftar['pernah_paud'] ?? '') ? 'checked' : '' ?>>
                <label class="form-check-label" for="pernah_paud">Pernah PAUD</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="pernah_tk" name="pernah_tk" value="1"
                    <?= old('pernah_tk', $pendaftar['pernah_tk'] ?? '') ? 'checked' : '' ?>>
                <label class="form-check-label" for="pernah_tk">Pernah TK</label>
            </div>
        </div>

        <!-- Kebutuhan Disabilitas -->
        <div class="col-md-6 mb-3">
            <label for="kebutuhan_disabilitas" class="form-label">Kebutuhan Khusus/Disabilitas</label>
            <input type="text" class="form-control" id="kebutuhan_disabilitas" name="kebutuhan_disabilitas"
                value="<?= old('kebutuhan_disabilitas', $pendaftar['kebutuhan_disabilitas'] ?? '') ?>" placeholder="Kosongkan jika tidak ada">
        </div>

        <!-- Imunisasi -->
        <div class="col-md-6 mb-3">
            <label for="imunisasi" class="form-label">Riwayat Imunisasi</label>
            <input type="text" class="form-control" id="imunisasi" name="imunisasi"
                value="<?= old('imunisasi', $pendaftar['imunisasi'] ?? '') ?>" placeholder="Contoh: Lengkap, Tidak Lengkap">
        </div>

        <!-- No HP -->
        <div class="col-md-6 mb-3">
            <label for="no_hp" class="form-label">Nomor HP/WhatsApp</label>
            <input type="text" class="form-control numeric-only" id="no_hp" name="no_hp"
                value="<?= old('no_hp', $pendaftar['no_hp'] ?? '') ?>" placeholder="Contoh: 08123456789" maxlength="20">
        </div>

        <!-- Ukuran Baju -->
        <div class="col-md-6 mb-3">
            <label for="ukuran_baju" class="form-label">Ukuran Baju</label>
            <select class="form-select" id="ukuran_baju" name="ukuran_baju">
                <option value="">Pilih Ukuran</option>
                <option value="S" <?= old('ukuran_baju', $pendaftar['ukuran_baju'] ?? '') === 'S' ? 'selected' : '' ?>>S</option>
                <option value="M" <?= old('ukuran_baju', $pendaftar['ukuran_baju'] ?? '') === 'M' ? 'selected' : '' ?>>M</option>
                <option value="L" <?= old('ukuran_baju', $pendaftar['ukuran_baju'] ?? '') === 'L' ? 'selected' : '' ?>>L</option>
                <option value="XL" <?= old('ukuran_baju', $pendaftar['ukuran_baju'] ?? '') === 'XL' ? 'selected' : '' ?>>XL</option>
                <option value="XXL" <?= old('ukuran_baju', $pendaftar['ukuran_baju'] ?? '') === 'XXL' ? 'selected' : '' ?>>XXL</option>
            </select>
        </div>

        <!-- Prestasi -->
        <div class="col-md-12 mb-3">
            <label for="prestasi" class="form-label">Prestasi yang Pernah Diraih</label>
            <textarea class="form-control" id="prestasi" name="prestasi" rows="3"
                placeholder="Tuliskan prestasi yang pernah diraih (jika ada)"><?= old('prestasi', $pendaftar['prestasi'] ?? '') ?></textarea>
        </div>
    </div>
</div>
