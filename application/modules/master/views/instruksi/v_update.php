<?php defined('BASEPATH') OR exit('No direct script access alloweds');?>
    <?php echo form_open($this->uri->uri_string(),  'class="form-horizontal" id="form" role="form"'); ?>
    <div class="card card-bordered card-body">
        <div class="row">
            <div class="form-group col-lg-12">
                <label class="text-dark">Nama Kolom</label>
                <input class="form-control" type="text" name="insNama" value="<?= $data->insNama ?>">
                <span class="invalid-feedback error_insNama" id=""></span>
            </div>
           
        </div>
    </div>
    <div class="card-footer text-right">
        <input type="hidden" name="action" value="submit">
        <button class="btn btn-label btn-bold btn-success" data-perform="confirm" type="submit">Simpan <label><i class="ti-save"></i></label></button>
    </div>
    <?php echo form_close(); ?>
