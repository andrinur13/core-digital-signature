<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
   .modal-dialog {
      max-height: 100%;
   }

   .modal-body {
      max-height: calc(100vh - 143px);
      overflow-y: auto;
   }
</style>
<?php echo form_open_multipart($this->uri->uri_string(),  'class="form-horizontal" id="formUpdate" role="form"'); ?>
<div class="form-type-round">
   <?php
   // print_r($data);
   // print_r($data_kolom);
   ?>
   <div class="row">
      <div class="form-group col-lg-8">
         <label class="text-dark" for="jenis">Jenis Surat</label>
         <p class="text-success font-weight-bold">
            <input type="hidden" name="jenis" value="<?php echo encode($data['srtJenisSuratId']); ?>">
            <input type="hidden" name="status" value="<?php echo encode($data['srtStatusId']); ?>">
            <input type="hidden" name="catatan" value="<?php echo $data['logsCatatan']; ?>">
            <?php echo $data['jnsrtNama'] ?>
         </p>
      </div>
      <div class="form-group col-lg-4">
         <label class="text-dark" for="template">Pakai Template?</label>
         <p class="text-success font-weight-bold">
            <input type="hidden" name="checkbox-template" value="<?php echo $data['srtUseTemplate']; ?>">
            <input type="hidden" name="file_generated" value="<?php echo $data['srtFile']; ?>">
            <?php echo ($data['srtUseTemplate'] == '1') ? 'Ya' : 'Tidak'; ?>
         </p>
      </div>
   </div>
   <?php if ($data['srtUseTemplate'] != '1') { ?>
      <div class="row">
         <!-- <div class="form-group col-lg-6">
            <label class="text-dark" for="nomor">Nomor Surat</label>
            <input type="text" name="nomor" class="btn form-control" value="<?php echo $data['srtNomorSurat']; ?>" placeholder="Nomor Surat" autocomplete="off">
            <span class="invalid-feedback" id="error_nomor"></span>
         </div> -->
         <div class="form-group file-group col-lg-12">
            <label class="text-dark">File Surat</label>
            <div class="input-group">
               <input type="text" name="field_file_surat" class="form-control file-value file-browser" placeholder="Choose file..." readonly>
               <input type="file" name="file_surat" class="form-control">
               <span class="input-group-addon">
                  <i class="fa fa-upload"></i>
               </span>
            </div>
            <span class="invalid-feedback" id="error_field_file_surat"></span>
            <span class="help-inline text-success">
               <i class="middle">File PDF maks. 2MB</i>
            </span>
            <p>
               File Sebelumnya :
               <?php
               $url = $path_file . $data['srtFile'];
               if ((($data['srtFile'] != '')) && (is_file($url))) { ?>
                  <a target="_blank" href="<?php echo site_url($module . 'view_by_file/' . $data['srtFile']); ?>" class="btn btn-xs btn-cyan" title="Lihat File"> <i class="fa fa-search "></i>&nbsp;&nbsp; Lihat File</a>
               <?php } else {  ?>
                  <span class="text-warning"><i class="fa fa-warning" title="File tidak ditemukan."></i> File tidak ditemukan</span>
               <?php } ?>
            </p>
            <input type="hidden" name="file_uploaded" value="<?= $data['srtFile'] ?>">
         </div>
      </div>
   <?php } else { ?>
      <div class="row">
         <div class="form-group file-group col-lg-3">
            <label class="text-dark">File Surat Generate</label>
         </div>
         <div class="form-group file-group col-lg-9"> :
            <?php
            $url = $path_file . $data['srtFile'];
            if ((($data['srtFile'] != '')) && (is_file($url))) { ?>
               <input type="hidden" name="file_generated" value="<?= $data['srtFile'] ?>">
               <a target="_blank" href="<?php echo site_url($module . '/view_by_file/' . $data['srtFile']); ?>" class="btn btn-xs btn-cyan" title="Lihat File"> <i class="fa fa-search "></i>&nbsp;&nbsp; Lihat File</a>
            <?php } else {  ?>
               <span class="text-warning"><i class="fa fa-warning" title="File tidak ditemukan."></i> File tidak ditemukan</span>
            <?php } ?>
         </div>
      </div>
   <?php } ?>
   <div class="row">
      <div class="form-group col-lg-6">
         <label class="text-dark require" for="sifat">Sifat Urgensi</label>
         <select name="sifat" class="form-control">
            <option value="">-- PILIH --</option>
            <?php foreach ($ref_sifat as $sft) {
               $sft_selected = ($data['srtSifatSurat'] == $sft['id']) ? 'selected="selected"' : '';
            ?>
               <option value="<?php echo $sft['id']; ?>" <?= $sft_selected; ?>><?php echo $sft['name']; ?></option>
            <?php } ?>
         </select>
         <span class="invalid-feedback" id="error_sifat"></span>
      </div>
      <div class="form-group col-lg-6">
         <label class="text-dark require" for="klasifikasi">Klasifikasi</label>
         <select name="klasifikasi" class="form-control">
            <option value="">-- PILIH --</option>
            <?php foreach ($ref_klasifikasi as $kla) {
               $kla_selected = ($data['srtKlasifikasiId'] == $kla['id']) ? 'selected="selected"' : '';
            ?>
               <option value="<?php echo $kla['id']; ?>" <?= $kla_selected; ?>><?php echo $kla['name']; ?></option>
            <?php } ?>
         </select>
         <span class="invalid-feedback" id="error_klasifikasi"></span>
      </div>
   </div>
   <div class="row">
      <div class="form-group col-lg-6">
         <label class="text-dark require" for="tanggal">Tanggal</label>
         <input name="tanggal" class="file-value form-control" placeholder="dd-mm-yyyy" data-provide="datepicker" data-date-today-highlight="true" data-date-format="dd-mm-yyyy" type="text" value="<?php echo date('d-m-Y', strtotime($data['srtTglDraft'])); ?>" autocomplete="off">
         <span class="invalid-feedback" id="error_tanggal"></span>
      </div>
      <div class="form-group col-lg-6">
         <label class="text-dark require" for="perihal">Perihal</label>
         <input name="perihal" class="btn form-control" type="text" value="<?php echo $data['srtPerihal']; ?>" autocomplete="off">
         <span class="invalid-feedback" id="error_perihal"></span>
      </div>
   </div>
   <div class="row">
      <div class="form-group col-lg-12">
         <label class="text-dark require" for="ringkasan">Isi Ringkasan</label>
         <textarea name="ringkasan" class="form-control" rows="5"><?php echo $data['srtIsiRingkasan']; ?></textarea>
         <span class="invalid-feedback" id="error_ringkasan"></span>
      </div>
   </div>
   <?php if (empty($data_kolom)) { ?>
      <div class="alert alert-danger">
         <strong><i class="fa fa-warning"></i> Peringatan : </strong>Kolom jenis surat belum diset. Silahkan hubungi Administrator!
      </div>
      <?php } elseif (!empty($data_kolom)) {
      foreach ($data_kolom as $kk => $kolom) { ?>
         <input type="hidden" name="<?php echo 'jnskol_' . $kolom['kolId']; ?>" value="<?php echo $kolom['jnsurkolId']; ?>">
         <?php if ($kolom['kolTipe'] === 'number') {
         } elseif ($kolom['kolTipe'] === 'varchar') { ?>
            <div class="row mb-3">
               <label class="col-sm-3 col-form-label"><?php echo $kolom['kolNama'] ?></label>
               <div class="col-sm-9">
                  <input type="text" class="form-control" name="<?php echo 'kolom_' . $kolom['kolId']; ?>" autocomplete="off" placeholder="<?php echo $kolom['kolNama']; ?>" value="<?php echo set_value('kolom_' . $kolom['kolId'], $kolom['surkolKonten']) ?>">
                  <?php if ($kolom['kolId'] == '1') { ?>
                     <span class="help-inline text-success"><i class="middle ">Nomor Surat boleh kosong dan akan di Generate ketika Konsep sudah Fix.</i></span></label>
                  <?php } else { ?>
                     <span class="help-inline text-success"><i class="middle ">Isian berupa text.</i></span>

                  <?php } ?>
                  <?php echo form_error('kolom_' . $kolom['kolId']); ?>
               </div>
            </div>
         <?php } elseif ($kolom['kolTipe'] === 'date') { ?>
            <div class="row mb-3">
               <label class="col-sm-3 col-form-label"><?php echo $kolom['kolNama'] ?></label>
               <div class="col-sm-6">
                  <input type="text" class="form-control" name="<?php echo 'kolom_' . $kolom['kolId']; ?>" data-provide="datepicker" data-date-today-highlight="true" data-date-format="dd-mm-yyyy" autocomplete="off" placeholder="<?php echo $kolom['kolNama']; ?>" value="<?php echo set_value('kolom_' . $kolom['kolId'], ($kolom['surkolKonten'] != '') ? date('d-m-Y', strtotime($kolom['surkolKonten'])) : ''); ?>">
                  <span class="help-inline text-success"><i class="middle ">Format : Tanggal-Bulan-Tahun.</i></span>
                  <?php echo form_error('kolom_' . $kolom['kolId']); ?>
               </div>
            </div>
         <?php } elseif ($kolom['kolTipe'] === 'text') { ?>
            <div class="row mb-3">
               <label class="col-sm-3 col-form-label"><?php echo $kolom['kolNama'] ?></label>
               <div class="col-sm-9">
                  <textarea rows="5" name=" <?php echo 'kolom_' . $kolom['kolId']; ?>" class="form-control" placeholder="<?php echo $kolom['kolNama'] ?>"><?php echo set_value('kolom_' . $kolom['kolId'], $kolom['surkolKonten']) ?></textarea>
                  <span class="help-inline text-success"><i class="middle ">Isian berupa text atau deskripsi.</i></span>
                  <?php echo form_error('kolom_' . $kolom['kolId']); ?>
               </div>
            </div>
         <?php } elseif ($kolom['kolTipe'] === 'option') { ?>
            <p>Belum diset value.</p>
   <?php }
      }
   }
   ?>
   <div class="row">
      <div class="form-group col-lg-6">
         <label class="text-dark" for="kategori">Kategori</label>
         <div class="col custom-controls-stacked">
            <label class="custom-control custom-control-lg custom-radio">
               <input type="radio" class="custom-control-input radio-internal" name="kategori" id="radio-internal" value="internal" <?php echo ($data['srtUnitTujuanUtama'] != '') ? 'checked="checked"' : ''; ?>>
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">Internal</span>
            </label>
            <label class="custom-control custom-control-lg custom-radio">
               <input type="radio" class="custom-control-input radio-eksternal" name="kategori" id="radio-eksternal" value="eksternal" <?php echo ($data['srtTujuanSurat'] != '') ? 'checked="checked"' : ''; ?>>
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">Eksternal</span>
            </label>
         </div>
         <span class="invalid-feedback" id="error_kategori"></span>
      </div>
   </div>

   <div class="row">
      <?php $int_style = 'd-none';
      if ($data['srtUnitTujuanUtama'] != '') {
         $int_style = '';
      } ?>
      <div class="form-group col-lg-12 internal <?php echo $int_style; ?>">
         <label class="text-dark" for="tujuan_internal">Unit/Biro/Lembaga</label>
         <select name="tujuan_internal" class="btn form-control">
            <option value="">-- PILIH --</option>
            <?php foreach ($ref_unit as $unt) {
               $unt_selected = ($data['srtUnitTujuanUtama'] == $unt['id']) ? 'selected="selected"' : '';
            ?>
               <option value="<?php echo $unt['id']; ?>" <?= $unt_selected; ?>><?php echo $unt['name']; ?></option>
            <?php } ?>
         </select>
         <span class="invalid-feedback" id="error_tujuan_internal"></span>
      </div>

      <?php $eks_style = 'd-none';
      if ($data['srtTujuanSurat'] != '') {
         $eks_style = '';
      } ?>
      <div class="form-group col-lg-12 eksternal <?php echo $eks_style; ?>">
         <label class="text-dark" for="tujuan_eksternal">Tujuan Surat (Luar Universitas)</label>
         <input name="tujuan_eksternal" class="btn form-control" type="text" value="<?php echo $data['srtTujuanSurat']; ?>">
         <span class="invalid-feedback" id="error_tujuan_eksternal"></span>
      </div>
   </div>


   <div class="row">
      <div class="form-group col-lg-12 text-right">
         <a class="btn nav-link col-lg-12 text-center" data-toggle="tab" href="#" id="referensi"><i class="fa fa-plus"></i> Referensi Surat (Opsional)</a>
      </div>
      <div class="card-body" id="tbl-referensi-surat">
         <table class="table table-separated table-striped tab" cellspacing="0" id="datatables_referensi" width="100%">
            <thead class="bg-color-primary1">
               <tr>
                  <th class="font-weight-bold">No.</th>
                  <th width="1%">
                     Pilih
                  </th>
                  <th class="font-weight-bold">Tanggal</th>
                  <th class="font-weight-bold">No. Surat</th>
                  <th class="font-weight-bold">Perihal</th>
               </tr>
            </thead>
         </table>
      </div>
   </div>

   <div class="divider color-primary">Lampiran</div>
   <div class="row">
      <div class="form-group file-group col-lg-12">
         <label class="text-dark">File Lampiran </label>
         <div class="input-group">
            <input type="text" class="btn form-control file-value file-browser" placeholder="Choose file..." readonly>
            <input type="file" name="file_lampiran[]" class="btn form-control" multiple>
            <span class="input-group-addon">
               <i class="fa fa-upload"></i>
            </span>
         </div>
         <span class="help-inline text-success">
            <i class="middle">Dapat mengunggah file lebih dari satu. File PDF maks. 2MB</i>
         </span>
         <div id="listLampiran">
            <p>
               Lampiran Sebelumnya : <br>
               <?php
               if ($data['lampiran'] != '') {
                  $arr_lampiran = explode("|", $data['lampiran']);
                  $arr_lampiran_id = explode("|", $data['lampiran_id']);
                  for ($l = 0; $l < count($arr_lampiran); $l++) {
                     $url = $path_file . $arr_lampiran[$l];
                     if ((($arr_lampiran[$l] != '')) && (is_file($url))) { ?>
                        <span id="<?php echo 'link_file_' . $l; ?>">
                           <a target=" _blank" href="<?php echo site_url($module . '/view_by_file/' . $arr_lampiran[$l]); ?>" class="" title="Lihat File Lampiran">Lihat File Lampiran <?php echo ($l + 1); ?></a> <button class="btn btn-xs btn-danger" id="hapus-file-btn" data-id="<?php echo $l; ?>" data-href="<?php echo site_url($module . '/delete_lampiran/' . encode($arr_lampiran_id[$l]) . '/' . encode($data['srtId'])); ?>" title="Hapus File"> <i class="fa fa-trash"></i> </button> <br>
                        </span>
                     <?php } else {  ?>
                        <span> <i class="fa fa-warning text-warning" title=""></i> File tidak ditemukan. <br>
                        <?php } ?>
                  <?php }
               } ?>

            </p>
         </div>
      </div>
   </div>

   <div class="divider color-primary">Tembusan Internal</div>
   <div class="row">
      <div class="form-group col-lg-12">
         <label class="text-dark" for="tembusan_internal">Unit Kerja </label>
         <select name="tembusan_internal[]" class="btn form-control" data-provide="selectpicker" data-live-search="true" multiple>
            <option value="">-- Pilih Unit Kerja --</option>
            <?php foreach ($ref_unit as $tbs) {
               $internal_id = explode(',', $data['tembusan_internal'])
            ?>
               <option value="<?php echo $tbs['id']; ?>" <?php echo (in_array($tbs['id'], $internal_id)) ? 'selected="selected"' : ''; ?>><?php echo $tbs['name']; ?></option>
            <?php } ?>
         </select>
         <span class="help-inline text-success">
            <i class="middle">Dapat memilih lebih dari satu unit kerja.</i>
         </span>
      </div>
   </div>
   <div class="divider color-primary">Tembusan Eksternal</div>
   <div class="row">
      <div class="form-group col-lg-12">
         <?php if ($data['tembusan_eksternal'] != '') {
            $arr_tembusan_eks = explode("|", $data['tembusan_eksternal']);
            for ($e = 0; $e < count($arr_tembusan_eks); $e++) { ?>
               <div id="row" class="<?php echo 'row_tembusan_eks_' . $e; ?>">
                  <label class="text-dark" for="tembusan">Tembusan </label>
                  <div class="input-group">
                     <input name="tembusan_eksternal[]" class="btn form-control" type="text" placeholder="Instansi atau Personal" autocomplete="off" value="<?php echo $arr_tembusan_eks[$e]; ?>">
                     &nbsp;<button class="btn btn-danger del-tembusan-eks" type="button" data-id="<?php echo $e; ?>" data-href="<?php echo site_url($module . '/delete_tembusan_eksternal/' . encode($arr_tembusan_eks[$e]) . '/' . encode($data['srtId'])); ?>" title="Hapus Tembusan Eksternal"><i class="fa fa-trash"></i></button>
                  </div>
               </div>
         <?php }
         }
         ?>

         <div id="newinput"></div>
         <button id="rowAdder" type="button" class="btn btn-xs btn-cyan">
            <i class="fa fa-plus"></i> Tambah Tembusan
         </button>
      </div>

   </div>

