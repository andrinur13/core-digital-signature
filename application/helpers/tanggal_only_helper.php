<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Fungsi untuk mengubah format tanggal
 * @param string $tanggal Tanggal dalam format YYYY-MM-DD
 * @return string Tanggal dalam format DD MMMM YYYY (contoh: 23 Oktober 2020)
 */
if (!function_exists('format_tanggal')) {
    function format_tanggal($tanggal) {
        // Mengubah tanggal menjadi objek tanggal dengan format standar PHP
        $tanggal = strtotime($tanggal);

        // Format tanggal dalam format "DD MMMM YYYY"
        return date('d F Y', $tanggal);
    }
}