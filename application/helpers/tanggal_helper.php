<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Fungsi untuk mengonversi tanggal dalam format Y-m-d ke format terbilang
 * Contoh: 1990-12-23 menjadi "tanggal dua puluh tiga bulan Desember tahun seribu sembilan ratus sembilan puluh"
 */
function tanggal_terbilang($tanggal) {
    // Array untuk nama bulan
    $bulan = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', 
        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', 
        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];

    // Fungsi untuk mengubah angka menjadi terbilang
    $angka_to_terbilang = [
        '0' => 'nol', '1' => 'satu', '2' => 'dua', '3' => 'tiga', '4' => 'empat',
        '5' => 'lima', '6' => 'enam', '7' => 'tujuh', '8' => 'delapan', '9' => 'sembilan',
        '10' => 'sepuluh', '11' => 'sebelas', '12' => 'dua belas', '13' => 'tiga belas', 
        '14' => 'empat belas', '15' => 'lima belas', '16' => 'enam belas', 
        '17' => 'tujuh belas', '18' => 'delapan belas', '19' => 'sembilan belas', 
        '20' => 'dua puluh', '30' => 'tiga puluh', '40' => 'empat puluh', 
        '50' => 'lima puluh', '60' => 'enam puluh', '70' => 'tujuh puluh', 
        '80' => 'delapan puluh', '90' => 'sembilan puluh',
        '100' => 'seratus', '1000' => 'seribu'
    ];

    $angka_to_terbilang2 = [
        '0' => 'nol', 
        '1' => 'satu', 
        '2' => 'dua', 
        '3' => 'tiga', 
        '4' => 'empat',
        '5' => 'lima', 
        '6' => 'enam', 
        '7' => 'tujuh', 
        '8' => 'delapan', 
        '9' => 'sembilan',
        '10' => 'sepuluh', 

        '01' => 'satu', 
        '02' => 'dua', 
        '03' => 'tiga', 
        '04' => 'empat',
        '05' => 'lima', 
        '06' => 'enam', 
        '07' => 'tujuh', 
        '08' => 'delapan', 
        '09' => 'sembilan',
        
        '11' => 'sebelas', '12' => 'dua belas', '13' => 'tiga belas', 
        '14' => 'empat belas', '15' => 'lima belas', '16' => 'enam belas', 
        '17' => 'tujuh belas', '18' => 'delapan belas', '19' => 'sembilan belas', 
        '20' => 'dua puluh', '30' => 'tiga puluh', '40' => 'empat puluh', 
        '50' => 'lima puluh', '60' => 'enam puluh', '70' => 'tujuh puluh', 
        '80' => 'delapan puluh', '90' => 'sembilan puluh',
        '100' => 'seratus', '1000' => 'seribu'
    ];

    // Pisahkan tanggal menjadi tahun, bulan, dan hari
    list($tahun, $bln, $tgl) = explode('-', $tanggal);

    // Fungsi untuk mengubah angka menjadi terbilang (untuk tanggal dan tahun)
    $terbilang_hari = angka_ke_terbilang($tgl, $angka_to_terbilang2);
    $terbilang_tahun = angka_ke_terbilang_tahun($tahun, $angka_to_terbilang);

    // Ubah bulan ke dalam nama bulan Indonesia
    $nama_bulan = $bulan[$bln];

    // Return hasil akhir
    return "tanggal $terbilang_hari bulan $nama_bulan tahun $terbilang_tahun";
}

// Fungsi untuk mengubah angka tanggal menjadi terbilang
function angka_ke_terbilang($angka, $angka_to_terbilang) {
    $terbilang = '';
    
    // Pastikan angka selalu 2 digit (misalnya 01, 02, 10, dst)
    $angka = str_pad($angka, 2, '0', STR_PAD_LEFT);
    
    // Proses angka dua digit (contoh 23)
    if ($angka < 10) {
        $terbilang = $angka_to_terbilang[$angka];
    } elseif ($angka < 20) {
        $terbilang = $angka_to_terbilang[$angka];
    } elseif ($angka < 100) {
        $puluhan = floor($angka / 10) * 10;
        $sisa = $angka % 10;
        if ($sisa == 0) {
            $terbilang = $angka_to_terbilang[$puluhan];
        } else {
            $terbilang = $angka_to_terbilang[$puluhan] . ' ' . $angka_to_terbilang[$sisa];
        }
    }
    
    return $terbilang;
}

// Fungsi untuk mengonversi tahun menjadi terbilang
function angka_ke_terbilang_tahun($tahun, $angka_to_terbilang) {
    $terbilang = '';
    
    // Menangani tahun dalam format 4 digit
    if (strlen($tahun) == 4) {
        $ribu = substr($tahun, 0, 1);  // Seribu
        $ratus = substr($tahun, 1, 1); // Ratusan
        $puluhan = substr($tahun, 2, 1); // Puluhan
        $sisa = substr($tahun, 3, 1);   // Satuan

        // Terbilang untuk "seribu" (tahun 1000-1999)
        $terbilang .= $angka_to_terbilang[$ribu] . " ribu ";

        // Terbilang untuk ratusan
        if ($ratus > 0) {
            $terbilang .= $angka_to_terbilang[$ratus] . " ratus ";
        }

        // Terbilang untuk puluhan
        if ($puluhan > 0) {
            $puluhan = $puluhan * 10; // mengubah puluhan ke angka yang sesuai
            $terbilang .= $angka_to_terbilang[$puluhan];
        }

        // Terbilang untuk satuan
        if ($sisa > 0) {
            $terbilang .= " " . $angka_to_terbilang[$sisa];
        }

        // Menghapus spasi ekstra di akhir
        $terbilang = trim($terbilang);
    }

    return $terbilang;
}
?>
