<?php
if (is_file($path . $dokumen)) {
    $file = explode('.', $dokumen);
    if ($file[1] == 'pdf') { ?>
        <embed src="<?php echo base_url($path . $dokumen); ?>" width="100%" height="100%" type="application/pdf">
    <?php } else if ($file[1] == 'doc') {
        header('Content-disposition: attachment');
        header('Content-type: application/msword'); // not sure if this is the correct MIME type
        readfile($path . $dokumen);
        exit;
    } else { ?>
        <img src="<?php echo base_url($path . $dokumen); ?>" width="100%">
<?php }
} else {
    echo '<h1>File Tidak Ditemukan</h1>';
}
?>