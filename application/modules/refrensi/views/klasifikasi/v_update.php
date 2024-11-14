<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
    <?php echo form_open($this->uri->uri_string(),  'class="form-horizontal" id="form" role="form"'); ?>
    <div class="card card-bordered card-body">
            <div class="row">
                <div class="form-group col-lg-12">
                    <label class="text-dark require" for="klasJenis">Jenis Klasifikasi</label>
                    <select name="klasJenis" id="klasJenis" required class="form-control" data-provide="selectpicker" data-live-search="true">
                    <option value="">-- Pilih Jenis Klasifikasi --</option>
                        <?php
                            $selected = '';
                            foreach($jenisKlasifikasi as $j => $jk):
                                if($jk->jnsklasId == $data->klasJenis){
                                    $selected = 'selected';
                                }else{
                                    $selected = '';
                                }
                        ?>
                            <option <?= $selected; ?> value="<?= encode($jk->jnsklasId) ?>" ><?= $jk->jnsklasKode; ?> - <?= $jk->jnsklasNama; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="invalid-feedback" id="error_klasJenis"></span>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                    <label class="text-dark require" for="klasKode">Kode Klasifikasi</label>
                    <input class="form-control" required type="text" name="klasKode" value="<?= $data->klasKode; ?>">
                    <span class="invalid-feedback" id="error_klasKode"></span>
                </div>
                <div class="form-group col-lg-6">
                    <label class="text-dark require" for="klasNama">Nama Klasifikasi</label>
                    <input class="form-control" required type="text" name="klasNama" value=" <?= $data->klasNama; ?>">
                    <span class="invalid-feedback" id="error_klasNama"></span>
                </div>
            </div>  
            <div class="row">
                <div class="form-group col-lg-6">
                    <label class="text-dark" for="klasKode">Status Klasifikasi</label>
                    <div class="switch-stacked">
                    <label class="switch switch-lg">
                        <input type="checkbox" name="klasIsAktif" <?php echo ($data->klasIsAktif == 1) ? 'checked="checked"' : ''; ?> >
                        <span class="switch-indicator"></span>
                        <span class="switch-description"></span>
                    </label>
                    </div>
                    <span class="invalid-feedback" id="error_klasIsAktif"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-right">
        <input type="hidden" name="action" value="submit">
        <button class="btn btn-label btn-bold btn-success" data-perform="confirm" type="submit">Simpan <label><i class="ti-save"></i></label></button>
    </div>
    <?php echo form_close(); ?>
