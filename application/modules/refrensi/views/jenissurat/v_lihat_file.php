<?php
if (is_file($path . $dokumen)) {
    $file = explode('.', $dokumen);
    if ($file[1] == 'pdf') {
?>
        <embed src="<?php echo base_url($path . $dokumen); ?>" width="100%" height="100%" type="application/pdf">
    <?php
    }elseif($file[1] == 'rtf'){
    ?>
    <iframe src="<?php echo base_url($path . $dokumen); ?>" width="500" height="300" frameBorder="0">

    <?php
    }
    
    else {
    ?>
        <img src="<?php echo base_url($path . $dokumen); ?>" width="100%">
<?php }
} else {
    echo '<h1>File Tidak Ditemukan</h1>';
}
?>