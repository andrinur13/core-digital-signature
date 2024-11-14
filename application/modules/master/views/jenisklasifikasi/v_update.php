<?php defined('BASEPATH') OR exit('No direct script access alloweds');?>
    <?php echo form_open($this->uri->uri_string(),  'class="form-horizontal" id="form" role="form"'); ?>
    <div class="card card-bordered card-body">
        <div class="row">
            <div class="form-group col-lg-6">
                <label class="text-dark">Jenis Klasifikasi Kode</label>
                <input class="form-control" type="text" name="jnsklasKode" value="<?= $data->jnsklasKode ?>">
                <span class="invalid-feedback error_jnsklasKode" id=""></span>
            </div>
            <div class="form-group col-lg-6">
                <label class="text-dark">Jenis Klasifikasi Nama</label>
                <input class="form-control" type="text" name="jnsklasNama" value="<?= $data->jnsklasNama ?>">
                <span class="invalid-feedback error_jnsklasNama" id=""></span>
            </div>
        </div>
    </div>
    <div class="card-footer text-right">
        <input type="hidden" name="action" value="submit">
        <button class="btn btn-label btn-bold btn-success" data-perform="confirm" type="submit">Simpan <label><i class="ti-save"></i></label></button>
    </div>
    <?php echo form_close(); ?>
