<!-- Section 6: Data Bansos -->
<div class="section-card hidden" id="section-6">
    <h2 class="section-title">
        <i class="icofont-money"></i> Data Program Bantuan Sosial (Opsional)
    </h2>

    <div class="alert alert-info mb-4">
        <i class="icofont-info-circle"></i> Bagian ini opsional. Isi jika keluarga Anda merupakan penerima program bantuan sosial dari pemerintah.
    </div>

    <div class="row">
        <!-- No KKS -->
        <div class="col-md-6 mb-3">
            <label for="no_kks" class="form-label">Nomor Kartu Keluarga Sejahtera (KKS)</label>
            <input type="text" class="form-control" id="no_kks" name="no_kks"
                value="<?= old('no_kks', $bansos['no_kks'] ?? '') ?>" placeholder="Masukkan No. KKS jika ada">
        </div>

        <!-- No PKH -->
        <div class="col-md-6 mb-3">
            <label for="no_pkh" class="form-label">Nomor Program Keluarga Harapan (PKH)</label>
            <input type="text" class="form-control" id="no_pkh" name="no_pkh"
                value="<?= old('no_pkh', $bansos['no_pkh'] ?? '') ?>" placeholder="Masukkan No. PKH jika ada">
        </div>

        <!-- No KIP -->
        <div class="col-md-6 mb-3">
            <label for="no_kip" class="form-label">Nomor Kartu Indonesia Pintar (KIP)</label>
            <input type="text" class="form-control" id="no_kip" name="no_kip"
                value="<?= old('no_kip', $bansos['no_kip'] ?? '') ?>" placeholder="Masukkan No. KIP jika ada">
        </div>
    </div>
</div>
