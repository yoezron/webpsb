<?php

/**
 * Helper render field formulir pendaftaran (dipakai oleh section form daftar & update data).
 *
 * Opsi umum ($o):
 *   col        kelas kolom bootstrap (default col-md-6)
 *   wajib      bool, field wajib diisi
 *   dinamis    nama grup wajib-dinamis (ayah/ibu/wali); field wajib hanya bila grup aktif
 *   hint       teks bantuan di bawah field
 *   attrs      atribut HTML tambahan (string, sudah aman)
 *   placeholder, maxlength, type, kelas (kelas input tambahan)
 */

if (!function_exists('pf_nilai')) {
    function pf_nilai(string $name, $default = '')
    {
        $v = old($name, $default ?? '');

        return $v ?? '';
    }
}

if (!function_exists('pf_buka')) {
    function pf_buka(string $name, string $label, array $o): string
    {
        $col     = $o['col'] ?? 'col-md-6';
        $wajib   = !empty($o['wajib']);
        $dinamis = $o['dinamis'] ?? '';
        $attr    = $dinamis !== '' ? ' data-wajib-dinamis="' . esc($dinamis, 'attr') . '"' : '';
        $id      = $o['id'] ?? $name;

        return '<div class="' . esc($col, 'attr') . ' mb-3" data-field="' . esc($name, 'attr') . '"' . $attr . '>'
            . '<label for="' . esc($id, 'attr') . '" class="form-label' . ($wajib ? ' required' : '') . '">' . esc($label) . '</label>';
    }
}

if (!function_exists('pf_tutup')) {
    function pf_tutup(array $o): string
    {
        $hint = isset($o['hint']) ? '<div class="form-text">' . $o['hint'] . '</div>' : '';

        return $hint . '</div>';
    }
}

if (!function_exists('pf_input')) {
    function pf_input(string $name, string $label, $nilai, array $o = []): string
    {
        $type  = $o['type'] ?? 'text';
        $kelas = 'form-control' . (isset($o['kelas']) ? ' ' . $o['kelas'] : '');
        $attr  = ' type="' . esc($type, 'attr') . '" class="' . esc($kelas, 'attr') . '" id="' . esc($o['id'] ?? $name, 'attr') . '" name="' . esc($name, 'attr') . '"';
        $attr .= ' value="' . esc((string) pf_nilai($name, $nilai), 'attr') . '"';
        if (isset($o['placeholder'])) {
            $attr .= ' placeholder="' . esc($o['placeholder'], 'attr') . '"';
        }
        if (isset($o['maxlength'])) {
            $attr .= ' maxlength="' . (int) $o['maxlength'] . '"';
        }
        if (!empty($o['wajib'])) {
            $attr .= ' required';
        }
        $attr .= isset($o['attrs']) ? ' ' . $o['attrs'] : '';

        return pf_buka($name, $label, $o) . '<input' . $attr . '>' . pf_tutup($o);
    }
}

if (!function_exists('pf_textarea')) {
    function pf_textarea(string $name, string $label, $nilai, array $o = []): string
    {
        $attr = ' class="form-control" id="' . esc($o['id'] ?? $name, 'attr') . '" name="' . esc($name, 'attr') . '" rows="' . (int) ($o['rows'] ?? 2) . '"';
        if (isset($o['placeholder'])) {
            $attr .= ' placeholder="' . esc($o['placeholder'], 'attr') . '"';
        }
        if (!empty($o['wajib'])) {
            $attr .= ' required';
        }
        $attr .= isset($o['attrs']) ? ' ' . $o['attrs'] : '';

        return pf_buka($name, $label, $o)
            . ($o['sebelum'] ?? '')
            . '<textarea' . $attr . '>' . esc((string) pf_nilai($name, $nilai)) . '</textarea>'
            . pf_tutup($o);
    }
}

if (!function_exists('pf_select')) {
    /**
     * @param array $opsi list nilai, atau [nilai => label]
     */
    function pf_select(string $name, string $label, array $opsi, $nilai, array $o = []): string
    {
        $terpilih = (string) pf_nilai($name, $nilai);
        $isList   = array_is_list($opsi);

        $html = '<select class="form-select" id="' . esc($o['id'] ?? $name, 'attr') . '" name="' . esc($name, 'attr') . '"'
            . (!empty($o['wajib']) ? ' required' : '') . (isset($o['attrs']) ? ' ' . $o['attrs'] : '') . '>';
        $html .= '<option value="">' . esc($o['kosong'] ?? 'Pilih') . '</option>';

        // Pertahankan nilai lama yang tidak ada di daftar (data lama) agar tidak hilang saat update
        $nilaiOpsi = $isList ? $opsi : array_keys($opsi);
        if ($terpilih !== '' && !in_array($terpilih, array_map('strval', $nilaiOpsi), true)) {
            $html .= '<option value="' . esc($terpilih, 'attr') . '" selected>' . esc($terpilih) . '</option>';
        }

        foreach ($opsi as $k => $v) {
            $val = $isList ? $v : $k;
            $lab = ($o['label_opsi'][$val] ?? null) ?? $v;
            $html .= '<option value="' . esc((string) $val, 'attr') . '"' . ((string) $val === $terpilih ? ' selected' : '') . '>' . esc((string) $lab) . '</option>';
        }
        $html .= '</select>';

        return pf_buka($name, $label, $o) . $html . pf_tutup($o);
    }
}
