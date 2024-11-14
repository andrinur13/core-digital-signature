<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
    <?php echo form_open($this->uri->uri_string(),  'class="form-horizontal" id="form" role="form"'); ?>
    <div class="card card-bordered card-body">
        <div class="row">
            <div class="form-group col-lg-6">
                <label class="text-dark" for="jnsrtKode">Kode Jenis Surat</label>
                <input class="form-control" type="text" name="jnsrtKode" value="<?= $data->jnsrtKode ?>">
                <span class="invalid-feedback error_jnsrtKode" id=""></span>
            </div>
            <div class="form-group col-lg-6">
                <label class="text-dark" for="jnsrtNama">Nama Jenis Surat</label>
                <input class="form-control" type="text" name="jnsrtNama" value="<?= $data->jnsrtNama ?>">
                <span class="invalid-feedback error_jnsrtNama" id=""></span>
            </div>           
        </div>
        <div class="row">
            <div class="form-group file-group col-lg-12">
                <label class="text-dark">File Template Surat</label>
                <div class="input-group">
                    <input type="text" class="form-control file-value file-browser" placeholder="Choose file..." readonly>
                    <input type="file" name="file_surat" class="form-control">
                    <span class="input-group-addon">
                        <i class="fa fa-upload"></i>
                    </span>
                </div>
                <small class="form-text">Harap unggah file template word dan save as dengan format <b>.rtf</b> </small>
                <input type="hidden" name="file_uploaded" value="<?= $data->jnsrtTemplate ?>">
                <span>
                    File Sebelumnya :
                    <?php
                    $url = $path_file . $data->jnsrtTemplate;
                    if ((($data->jnsrtTemplate != '')) && (is_file($url))) { ?>
                    <a target="_blank" href="<?php echo site_url($module . '/view_by_file/' . $data->jnsrtTemplate); ?>" class="btn btn-xs btn-cyan" title="Lihat File"> <i class="fa fa-search "></i>&nbsp;&nbsp; Lihat File</a>
                    <?php } else {  ?>
                    <i class="fa fa-warning " title="File tidak ditemukan."></i>
                    <?php } ?>
                </span>
            </div>

        </div>
    </div>
    <div class="card-footer text-right">
        <input type="hidden" name="action" value="submit">
        <button class="btn btn-label btn-bold btn-success" data-perform="confirm" type="submit">Simpan <label><i class="ti-save"></i></label></button>
    </div>
    <?php echo form_close(); ?>
