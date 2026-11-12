<script>
/**
 * Perilaku dinamis formulir pendaftaran / update data (dipakai kedua form).
 *  - Ayah/Ibu: field wajib hanya bila status "Masih Hidup"
 *  - Wali: field tampil & wajib hanya bila "Ya, ada wali"
 *  - "Sama dengan Ayah/Ibu Kandung" menyalin data ke wali
 *  - Nama otomatis huruf besar, salin alamat santri, cek file KK (JPG, maks 1 MB)
 */
window.PSBForm = (function ($) {
    function grupAktif(grup) {
        if (grup === 'wali') {
            return $('input[name="ada_wali"]:checked').val() === 'Ya';
        }
        return $('[name="status_' + grup + '"]').val() === 'Masih Hidup';
    }

    function terapkanGrup(grup) {
        const aktif = grupAktif(grup);
        $('[data-wajib-dinamis="' + grup + '"]').each(function () {
            $(this).find('label.form-label').first().toggleClass('required', aktif);
            $(this).find('input:not([type=checkbox]), select, textarea').prop('required', aktif);
        });

        if (grup === 'wali') {
            const dipilih = $('input[name="ada_wali"]:checked').val();
            $('#wali-fields').toggleClass('d-none', dipilih !== 'Ya');
        } else {
            const status = $('[name="status_' + grup + '"]').val();
            $('.catatan-opsional[data-untuk="' + grup + '"]').toggleClass('d-none', !status || status === 'Masih Hidup');
        }
    }

    function terapkanSemua() {
        ['ayah', 'ibu', 'wali'].forEach(terapkanGrup);
    }

    function salinKeWali(sumber) {
        ['nik', 'nama', 'tempat_lahir', 'tanggal_lahir', 'pendidikan', 'pekerjaan', 'domisili', 'hp', 'penghasilan', 'alamat', 'status_tempat_tinggal']
            .forEach(function (f) {
                const v = $('[name="' + f + '_' + sumber + '"]').val();
                const $t = $('[name="' + f + '_wali"]');
                if ($t.is('select') && v && !$t.find('option[value="' + v.replace(/"/g, '\\"') + '"]').length) {
                    $t.append($('<option>').val(v).text(v));
                }
                $t.val(v).trigger('change');
            });
    }

    function alamatSantri() {
        const v = (n) => ($('[name="' + n + '"]').val() || '').trim();
        const bagian = [
            v('alamat'),
            v('rt_rw') ? 'RT/RW ' + v('rt_rw') : '',
            v('desa'), v('kecamatan'), v('kabupaten'), v('provinsi'), v('kode_pos'),
        ].filter(Boolean);
        return bagian.join(', ');
    }

    function cekFileKk(input, tampilkanPesan) {
        const file = input.files && input.files[0];
        const $preview = $('#preview_kk');
        if (!file) {
            $preview.addClass('d-none').attr('src', '');
            return '';
        }
        const maxKb = parseInt($(input).data('max-kb') || '1024', 10);
        const nama = file.name.toLowerCase();
        let pesan = '';
        if (!/\.(jpe?g)$/.test(nama) || (file.type && file.type !== 'image/jpeg' && file.type !== 'image/pjpeg')) {
            pesan = 'File Kartu Keluarga harus berformat JPG/JPEG.';
        } else if (file.size > maxKb * 1024) {
            pesan = 'Ukuran file Kartu Keluarga maksimal 1 MB (file Anda ' + (file.size / 1024 / 1024).toFixed(2) + ' MB).';
        }
        if (pesan) {
            input.value = '';
            $preview.addClass('d-none').attr('src', '');
            if (tampilkanPesan && typeof showToast === 'function') showToast(pesan, 'error', 6000);
            return pesan;
        }
        $preview.attr('src', URL.createObjectURL(file)).removeClass('d-none');
        return '';
    }

    /**
     * Validasi satu section (dipanggil tombol "Selanjutnya").
     */
    function validasiSection($section) {
        let valid = true;
        $section.find('.is-invalid').removeClass('is-invalid');
        $section.find('.invalid-feedback.psb').remove();

        const tandai = ($el, pesan) => {
            valid = false;
            $el.addClass('is-invalid');
            $el.after('<div class="invalid-feedback psb" style="display:block">' + pesan + '</div>');
        };

        $section.find('[required]').each(function () {
            const $el = $(this);
            if ($el.closest('.d-none').length || $el.is(':radio') || $el.is(':checkbox')) return;
            if (!($el.val() || '').toString().trim()) {
                tandai($el, 'Field ini wajib diisi!');
                return;
            }
            if (this.type === 'email' && this.validity && this.validity.typeMismatch) {
                tandai($el, 'Format email tidak valid.');
            }
            const ml = parseInt($el.attr('maxlength') || '0', 10);
            if ($el.hasClass('numeric-only') && ['nik', 'nik_ayah', 'nik_ibu', 'nik_wali', 'nisn', 'kode_pos'].includes(this.name) && $el.val().length !== ml) {
                tandai($el, 'Harus ' + ml + ' digit.');
            }
        });

        // Grup checkbox / radio wajib (minimal satu dipilih)
        $section.find('[data-wajib-grup]').each(function () {
            const nama = $(this).data('wajib-grup');
            if (!$section.find('[name="' + nama + '"]:checked').length) {
                valid = false;
                $(this).find('.invalid-feedback.psb').remove();
                $(this).append('<div class="invalid-feedback psb" style="display:block">Pilih minimal satu.</div>');
            }
        });

        if (!valid && typeof showToast === 'function') {
            showToast('Mohon lengkapi semua field yang wajib diisi!', 'warning', 5000);
            const $pertama = $section.find('.is-invalid, .invalid-feedback.psb').first();
            if ($pertama.length) $('html, body').animate({ scrollTop: $pertama.offset().top - 120 }, 300);
        }
        return valid;
    }

    /**
     * Ringkasan untuk halaman review: dibangun dari semua field yang tampil di section 1-7.
     */
    function ringkasan() {
        const esc = (s) => $('<div>').text(s).html();
        let html = '';
        $('.section-card').each(function () {
            const $s = $(this);
            if ($s.attr('id') === 'section-8') return;
            const judul = $s.find('.section-title').first().text().trim();
            let baris = '';

            const $bidang = $s.find('[data-field]').length ? $s.find('[data-field]') : $s.find('.mb-3').filter(function () {
                return $(this).find('label.form-label').length && $(this).find('input, select, textarea').length;
            });
            $bidang.each(function () {
                const $f = $(this);
                if ($f.closest('.d-none').length) return;
                const label = $f.find('label.form-label').first().text().replace('*', '').trim();
                let nilai = '';
                const $cek = $f.find('input[type=checkbox]:checked, input[type=radio]:checked').not('.salin-alamat');
                const $file = $f.find('input[type=file]');
                if ($file.length) {
                    nilai = $file[0].files && $file[0].files[0] ? $file[0].files[0].name : ($file.prop('required') ? '' : '(file sudah tersimpan)');
                } else if ($f.find('input[type=checkbox], input[type=radio]').not('.salin-alamat').length && !$f.find('select, textarea, input[type=text], input[type=number], input[type=date], input[type=email]').length) {
                    nilai = $cek.map(function () { return $(this).next('label').text().trim(); }).get().join(', ');
                } else {
                    const $in = $f.find('select, textarea, input').not('[type=checkbox]').first();
                    nilai = $in.is('select') ? ($in.val() ? $in.find('option:selected').text().trim() : '') : ($in.val() || '').trim();
                }
                if (nilai) {
                    baris += '<tr><td style="width:40%;font-weight:600">' + esc(label) + '</td><td>' + esc(nilai) + '</td></tr>';
                }
            });

            // Checkbox tunggal (mis. Pernah PAUD / TK)
            $s.find('.form-check input[type=checkbox]:checked').not('[name$="[]"]').not('.salin-alamat').each(function () {
                if ($(this).closest('[data-field]').length) return;
                baris += '<tr><td style="width:40%;font-weight:600">' + esc($(this).next('label').text().trim()) + '</td><td>Ya</td></tr>';
            });

            if (baris) {
                html += '<div class="review-section"><div class="review-section-title">' + esc(judul) + '</div>'
                    + '<table class="review-table"><tbody>' + baris + '</tbody></table></div>';
            }
        });
        return html;
    }

    function init() {
        // Status ayah/ibu & ada wali
        $(document).on('change', '[data-pengendali-wajib]', function () {
            terapkanGrup($(this).data('pengendali-wajib'));
        });

        // Wali sama dengan ayah/ibu kandung
        $(document).on('change', '#hubungan_wali', function () {
            if (this.value === 'Sama dengan Ayah Kandung') salinKeWali('ayah');
            if (this.value === 'Sama dengan Ibu Kandung') salinKeWali('ibu');
        });

        // Huruf besar untuk nama
        $(document).on('input', '.huruf-besar', function () {
            const pos = this.selectionStart;
            this.value = this.value.toUpperCase();
            if (pos !== null) this.setSelectionRange(pos, pos);
        });
        $('.huruf-besar').each(function () { this.value = this.value.toUpperCase(); });

        // Salin alamat santri
        $(document).on('change', '.salin-alamat', function () {
            const $t = $('#' + $(this).data('target'));
            if (this.checked) {
                $t.val(alamatSantri()).trigger('change');
            }
        });

        // "Tidak Ada" disabilitas eksklusif
        $(document).on('change', '.cek-disabilitas', function () {
            if (!this.checked) return;
            if (this.value === 'Tidak Ada') {
                $('.cek-disabilitas').not(this).prop('checked', false);
            } else {
                $('.cek-disabilitas[value="Tidak Ada"]').prop('checked', false);
            }
        });

        // File KK
        $(document).on('change', '#file_kk', function () { cekFileKk(this, true); });

        // Angka saja
        $(document).on('input', '.numeric-only', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        terapkanSemua();
    }

    return { init, terapkanSemua, validasiSection, ringkasan };
})(jQuery);
</script>
