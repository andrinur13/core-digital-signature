<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'third_party/endroid_qrcode/autoload.php';
// require APPPATH.'third_party/fpdi/src/autoload.php';

require APPPATH . 'third_party/fpdf/fpdf.php';
require APPPATH . 'third_party/fpdi/src/autoload.php';
		
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;
use Endroid\QrCode\Writer\PngWriter;

use setasign\Fpdi\PdfReader;
use setasign\Fpdi\Fpdi;

class Ppg extends Dashboard_Controller {
    public function __construct() {
        parent::__construct();
		
		
		$this->load->model($this->path.'/M_Ppg');
        $this->load->library('upload');
        $this->load->library('tcpdf/TCPDF');

        // restrict();
        
        
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

    public function add_certificate() {
        // Validasi input
        $this->form_validation->set_rules('nomorPpgMahasiswa', 'Nomor PPG Mahasiswa', 'required|trim');
        $this->form_validation->set_rules('namaMahasiswa', 'Nama Mahasiswa', 'required|trim');
        $this->form_validation->set_rules('nimMahasiswa', 'NIM Mahasiswa', 'required|trim');
        $this->form_validation->set_rules('kotaLahir', 'Kota Lahir', 'required|trim');
        $this->form_validation->set_rules('tanggalLahir', 'Tanggal Lahir', 'required|trim');
        $this->form_validation->set_rules('namaGelarGuru', 'Nama Gelar Guru', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal
            $this->session->set_flashdata('message_form', [
                'status' => 'danger',
                'title' => 'Gagal!',
                'message' => validation_errors()
            ]);
            redirect('sertifikat/Ppg');
        }

        // Upload foto
        $config['upload_path'] = './uploads/sertifikat/ppg/sertifikat/'; // Folder tujuan
        $config['allowed_types'] = 'pdf'; // Format yang diizinkan
        $config['max_size'] = 2048; // Maksimal ukuran file dalam KB (2MB)
        $config['file_name'] = 'photo_' . encode(time()) . time(); // Nama file unik

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('pathDokumen')) {
            // Jika upload gagal
            $this->session->set_flashdata('message_form', [
                'status' => 'danger',
                'title' => 'Gagal!',
                'message' => $this->upload->display_errors()
            ]);
            redirect('sertifikat/Ppg');
        } else {
            // Jika upload berhasil
            $fileData = $this->upload->data();
            $documentPath = 'uploads/sertifikat/ppg/sertifikat/' . $fileData['file_name']; // Simpan path file

            // Simpan data ke database
            $data = [
                'nomorDokumen' => $this->input->post('nomorDokumen', TRUE),
                'nomorPpgMahasiswa' => $this->input->post('nomorPpgMahasiswa', TRUE),
                'namaMahasiswa' => $this->input->post('namaMahasiswa', TRUE),
                'nimMahasiswa' => $this->input->post('nimMahasiswa', TRUE),
                'kotaLahir' => $this->input->post('kotaLahir', TRUE),
                'tanggalLahir' => $this->input->post('tanggalLahir', TRUE),
                'namaGelarGuru' => $this->input->post('namaGelarGuru', TRUE),
                'pathDokumen' => $documentPath,
                'tanggalSigned' => $this->input->post('tanggalSigned', TRUE),
                'dokUserAddDate' => date('Y-m-d H:i:s'),
                'dokUserUpdateDate' => date('Y-m-d H:i:s'),
                'pejabatanPenandatangan' => $this->input->post('pejabatanPenandatangan', TRUE),
                'jabatanPenandatangan' => $this->input->post('jabatanPenandatangan', TRUE),
                'nomorJabatanPenandatangan' => $this->input->post('nomorJabatanPenandatangan', TRUE),
                'tanggalSertifikat' => $this->input->post('tanggalSertifikat', TRUE),
                'nomorDokumen' => $this->input->post('nomorDokumen', TRUE),

            ];

            $insert = $this->M_Ppg->insert_certificate($data);

            if ($insert) {
                // Jika berhasil disimpan
                $this->session->set_flashdata('message_form', [
                    'status' => 'success',
                    'title' => 'Berhasil!',
                    'message' => 'Data sertifikat berhasil disimpan.'
                ]);
            } else {
                // Jika gagal disimpan
                $this->session->set_flashdata('message_form', [
                    'status' => 'danger',
                    'title' => 'Gagal!',
                    'message' => 'Terjadi kesalahan saat menyimpan data.'
                ]);
            }
            redirect('sertifikat/Ppg');
        }
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
        $this->resizeQRCode($qrCodePath, 200, 200);

        $this->addQRCodeToImage($image, $qrCodePath, 2030, $height - 590); // Posisi QR Code di pojok kanan bawah

        $signatures = [
            'Yogyakarta, ' .format_tanggal($data->tanggalSigned),
            'Rektor,',
            'Prof. Dr. Muchlas, M.T.'
        ];

        $this->addTextToImageWithCustomCoordinate($image, $fontArial, $signatures[0], 38, $black, 2030, 1800);
        $this->addTextToImageWithCustomCoordinate($image, $fontArial, $signatures[1], 38, $black, 2030, 1860);
        $this->addTextToImageWithCustomCoordinate($image, $fontArial, $signatures[2], 38, $black, 2030, 2160);


        $this->addCustomImageToImage($image, FCPATH . $data->photoPath, 1090, $height - 756);

        // Path output sertifikat
        $pathDoc = 'uploads/sertifikat/ppg/sertifikat/' . strtolower($this->generateRandomString(16)) . '_sertifikat.jpg';
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

    public function generate_privy($id)
    {


        $data = $this->M_Ppg->getDataDetail($id);
        
        $this->load->library('Amqp');
        $this->amqp->publish('golang_queue', json_encode([
            'function' => 'GenerateCertificatePrivy',
            'data' => $data->dokumenPpgId,
        ]));

        return redirect('sertifikat/Ppg');
    }

    public function fetch_privy($id)
    {


        $data = $this->M_Ppg->getDataDetail($id);
        
        $this->load->library('Amqp');
        $this->amqp->publish('golang_queue', json_encode([
            'function' => 'FetchCertificatePrivy',
            'data' => $data->dokumenPpgId,
        ]));

        return redirect('sertifikat/Ppg');
    }

    public function generate_detail($id)
    {


        $data = $this->M_Ppg->getDataDetail($id);
        
        $this->load->library('Amqp');
        $this->amqp->publish('golang_queue', json_encode([
            'function' => 'GenerateCertificateUAD',
            'data' => $data->dokumenPpgId,
        ]));

        return redirect('sertifikat/Ppg');
        

        // Generate QR Code (URL or text data to encode)
        $qrCode = new \Endroid\QrCode\QrCode('https://digi.andridev.id/index.php/validasi/' . encode($data->nomorPpgMahasiswa));
        $qrCode->setSize(290);  // Set the size of the QR code
        $qrCode->setMargin(0);  // Set margin to zero for better control

        // Path to save QR Code image temporarily
        $qrCodePath = FCPATH . 'uploads/sertifikat/ppg/qrcode/' . strtolower($this->generateRandomString(32)) . '.png';
        $qrCode->writeFile($qrCodePath);  // Save the QR code as a PNG file

        // Optional: Resize QR Code if needed
        $this->resizeQRCode($qrCodePath, 200, 200);  // Resize to 200x200 (optional)

        // Path to the existing PDF file (document path from the database)
        $existingPdfPath = FCPATH . $data->pathDokumen;

        $copyPdfPath = pathinfo($existingPdfPath, PATHINFO_DIRNAME) . '/' . pathinfo($existingPdfPath, PATHINFO_FILENAME) . '_signed.' . pathinfo($existingPdfPath, PATHINFO_EXTENSION);
        if (!copy($existingPdfPath, $copyPdfPath)) {
            throw new Exception('Failed to create a copy of the PDF');
        }

        $copyOfDocumentForSigned = pathinfo($existingPdfPath, PATHINFO_FILENAME) . '_signed.' . pathinfo($existingPdfPath, PATHINFO_EXTENSION);

        // Initialize FPDI to import the existing PDF
        $pdf = new FPDI();

        // Get the number of pages in the existing PDF
        $pageCount = $pdf->setSourceFile($copyPdfPath);

        // Loop through each page of the existing PDF and add the QR code
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $tplIdx = $pdf->importPage($pageNo);  // Import the page
            $size = $pdf->getTemplateSize($tplIdx);  // Get the dimensions of the imported page
            $pageWidth = $size['width'];
            $pageHeight = $size['height'];

            // Check orientation based on page width and height
            if ($pageWidth > $pageHeight) {
                $pdf->AddPage('L', array($pageWidth, $pageHeight));  // Landscape orientation
            } else {
                $pdf->AddPage('P', array($pageWidth, $pageHeight));  // Portrait orientation
            }

            // Use the imported page's content
            $pdf->useTemplate($tplIdx);

            // Custom coordinates to place the QR Code (e.g., 50 mm from the left and 150 mm from the top)
            $customX = 186;  // Custom X coordinate (horizontal position)
            $customY = 154; // Custom Y coordinate (vertical position)

            $qrCodeWidth = 24;  // Set the size of the QR code (adjustable)
            $qrCodeHeight = 24;

            // Place QR Code at custom coordinates
            $pdf->Image($qrCodePath, $customX, $customY, $qrCodeWidth, $qrCodeHeight);
        }

        $pdf->Output('F', $copyPdfPath);

        $updateData = [
            'pathDokumenSigned' => str_replace(FCPATH, '', $copyPdfPath)  // Save relative path
        ];

        $this->db->where('dokumenPpgId', $id);
        $this->db->update('dokumen_ppg', $updateData);

        return redirect('sertifikat/Ppg');
    }

