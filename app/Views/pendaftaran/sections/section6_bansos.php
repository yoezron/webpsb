<!-- Section 6: Data Bansos -->
<div class="section-card hidden" id="section-6">
    <h2 class="section-title">
        <i class="icofont-card"></i> Data Bantuan Sosial (Bansos)
    </h2>

    <div class="alert alert-info">
        <i class="icofont-info-circle"></i> Kosongkan jika tidak memiliki/tidak menerima bantuan sosial
    </div>

    <div class="row">
        <!-- NO. KKS -->
        <div class="col-md-12 mb-3">
            <label for="no_kks" class="form-label">Nomor Kartu Keluarga Sejahtera (KKS)</label>
            <input type="text" class="form-control numeric-only" id="no_kks" name="no_kks"
                value="<?= old('no_kks') ?>" placeholder="Masukkan nomor KKS" maxlength="30">
            <div class="form-text">KKS adalah kartu yang diberikan kepada keluarga penerima PKH atau BPNT</div>
        </div>

        <!-- NO. PKH -->
        <div class="col-md-6 mb-3">
            <label for="no_pkh" class="form-label">Nomor Program Keluarga Harapan (PKH)</label>
            <input type="text" class="form-control numeric-only" id="no_pkh" name="no_pkh"
                value="<?= old('no_pkh') ?>" placeholder="Masukkan nomor PKH" maxlength="30">
            <div class="form-text">PKH adalah program bantuan sosial bersyarat untuk keluarga miskin</div>
        </div>

        <!-- NO. KIP -->
        <div class="col-md-6 mb-3">
            <label for="no_kip" class="form-label">Nomor Kartu Indonesia Pintar (KIP)</label>
            <input type="text" class="form-control numeric-only" id="no_kip" name="no_kip"
                value="<?= old('no_kip') ?>" placeholder="Masukkan nomor KIP" maxlength="30">
            <div class="form-text">KIP adalah kartu bantuan pendidikan untuk anak usia sekolah</div>
        </div>
    </div>
</div>
