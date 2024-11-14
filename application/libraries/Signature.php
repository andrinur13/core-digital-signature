<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'third_party/fpdf/fpdf.php');
require_once(APPPATH .'third_party/fpdi/src/autoload.php');

use setasign\Fpdi\Fpdi;

class Signature
{
    public function build_pdf($data, $qrImage, $inputPdf, $outputPdf) {
        

         // Debugging: pastikan path file dan gambar dapat diakses
         if (!file_exists($inputPdf)) {
            echo json_encode(['status' => 'error', 'message' => 'File PDF input tidak ditemukan']);
            return;
        }
        if (!file_exists($qrImage)) {
            echo json_encode(['status' => 'error', 'message' => 'Gambar QR code tidak ditemukan']);
            return;
        }

        $pdf = new FPDI();
        $pageCount = $pdf->setSourceFile($inputPdf);

        // Tambahkan halaman dari file PDF input
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $pdf->AddPage();
            $pdf->useTemplate($pdf->importPage($pageNo));

            // Cari data QR code untuk halaman ini
            foreach ($data as $item) {
                if ($item['page'] == $pageNo) {
                    // Debugging: cetak posisi dan ukuran QR code
                    log_message('debug', 'Adding QR Code: Page ' . $item['page'] . ' at X: ' . $item['x'] . ', Y: ' . $item['y'] . ', Width: ' . $item['width'] . ', Height: ' . $item['height']);

                    // Tempatkan QR code ke halaman
                    $pdf->Image($qrImage, $item['x'], $item['y'], $item['width'], $item['height']);
                }
            }
        }

        // Simpan file PDF output
        $pdf->Output('F', $outputPdf);

        echo json_encode(['status' => 'success', 'fileName' => base_url($outputPdf)]);
    }
}

/* End of file Signature.php */
/* Location: ./application/libraries/Signature.php */