    public function upload_file_excel() {

        // Load the upload library
        $config['upload_path'] = './uploads';
        $config['allowed_types'] = '*';
        $config['file_name'] = 'file.xlsx';
        $config['overwrite'] = true;

        $this->upload->initialize($config);

        if ($this->upload->do_upload('document')) { // Use 'document' as the field name
            $this->load->library('Amqp');

            $this->amqp->publish('golang_queue', json_encode([
                'function' => 'InsertDocument',
                'data' => NULL,
            ]));

            return redirect('sertifikat/ppg');

        } else {
            return redirect('sertifikat/ppg');
        }
    }

    public function fetch_all_certificate_local() {
        $this->load->library('Amqp');

        $this->amqp->publish('golang_queue', json_encode([
            'function' => 'FetchCertificateLocalAll',
            'data' => NULL,
        ]));

        return redirect('sertifikat/ppg');
    }

    public function download_privy_all() {
        $this->db->select('pathDokumenSignedByPrivy');
        $this->db->from('dokumen_ppg');
        $this->db->where('pathDokumenSignedByPrivy IS NOT NULL');
        $query = $this->db->get();
        $result = $query->result();

        $zipName = 'dokumen_privy_' . date('YmdHis') . '.zip';
        
        $zip = new ZipArchive();
        $zipPath = FCPATH . 'uploads/sertifikat/' . $zipName; // Lokasi sementara penyimpanan ZIP
        if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
            show_error('Tidak dapat membuat file ZIP.');
            return;
        }

