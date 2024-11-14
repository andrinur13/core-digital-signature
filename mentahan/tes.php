<?php
$data = "data to sign";
$certPemPath = 'D:/laragon/www/digital-signature/cert/sertifikat.pem';
$privateKeyPemPath = 'D:/laragon/www/digital-signature/cert/kunci_privat_decrypted.pem';
$signedDataPath = 'signed_data.txt';

// Load certificate
$certPem = file_get_contents($certPemPath);
if ($certPem === false) {
    die('Failed to load certificate.');
}

// Load private key
$privateKeyPem = file_get_contents($privateKeyPemPath);
$privateKey = openssl_pkey_get_private($privateKeyPem);
if ($privateKey === false) {
    die('Failed to load private key.');
}

// Jika kunci privat terenkripsi, gunakan frase dekripsi
$phrase = ''; // Jika tidak terenkripsi, gunakan string kosong

// Format array dengan kunci dan frase
$privateKeyArray = array($privateKey, $phrase);

// Save the data to be signed to a temporary file
file_put_contents('data.txt', $data);

// Sign the data
$success = openssl_pkcs7_sign(
    'input.txt',          // Input file
    $signedDataPath,     // Output file
    $certPem,            // Sertifikat dalam format PEM
    $privateKeyArray,    // Kunci privat dan frase (jika diperlukan)
    array()              // Header opsional (kosongkan jika tidak diperlukan)
);

if ($success) {
    echo 'Data signed successfully!';
} else {
    echo 'Failed to sign data.';
    $error = openssl_error_string();
    echo 'OpenSSL error: ' . $error;
}
?>
