<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

use Endroid\QrCode\QrCode;

class Ppg extends Dashboard_Controller {
    public function __construct() {
        parent::__construct();
		
		
		$this->load->model($this->path.'/M_Ppg');
        
        
		// $this->load->helper($this->master.'/general');

		// $this->general = new GeneralHelper();
        // $this->module = $this->general->module($this->path, $this->mod);
        
        // restrict();
    }

    public function index() {
        $data = $this->M_Ppg->getDataPpg();

        $tpl['module'] = 'system/Unit';
		$tpl['dt_ppgs'] = $data;

        $this->template->title( 'Sertifikat PPG' );
		$this->template->set_breadcrumb( config_item('app_name') , '' );
		$this->template->set_breadcrumb( 'Sertifikat PPG' , '' );
		
		$this->template->build('sertifikat/v_index_ppg', $tpl);
    }

    public function pdf($id) {
        $data = $this->M_Ppg->getDataDetail($id);

		$this->generate($data);
        //dd($data);
    }

    public function generate_all() {
        $datas = $this->M_Ppg->getData();

        foreach($datas as $data) {
            $this->generate($data);
        }

		// $this->session->set_flashdata('msg', array('status' => 'success', 'title' => 'Informasi', 'message' => 'Data berhasil digenerate.'));
		redirect('sertifikat/Ppg');
    }

    public function generate($data)
    {
        // Path ke template sertifikat
        $template_sertifikat = FCPATH . 'assets/img/sertifikat_ppg.jpg';
        if (!file_exists($template_sertifikat)) {
            show_error('Template sertifikat tidak ditemukan');
        }

        // Memuat gambar template
        $image = imagecreatefromjpeg($template_sertifikat);
        if (!$image) {
            show_error('Gagal memuat template sertifikat');
        }

        // Path ke font kustom
        $font_path = FCPATH . 'assets/fonts/font.ttf';
        if (!file_exists($font_path)) {
            show_error('Font tidak ditemukan');
        }

        // Mendapatkan ukuran gambar
        $width = imagesx($image);
        $height = imagesy($image);

        // Warna untuk teks (Hitam dalam hal ini)
        $black = imagecolorallocate($image, 0, 0, 0); // RGB untuk hitam

        $font_size = 20;
        // Menambahkan teks nama di tengah
        $this->addTextToImage($image, $font_path, 'Nomor : ' . $data->nomorPpgMahasiswa, $font_size, $black, 530);

        // Ukuran font untuk nama
        $font_size = 38;
        // Menambahkan teks nama di tengah
        $this->addTextToImage($image, $font_path, $data->namaMahasiswa, $font_size, $black, 730);

        // Ukuran font untuk NIM
        $font_size_nim = 20;
        // Menambahkan teks NIM di tengah
        $this->addTextToImage($image, $font_path, 'NIM : ' . $data->nimMahasiswa, $font_size_nim, $black, 780);

        $qrCode = new QrCode('https://digi.andridev.id/index.php/validasi/' . $data->nomorDokumen);
        $qrCode->setSize(100); // Ukuran QR Code (misalnya 150px)
        $qrCode->setMargin(0);
        // $qrCode->setBackgroundColor([0, 0, 0, 0]);
        // $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH);

        // Menyimpan QR Code ke file sementara
        $qrCodePath = FCPATH . 'uploads/sertifikat/ppg/' . strtolower($this->generateRandomString(32)) . '.png';
        $qrCode->writeFile($qrCodePath);
        $this->resizeQRCode($qrCodePath, 100, 100);

        $this->addQRCodeToImage($image, $qrCodePath, $width - 630, $height - 280); // Posisi QR Code di pojok kanan bawah

        // Path output sertifikat
        $pathDoc = 'uploads/sertifikat/ppg/' . strtolower($this->generateRandomString(32)) . '_sertifikat.jpg';
        $output_path = FCPATH . $pathDoc;


        // Menyimpan file gambar
        if (!imagejpeg($image, $output_path)) {
            show_error('Gagal menyimpan sertifikat');
        }

        $data = $this->M_Ppg->update($data->dokumenPpgId, [
            'pathDokumen' => $pathDoc,
        ]);

        // Membersihkan memori
        // imagedestroy($image);

        // Menampilkan hasil sertifikat yang telah dibuat
        // $this->load->helper('url');
        // redirect(base_url('uploads/sertifikat/ppg/' . basename($output_path)));
    }

    private function addTextToImage($image, $font_path, $text, $font_size, $color, $y_position)
    {
        // Menghitung bounding box untuk teks
        $text_box = imagettfbbox($font_size, 0, $font_path, $text);
        $text_width = $text_box[2] - $text_box[0]; // Lebar teks
        $text_height = $text_box[1] - $text_box[5]; // Tinggi teks

        // Menghitung posisi horizontal dan vertikal agar terpusat
        $x_position = (imagesx($image) - $text_width) / 2; // Posisi horizontal di tengah
        
        // Menambahkan teks ke gambar
        imagettftext($image, $font_size, 0, $x_position, $y_position, $color, $font_path, $text);
    }

    private function addQRCodeToImage($image, $qrCodePath, $x_position, $y_position)
    {
        // Memuat gambar QR Code
        $qrImage = imagecreatefrompng($qrCodePath);
        if (!$qrImage) {
            show_error('Gagal memuat QR Code');
        }

        // Menambahkan QR Code ke gambar template sertifikat
        imagecopy($image, $qrImage, $x_position, $y_position, 0, 0, imagesx($qrImage), imagesy($qrImage));

        // Membersihkan memori
        imagedestroy($qrImage);
    }

    private function resizeQRCode($qrCodePath, $newWidth, $newHeight)
    {
        // Memuat gambar QR Code yang asli
        $qrImage = imagecreatefrompng($qrCodePath);
        if (!$qrImage) {
            show_error('Gagal memuat gambar QR Code untuk resize');
        }

        // Membuat gambar baru dengan ukuran yang diinginkan
        $resizedQRCode = imagecreatetruecolor($newWidth, $newHeight);

        // Resize gambar QR Code
        imagecopyresampled($resizedQRCode, $qrImage, 0, 0, 0, 0, $newWidth, $newHeight, imagesx($qrImage), imagesy($qrImage));

        // Menyimpan gambar QR Code yang sudah diresize ke file sementara
        imagepng($resizedQRCode, $qrCodePath); // Menimpa file QR Code dengan yang telah diresize

        // Membersihkan memori
        imagedestroy($qrImage);
        imagedestroy($resizedQRCode);
    }

    function generateRandomString($length = 16) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        
        return $randomString;
    }

}