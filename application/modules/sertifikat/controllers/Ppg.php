<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'third_party/endroid_qrcode/autoload.php';
		
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;

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
        $template_sertifikat = FCPATH . 'assets/img/white_bg.jpeg';
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
        $fontArial = FCPATH . 'assets/fonts/Arial.ttf';
        $fontArialBold = FCPATH . 'assets/fonts/Arial_Bold.ttf';
        if (!file_exists($font_path)) {
            show_error('Font tidak ditemukan');
        }

        // Mendapatkan ukuran gambar
        $width = imagesx($image);
        $height = imagesy($image);

        // Warna untuk teks (Hitam dalam hal ini)
        $black = imagecolorallocate($image, 0, 0, 0); // RGB untuk hitam

        $font_size = 34;
        // Menambahkan teks nama di tengah
        $this->addTextToImage($image, $fontArialBold, 'Nomor: ' . $data->nomorPpgMahasiswa, $font_size, $black, 1010);

        // text wording alinea 1
        $textWording1 = [
            'Berdasarkan Surat Keputusan Menteri Pendidikan, Kebudayaan, Riset dan Teknologi Nomor 825/E/O/2022',
            'tanggal 11 November 2022 Tentang Izin Pembukaan Program Studi Pendidikan Profesi Guru Program Profesi Pada',
            'Universitas Ahmad Dahlan di Yogyakarta Yang diselenggarakan oleh Persyarikatan Muhammadiyah,',
            'Rektor Universitas Ahmad Dahlan menyatakan bahwa:'
        ];

        $this->addTextToImage($image, $fontArial, $textWording1[0], $font_size, $black, 1090);
        $this->addTextToImage($image, $fontArial, $textWording1[1], $font_size, $black, 1140);
        $this->addTextToImage($image, $fontArial, $textWording1[2], $font_size, $black, 1200);
        $this->addTextToImage($image, $fontArial, $textWording1[3], $font_size, $black, 1260);
        
        // Ukuran font untuk nama
        // Menambahkan teks nama di tengah
        $this->addTextToImage($image, $fontArialBold, $data->namaMahasiswa, 50, $black, 1350);
        $this->addTextToImage($image, $fontArialBold, 'Nomor Induk Mahasiswa: ' . $data->nimMahasiswa, 36, $black, 1410);


        $textWording2 = [
            "lahir di $data->kotaLahir pada " . tanggal_terbilang($data->tanggalLahir),
            'telah memenuhi semua syarat penyelesaian Pendidikan Profesi Guru dan LULUS Uji Kompetensi Peserta Pendidikan Profesi Guru.',
            'Kepadanya diberikan sebutan profesi GURU (Gr.) ' . $data->namaGelarGuru,
            'sesuai hak dan kewajiban yang melekat pada sebutan profesi tersebut.'
        ];

        $this->addTextToImage($image, $fontArial, $textWording2[0], 34, $black, 1480);
        $this->addTextToImage($image, $fontArial, $textWording2[1], 34, $black, 1540);
        $this->addTextToImage($image, $fontArial, $textWording2[2], 34, $black, 1600);
        $this->addTextToImage($image, $fontArial, $textWording2[3], 34, $black, 1660);

        $qrCode = new QrCode('https://digi.andridev.id/index.php/validasi/' . encode($data->nomorDokumen));
        $qrCode->setSize(290); // Ukuran QR Code (misalnya 150px)
        $qrCode->setMargin(0);
        // $qrCode->setBackgroundColor([0, 0, 0, 0]);
        // $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH);

        // Menyimpan QR Code ke file sementara
        $qrCodePath = FCPATH . 'uploads/sertifikat/ppg/qrcode/' . strtolower($this->generateRandomString(32)) . '.png';
        $qrCode->writeFile($qrCodePath);
        $this->resizeQRCode($qrCodePath, 290, 290);

        $this->addQRCodeToImage($image, $qrCodePath, 420, $height - 720); // Posisi QR Code di pojok kanan bawah

        $signatures = [
            'Yogyakarta, ' .format_tanggal($data->tanggalSigned),
            'Rektor,',
            'Prof. Dr. Muchlas, M.T.'
        ];

        $this->addTextToImageWithCustomCoordinate($image, $fontArial, $signatures[0], 38, $black, 2030, 1800);
        $this->addTextToImageWithCustomCoordinate($image, $fontArial, $signatures[1], 38, $black, 2030, 1860);
        $this->addTextToImageWithCustomCoordinate($image, $fontArial, $signatures[2], 38, $black, 2030, 2160);


        $this->addCustomImageToImage($image, FCPATH . $data->photoPath, 840, $height - 756); // Posisi QR Code di pojok kanan bawah


        // Path output sertifikat
        $pathDoc = 'uploads/sertifikat/ppg/sertifikat/' . strtolower($this->generateRandomString(32)) . '_sertifikat.jpg';
        $output_path = FCPATH . $pathDoc;

        header('Content-Type: image/jpeg');

        if (!imagejpeg($image)) {
            show_error('Gagal menghasilkan sertifikat');
        }
    
        // Membersihkan memori setelah gambar selesai diproses
        imagedestroy($image);
        unlink($qrCodePath); // Menghapus file QR Code sementara
        die;


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

    private function addTextToImageWithCustomCoordinate($image, $font_path, $text, $font_size, $color, $x_position, $y_position)
    {
        // Menghitung bounding box untuk teks
        $text_box = imagettfbbox($font_size, 0, $font_path, $text);

        // Menghitung posisi horizontal dan vertikal agar terpusat
        
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

    private function addCustomImageToImage($image, $qrCodePath, $x_position, $y_position)
    {
        // Memuat gambar QR Code
        $customImage = imagecreatefromjpeg($qrCodePath);
        if (!$customImage) {
            show_error('Gagal memuat Image');
        }

        $new_height = 480;
        $new_width = 350;

        // Mendapatkan ukuran gambar QR Code asli
        $original_width = imagesx($customImage);
        $original_height = imagesy($customImage);

        // Membuat gambar baru dengan ukuran yang sudah diskalakan
        $scaledImage = imagecreatetruecolor($new_width, $new_height);

        // Melakukan scaling gambar QR Code
        imagecopyresampled($scaledImage, $customImage, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

        // Menyalin gambar yang sudah diskalakan ke gambar utama
        imagecopy($image, $scaledImage, $x_position, $y_position, 0, 0, $new_width, $new_height);

        // Menghancurkan gambar-gambar sementara
        imagedestroy($customImage);
        imagedestroy($scaledImage);
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