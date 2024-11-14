<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
    <?php echo form_open($this->uri->uri_string(),  'class="form-horizontal" id="form" role="form"'); ?>
    <table class="table table-separated table-striped tab">
                    <thead class="bg-color-primary1">
                        <tr>
                            <th class="font-weight-bold">
                                <div class="custom-controls-stacked">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" name="select_all" class="custom-control-input select-all" onchange="selectAll()" >
                                        <span class="custom-control-indicator"></span>
                                    </label>
                                </div>
                            </th>
                            <th class="font-weight-bold">Nomor Surat</th>
                            <th class="font-weight-bold">Perihal</th>
                            <th class="font-weight-bold">Jenis Surat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($data_arsip as $da => $d){
                        ?>
                        <tr>
                            <td>
                                <div class="custom-controls-stacked">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input data-id" name="data_id[]" value="<?= encode($d->arsId); ?>">
                                        <span class="custom-control-indicator"></span>
                                    </label>
                                </div>    
                            </td>
                            <td><?= $d->nomor_surat; ?></td>
                            <td><?= $d->perhial_arsip; ?></td>
                            <td><?= $d->jnsrtNama; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
    </div>
    <div class="modal-footer text-right">
        <input type="hidden" name="action" value="submit">
        <button class="btn btn-label btn-bold btn-success" data-perform="confirm" type="submit">Simpan <label><i class="ti-save"></i></label></button>
    </div>
    <?php echo form_close(); ?>


    <script>
        $(document).on('click', '#btnNoSurat', function() {
            // alert('Berhasil');
            var nomor_surat = $('#nomor_surat').val();
            // alert(id);

            if(nomor_surat == ''){
                Swal.fire({
                    title: "Informasi",
                    text: "Nomor Surat Wajib Diisi",
                    icon: "warning"
                });
            }else{
                $.ajax({
                    type: "GET",
                    url: "<?= site_url($module.'/ajax/search_arsip');?>",
                    data: {data: nomor_surat, type: 'search_surat'},
                    success: function(res) {
                        if(res.type == 'success'){
                            $("#result_data").show();
                            $("#jenisSurat").val(res.data.jenis_surat);
                            $("#suratId").val(res.data.surat_id);

                            $("#SifatSurat").val(res.data.sifat_surat_nama);

                            $("#Klasifikasi").val(res.data.klasifikasi);
                            
                            $("#Perihal").val(res.data.perihal_surat);
                            $("#ringkasan").val(res.data.ringkasan);
                            if(res.data.kategori == 'internal'){
                                document.getElementById('radio-internal').checked = true;
                            }else{
                                document.getElementById('radio-eksternal').checked = true;
                            }
                        }else{
                            $("#result_data").hide();
                            Swal.fire({
                                title: "Informasi",
                                text: res.msg,
                                icon: res.type
                            });
                        }
                        
                    }
                });
            }
            
        });


function selectAll() {
    "use strict";
    if ($('.select-all').is(":checked")) {
        $(".data-id").prop("checked", true);
    } else {
        $(".data-id").prop("checked", false);
    }
}
    </script>
