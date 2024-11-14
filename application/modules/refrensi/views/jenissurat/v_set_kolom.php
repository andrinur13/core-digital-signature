<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
    <?php echo form_open($this->uri->uri_string(),  'class="form-horizontal" id="form" role="form"'); ?>
    <div class="form-group row">
        <label class="col-4 col-lg-2 col-form-label" for="input-2">Kode Jenis Surat</label>
        <div class="col-8 col-lg-10">
            <p class="col-form-label">: <?= $data->jnsrtKode?></p>
            <div class="invalid-feedback"></div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-6 col-lg-2 col-form-label" for="input-2">Nama Jenis Surat</label>
        <div class="col-8 col-lg-10">
            <p class="col-form-label">: <?= $data->jnsrtNama?></p>
            <div class="invalid-feedback"></div>
        </div>
    </div>
    <div class="divider color-primary">Kolom Surat</div>
    
    <div class="scrollable-table">
        <table class="table table-separated table-striped tab">
            <thead class="bg-color-primary1">
                <tr>
                    <th class="font-weight-bold">Pilih </th>
                    <th class="font-weight-bold">Nama Kolom</th>
                    <th class="font-weight-bold">Tipe Kolom</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($kolom as $k => $kol):
                ?>
                <tr>
                    <td class="text-center">
                        <div class="custom-controls-stacked">
                            <label class="custom-control custom-checkbox">
                                <input <?= in_array($kol->kolId, $kolom_jenis) ? 'checked' : '' ?> value="<?= $kol->kolId ?>" name="jnsurkolKolomId[]" type="checkbox" class="custom-control-input">
                                <span class="custom-control-indicator"></span>
                            </label>
                        </div>
                    </td>
                    <td><?= $kol->kolNama ?></td>
                    <td class="text-center"><?= $kol->kolTipe ?></td>
                </tr>

                <?php endforeach; ?>
            </tbody>
                
            </tbody>
        </table>
    </div>
    <div class="card-footer text-right">
        <input type="hidden" name="action" value="submit">
        <button class="btn btn-label btn-bold btn-success" data-perform="confirm" type="submit">Simpan <label><i class="ti-save"></i></label></button>
    </div>
    <?php echo form_close(); ?>
