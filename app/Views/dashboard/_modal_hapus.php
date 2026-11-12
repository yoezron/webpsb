<!-- Modal konfirmasi hapus pendaftar (dipakai dashboard jalur & halaman detail).
     Tombol pemicu: <button class="btn-delete" data-id=".." data-nama=".." data-nomor=".."> -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="icofont-warning me-2"></i> Konfirmasi Hapus Permanen
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="text-center mb-2">Apakah Anda yakin ingin menghapus data pendaftar berikut?</p>
                <div class="alert alert-warning">
                    <strong>Nama:</strong> <span id="delete-nama"></span><br>
                    <strong>Nomor Peserta:</strong> <span id="delete-nomor"></span>
                </div>
                <div class="alert alert-danger mb-0">
                    <i class="icofont-warning me-1"></i>
                    <strong>Peringatan:</strong> Data yang dihapus tidak dapat dikembalikan. Semua data terkait
                    (alamat, orang tua, wali, bansos, asal sekolah, dan file Kartu Keluarga) ikut terhapus.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">
                        <i class="icofont-trash me-1"></i> Ya, Hapus Permanen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const el = document.getElementById('deleteModal');
        if (!el || typeof bootstrap === 'undefined') return;
        const modal = new bootstrap.Modal(el);
        const form = document.getElementById('deleteForm');
        document.querySelectorAll('.btn-delete').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.getElementById('delete-nama').textContent = btn.dataset.nama || '';
                document.getElementById('delete-nomor').textContent = btn.dataset.nomor || '';
                form.setAttribute('action', '<?= base_url('dashboard/delete/') ?>' + btn.dataset.id);
                modal.show();
            });
        });
    });
</script>