        // Tambahkan file ke ZIP
        foreach ($result as $row) {
            $filePath = FCPATH . $row->pathDokumenSignedByPrivy;
            if (file_exists($filePath)) {
                $zip->addFile($filePath, basename($filePath)); // Tambahkan file ke ZIP
            }
        }

        // Tutup ZIP
        $zip->close();

        // Cek apakah ZIP dibuat dengan sukses
        if (!file_exists($zipPath)) {
            show_error('Gagal membuat file ZIP.');
            return;
        }

        // Berikan file ZIP untuk diunduh
        $this->load->helper('download');
        force_download($zipPath, NULL);

        // Hapus file ZIP setelah diunduh
        unlink($zipPath);
    }

    public function generate_all_certificate() {
        $this->load->library('Amqp');

        $this->amqp->publish('golang_queue', json_encode([
            'function' => 'GenerateCertificateUADAll',
            'data' => NULL,
        ]));

        return redirect('sertifikat/ppg');
    }

    public function fetch_privy_all() {
        $this->load->library('Amqp');

        $this->amqp->publish('golang_queue', json_encode([
            'function' => 'FetchCertificatePrivyAll',
            'data' => NULL,
        ]));

        return redirect('sertifikat/ppg');
    }

    public function generate_privy_all() {
        $this->load->library('Amqp');

        $this->amqp->publish('golang_queue', json_encode([
            'function' => 'GenerateCertificatePrivyAll',
            'data' => NULL,
        ]));

        return redirect('sertifikat/ppg');
    }

    public function detail($id) {
        // Fetch data detail based on the ID
        $ppg = $this->M_Ppg->getDataDetail($id);

        // Check if the data exists
        if ($ppg) {

            $data = [
                'ppg' => $ppg,
            ];

            $this->template->title( 'Sertifikat PPG' );
            $this->template->set_breadcrumb( config_item('app_name') , '' );
            $this->template->set_breadcrumb( 'Sertifikat PPG' , '' );
            
            $this->template->build('sertifikat/v_detail_ppg', $data);
        }
    }

    public function update($id)
    {
        // Load necessary models and libraries
        $this->load->model('M_Ppg'); // Example model, make sure to adjust to your actual model name
        $this->load->library('form_validation');
        $this->load->library('upload');  // Load the upload library for handling file uploads

        // Set validation rules
        $this->form_validation->set_rules('nomorPpgMahasiswa', 'Nomor Dokumen PPG', 'required');
        // $this->form_validation->set_rules('namaMahasiswa', 'Nama Mahasiswa', 'required');
        // $this->form_validation->set_rules('nimMahasiswa', 'NIM Mahasiswa', 'required');
        // $this->form_validation->set_rules('tanggalLahir', 'Tanggal Lahir', 'required');

        // Check if form validation passes
        if ($this->form_validation->run() == FALSE) {
            // Validation failed, load the form with errors
            $this->session->set_flashdata('message_form', [
                'status' => 'danger',
                'title' => 'Validation Error',
                'message' => 'Please correct the errors in the form.'
            ]);
            redirect('sertifikat/Ppg/detail/' . $id);
        } else {
            // Data to be updated
            $data = [
                'nomorPpgMahasiswa' => $this->input->post('nomorPpgMahasiswa'),
                'namaMahasiswa' => $this->input->post('namaMahasiswa'),
                'tanggalLahir' => $this->input->post('tanggalLahir'),
                'nimMahasiswa' => $this->input->post('nimMahasiswa'),
                'kotaLahir' => $this->input->post('kotaLahir'),
                'tanggalLahir' => $this->input->post('tanggalLahir'),
                'namaGelarGuru' => $this->input->post('namaGelarGuru'),
                'pejabatanPenandatangan' => $this->input->post('pejabatanPenandatangan'),
                'jabatanPenandatangan' => $this->input->post('jabatanPenandatangan'),
                'nomorJabatanPenandatangan' => $this->input->post('nomorJabatanPenandatangan'),
                'tanggalSertifikat' => $this->input->post('tanggalSertifikat'),
                'nomorDokumen' => $this->input->post('nomorDokumen'),
            ];

            if ($_FILES['pathDocument']['name']) {
                $config['upload_path'] = './uploads/sertifikat/ppg/sertifikat';
                $config['allowed_types'] = 'pdf|doc|docx|jpg|jpeg|png';
                $config['max_size'] = 4096; // Maximum file size (4MB)
                $config['file_name'] = 'photo_' . encode(time()) . time();
                $this->upload->initialize($config);

                if ($this->upload->do_upload('pathDocument')) {
                    $fileData = $this->upload->data();
                    $data['pathDokumen'] = 'uploads/sertifikat/ppg/sertifikat/' . $fileData['file_name'];  // Save the file path
                } else {
                    $this->session->set_flashdata('message_form', [
                        'status' => 'danger',
                        'title' => 'File Upload Error',
                        'message' => 'Error uploading document: ' . $this->upload->display_errors()
                    ]);
                    // redirect('sertifikat/Ppg/detail/' . $id);
                }
            }

            // Update the record in the database
            $update_successful = $this->M_Ppg->update_ppg($id, $data);

            if ($update_successful) {
                // Set a success message
                $this->session->set_flashdata('message_form', [
                    'status' => 'success',
                    'title' => 'Update Successful',
                    'message' => 'The record has been updated successfully.'
                ]);
                redirect('sertifikat/Ppg'); // Redirect to the index page
            } else {
                // Set an error message
                $this->session->set_flashdata('message_form', [
                    'status' => 'danger',
                    'title' => 'Update Failed',
                    'message' => 'There was an error updating the record.'
                ]);
                // redirect('sertifikat/Ppg/detail/' . $id); // Redirect back to the same form for corrections
            }
        }
    }
}