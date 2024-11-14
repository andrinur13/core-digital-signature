<?php
require_once('vendor/autoload.php');

use setasign\Fpdi\Tcpdf\Fpdi;

// Path to your certificate and private key
$certificate = 'cert/certificate.pem';
$privateKey = 'sert/kuncu_privat.pem';
$password = 'bsiuad'; // jika menggunakan password pada file


$cert = file_get_contents($certificate);
$privateKey = array(file_get_contents($privateKey), $password);


$tempPdfPath = 'output_pdf_with_qr.pdf';

// Load the modified PDF
$pdf = new Fpdi();
$pdf->setSourceFile($tempPdfPath);
$pageCount = $pdf->getNumPages();

for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
    $pdf->AddPage();
    $pdf->useTemplate($pdf->importPage($pageNo));
}

// Add a signature field (if needed)
try {
    $pdf->SetSignature($cert, $privateKey, $password);
    // Set signature appearance
    $pdf->setSignatureAppearance(180, 50, 60, 15);

    // Save the signed PDF
    $signedPdfPath = 'signed_pdf.pdf';

    // Clean the output buffer before sending the PDF
    if (ob_get_length()) {
        ob_end_clean();
    }

    // Output the PDF to the browser
    $pdf->Output($signedPdfPath, 'D'); // 'I' untuk output ke browser

    echo 'PDF signed successfully!';
} catch (Exception $e) {
    echo 'Failed to sign PDF: ',  $e->getMessage(), "\n";
}
?>
