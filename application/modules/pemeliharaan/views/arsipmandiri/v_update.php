<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
    <?php echo form_open($this->uri->uri_string(),  'class="form-horizontal" id="form" role="form"'); ?>
    <div class="card card-bordered card-body">
        <div class="row">
            <div class="form-group col-lg-6">
                <label class="text-dark required"> Klasifikasi</label>
                <select name="brksKlasifikasiId" class="form-control brksKlasifikasiId"  data-provide="selectpicker">
                    <option value="">-- Pilih Klasifikasi --</option>
                    <?php
                        $selected = '';
                        foreach($KlasifikasiList as $k => $kl):
                            if($kl->klasId == $data->brksKlasifikasiId){
                                $selected = 'selected';
                            }else{
                                $selected = '';
                            }
                    ?>
                    <option <?= $selected; ?> value="<?= encode($kl->klasId); ?>"><?= $kl->klasifikasi ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="invalid-feedback" id="error_brksKlasifikasiId"></span>
            </div>
            <div class="form-group col-lg-6">
                <label class="text-dark require">Nomor Berkas</label>
                <input class="form-control brksNomor" type="text"  name="brksNomor" value="<?= $data->brksNomor; ?>">
                <small class="form-text text-warning">Nomor Berkas Akan Muncul Ketika Memilih Klasifikasi</small>
                <span class="invalid-feedback" id="error_brksNomor"></span>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-12">
                <label class="text-dark require">Nama Berkas</label>
                <input class="form-control" type="text" name="brksNama" id="brksNama" value="<?= $data->brksNama; ?>">
                <span class="invalid-feedback" id="error_brksNama"></span>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-12">
                <label class="text-dark">Keterangan Berkas</label>
                <textarea class="form-control" rows="5" name="brksKeterangan" id="brksKeterangan"><?= $data->brksKeterangan; ?></textarea>
                <span class="invalid-feedback" id="error_brksKeterangan"></span>
            </div>
        </div>
    </div>
    <div class="card-footer text-right">
        <input type="hidden" name="action" value="submit">
        <button class="btn btn-label btn-bold btn-success" data-perform="confirm" type="submit">Simpan <label><i class="ti-save"></i></label></button>
    </div>
    <?php echo form_close(); ?>


    <script>
    $(function() {

        $(".brksKlasifikasiId").change(function(){
            var id = $(this).val();
            $.ajax({
                type: "GET",
                url: "<?= site_url($module.'/ajax/select');?>",
                data: {data:id, type : 'select_nomor_klasifikasi'},
                // dataType: "json",
                success: function (res) {
                    var json = $.parseJSON(res);
                    $(".brksNomor").val(json.data);
                }
            });
        });
       
       
    });
</script>