</div>
<div class="card-footer text-right">
   <input type="hidden" name="action" value="submit">
   <button type="button" class="btn btn-label btn-bold btn-secondary" data-dismiss="modal">Batal<label><i class="ti-close"></i></label></button>
   <button id="btn-simpan" class="btn btn-label btn-bold btn-success" data-perform="confirm" type="submit" data-id="<?php echo $surat_id; ?>">Simpan<label><i class="ti-save"></i></label></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
   $("#rowAdder").click(function() {
      newRowAdd =
         `<div id="row">
               <label class="text-dark" for="tembusan">Tembusan </label>
               <div class="input-group">
                  <input name="tembusan_eksternal[]" class="btn form-control" type="text" placeholder="Instansi atau Personal" autocomplete="off">
                  &nbsp;<button class="btn btn-danger" id="DeleteRow" type="button"><i class="fa fa-trash"></i></button>
               </div>
            </div>`;

      $('#newinput').append(newRowAdd);
   });
   $("body").on("click", "#DeleteRow", function() {
      $(this).parents("#row").remove();
   })
</script>

<script type="text/javascript">
   $(function() {
      var ajaxParams = {};
      var setAjaxParams = function(name, value) {
         ajaxParams[name] = value;
      };

      var dt = $('#datatables_referensi').DataTable({
         "processing": true,
         "serverSide": true,
         // "searching": true,
         "ajax": {
            "url": "<?php echo site_url($module . '/datatables_referensi_surat/') . $surat_id ?>",
            "type": "POST",
            "data": function(d) {
               $.each(ajaxParams, function(key, value) {
                  d[key] = value;
               });
            }
         },
         'drawCallback': function(settings) {
            $('[data-provide="tooltip"]').tooltip();
         },
         //"dom": "<'row'<'col-md-4 col-sm-12'l<'table-group-actions pull-right'>>r><'table-scrollable't><'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'p>>",
         'language': {
            'search': 'Cari',
            'searchPlaceholder': 'Nomor Surat / Jenis Surat',
            'lengthMenu': "Tampil _MENU_",
            'info': "_START_ - _END_ dari _TOTAL_",
            "paginate": {
               "previous": "Prev",
               "next": "Next",
               "last": "Last",
               "first": "First",
               "page": "Page",
               "pageOf": "of"
            }
         },
         'order': [
            [2, 'asc']
         ],
         'columnDefs': [{
               "visible": false,
               "targets": [0]
            },
            {
               "orderable": false,
               "searchable": false,
               "targets": [0]
            },
            {
               "orderable": false,
               "searchable": false,
               "className": "table-actions text-nowrap",
               "targets": [0]
            }
         ]
      });

      dt.on('order.dt search.dt', function() {
         dt.column(0, {
            search: 'applied',
            order: 'applied'
         }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
         });
      }).draw();

      $('#referensi').on('click', function(e) {
         e.preventDefault();
         $('#tbl-referensi-surat').show();
      });

      $('#radio-internal').click(function() {
         if ($('#radio-internal').is(':checked')) {
            $(".internal").removeClass('d-none');
            $(".eksternal").addClass('d-none');
         }
      });

      $('#radio-eksternal').click(function() {
         if ($('#radio-eksternal').is(':checked')) {
            $(".internal").addClass('d-none');
            $(".eksternal").removeClass('d-none');
         }
      });

   });

   $('#listLampiran').on('click', '#hapus-file-btn', function(e) {
      e.preventDefault();
      var action = $(this).attr('data-href');
      var dataid = $(this).attr('data-id');

      app.modaler({
         html: 'Apakah Anda yakin ingin menghapus file lampiran ini?',
         title: 'Konfirmasi Hapus Lampiran',
         cancelVisible: true,
         confirmText: 'YA',
         cancelText: 'Tidak',
         cancelClass: 'btn btn-w-sm btn-secondary',
         confirmClass: 'btn btn-w-sm btn-danger',
         onConfirm: function() {
            $.get(action, function(result) {
               // var status = (result.status == true) ? 'success' : 'danger';
               if (result.status == true) {
                  $("#link_file_" + dataid).remove();
               }
               Swal.fire({
                  title: result.title,
                  text: result.msg,
                  icon: result.type
               });
            }, "json");
         }
      });
   });

   $('.del-tembusan-eks').on('click', function(e) {
      e.preventDefault();
      var action = $(this).attr('data-href');
      var dataid = $(this).attr('data-id');

      app.modaler({
         html: 'Apakah Anda yakin ingin menghapus tembusan ini?',
         title: 'Konfirmasi Hapus Tembusan',
         cancelVisible: true,
         confirmText: 'YA',
         cancelText: 'Tidak',
         cancelClass: 'btn btn-w-sm btn-secondary',
         confirmClass: 'btn btn-w-sm btn-danger',
         type: 'center',
         onConfirm: function() {
            $.get(action, function(result) {
               // var status = (result.status == true) ? 'success' : 'danger';
               if (result.status == true) {
                  $(".row_tembusan_eks_" + dataid).remove();
               }
               Swal.fire({
                  title: result.title,
                  text: result.msg,
                  icon: result.type
               });
            }, "json");
         }
      });
   });

   $('#btn-simpan').on('click', function(e) {
      e.preventDefault(); // avoid to execute the actual submit of the form.
      var id = $(this).attr('data-id');
      var modal = $('.modal.fade.show').attr('id');
      var formData = new FormData($('#formUpdate')[0]);
      var actionUrl = "<? echo site_url($module . '/update/'); ?>" + id;
      console.log(formData);
      $.ajax({
         url: actionUrl,
         type: 'POST',
         data: formData,
         contentType: false,
         processData: false,
         dataType: 'json',
         success: function(result) {
            if (result.error == 'null') {
               $('#datatables_ajax').DataTable().ajax.reload(null, false);
               $('#' + modal).modal('hide');
               Swal.fire({
                  title: "Informasi",
                  text: result.text,
                  icon: result.type
               });
            } else {
               $.each(result.error, function(i, log) {
                  if (log != '') {
                     $('[name="' + i + '"]').addClass('is-invalid');
                  } else {
                     $('[name="' + i + '"]').removeClass('is-invalid');
                  }
                  $('#error_' + i).text(log);
               });

               $('#pil-klasifikasi').selectpicker('refresh');
               $('#pil-sifat').selectpicker('refresh');
               $('#pil-tujuan-internal').selectpicker('refresh');

               Swal.fire({
                  title: "Informasi",
                  text: "Periksa Semua Form Inputan",
                  icon: "warning"
               });

            }
         },
         error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus + errorThrown);
         }
      });

   });
</script>