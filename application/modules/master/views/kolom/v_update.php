<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
    <?php echo form_open($this->uri->uri_string(),  'class="form-horizontal" id="form" role="form"'); ?>
    <div class="card card-bordered card-body">
        <div class="row">
            <div class="form-group col-lg-6">
                <label class="text-dark">Nama Kolom</label>
                <input class="form-control" type="text" name="kolNama" value="<?= $data->kolNama ?>">
                <span class="invalid-feedback error_kolNama" id=""></span>
            </div>
            <div class="form-group col-lg-6">
                <label class="text-dark">Jenis Kolom</label>
                <select class="form-control" name="kolTipe">
                <option value="">-- Pilih Jenis Kolom --</option>
                    <?php
                    $jenisKolom = array('number', 'varchar', 'date', 'text', 'options');

                    $select = '';
                    foreach ($jenisKolom as $jenis) {
                        if($jenis == $data->kolTipe){
                            $select = 'selected';
                        }else{
                            $select = '';
                        }
                        echo "<option $select value='{$jenis}'>" . ucfirst($jenis) . "</option>";
                    }
                    ?>
                    
                </select>
                <span class="invalid-feedback error_kolTipe" id=""></span>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-12">
                <label class="text-dark">Variable Kolom</label>
                <input class="form-control" type="text" name="kolVariable" placeholder="Silahkan Gunakan %KOLOM_VARIABLE%" value="<?= $data->kolVariable ?>">
                <span class="invalid-feedback" id="error_kolVariable"></span>
            </div>
        </div>
    </div>
    <div class="card-footer text-right">
        <input type="hidden" name="action" value="submit">
        <button class="btn btn-label btn-bold btn-success" data-perform="confirm" type="submit">Simpan <label><i class="ti-save"></i></label></button>
    </div>
    <?php echo form_close(); ?>
