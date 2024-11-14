<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
   /* .modal-dialog {
      max-height: 100%;
   }

   .modal-body {
      max-height: calc(100vh - 143px);
      overflow-y: auto;
   } */
</style>

<?php echo form_open_multipart($this->uri->uri_string(),  'class="form-horizontal" id="formAdd" role="form"'); ?>
<div class="modal-body form-type-round">
   <div class="row">
      <div class="form-group col-lg-8">
         <label class="text-dark require">Jenis Surat</label>
         <select name="jenis" id="pil-jenis" class="btn form-control require" data-provide="selectpicker" data-live-search="true">
            <option value="">-- Pilih Jenis Surat --</option>
            <?php foreach ($ref_jenis_surat as $jns) { ?>
               <option value="<?php echo $jns['id']; ?>"><?php echo $jns['name']; ?></option>
            <?php } ?>
         </select>
         <span class="text-danger" id="info-jenis">
            <i class="middle">Pilih Jenis Surat terlebih dahulu</i>
         </span>
         <span class="invalid-feedback" id="error_jenis"></span>
      </div>
      <div class="form-group col-lg-4">
         <label class="text-dark" for="template">Pakai Template?</label>
         <div class="col custom-controls-stacked">
            <label class="custom-control custom-control-lg custom-checkbox">
               <input type="checkbox" class="custom-control-input" name="checkbox-template" id="checkbox-template" checked value="1">
               <span class="custom-control-indicator"></span>
               <span class="custom-control-description">Ya</span>
            </label>
            <div id="info-template"><span class="text-danger">Template tidak tersedia. Mohon menghubungi Administrator!</span></div>
         </div>
      </div>
   </div>

   <div id="form-no-template" class="d-none">
      <div class="row">
         <div class="form-group col-lg-6">
            <label class="text-dark require" for="nomor">Nomor Surat</label>
            <input name="nomor" class="btn form-control" type="text" placeholder="Nomor Surat" autocomplete="off">
            <span class="invalid-feedback" id="error_nomor"></span>
         </div>
         <div class="form-group file-group col-lg-6">
            <label class="text-dark require">File Surat </label>
            <div class="input-group">
               <input type="text" name="field_file_surat" class="form-control file-value file-browser" placeholder="Choose file..." readonly>
               <input type="file" name="file_surat" class="form-control">
               <span class="input-group-addon">
                  <i class="fa fa-upload"></i>
               </span>
            </div>
            <span class="help-inline text-success">
               <i class="middle">File PDF maks. 2MB</i>
            </span>
         </div>
      </div>
   </div>

   <div class="d-none" id="formSurat">
      <div id="field-nomor-surat">
         <div class="row">
            <div class="form-group col-lg-12">
               <label class="text-dark" for="nomor">Nomor Surat</label>
               <p class="help-inline text-success">
                  <i class="middle">Nomor surat akan digenerate ketika konsep surat sudah selesai.</i>
               </p>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="form-group col-lg-6">
            <label class="text-dark require" for="sifat">Sifat Urgensi</label>
            <select name="sifat" id="pil-sifat" class="btn form-control" data-provide="selectpicker" data-live-search="true">
               <option value="">-- Pilih Sifat Urgensi --</option>
               <?php foreach ($ref_sifat as $sft) { ?>
                  <option value="<?php echo $sft['id']; ?>"><?php echo $sft['name']; ?></option>
               <?php } ?>
            </select>
            <span class="invalid-feedback" id="error_sifat"></span>
         </div>
         <div class="form-group col-lg-6">
            <label class="text-dark require" for="klasifikasi">Klasifikasi</label>
            <select name="klasifikasi" id="pil-klasifikasi" class="btn form-control" data-provide="selectpicker" data-live-search="true">
               <option value="">-- Pilih Klasifikasi --</option>
               <?php foreach ($ref_klasifikasi as $kla) { ?>
                  <option value="<?php echo $kla['id']; ?>"><?php echo $kla['name']; ?></option>
               <?php } ?>
            </select>
            <span class="invalid-feedback" id="error_klasifikasi"></span>
         </div>
      </div>
      <div class="row">
         <div class="form-group col-lg-6">
            <label class="text-dark require" for="perihal">Tanggal</label>
            <input name="tanggal" class="btn form-control" type="text" placeholder="Tanggal" autocomplete="off" data-provide="datepicker" data-date-today-highlight="true" data-date-format="dd-mm-yyyy" value="<?php echo set_value('tanggal', date('d-m-Y')); ?>">
            <span class="invalid-feedback" id="error_tanggal"></span>
         </div>
         <div class="form-group col-lg-6">
            <label class="text-dark require" for="perihal">Perihal</label>
            <input name="perihal" class="btn form-control" type="text" placeholder="Perihal" autocomplete="off">
            <span class="invalid-feedback" id="error_perihal"></span>
         </div>
      </div>
      <div class="row">
         <div class="form-group col-lg-12">
            <label class="text-dark" for="ringkasan">Isi / Ringkasan</label>
            <textarea name="ringkasan" class="form-control" rows="5" placeholder="Isi/Ringkasan"></textarea>
            <span class="invalid-feedback" id="error_ringkasan"></span>
         </div>
      </div>
      <div class="row">
         <div class="form-group col-lg-6">
            <label class="text-dark require" for="kategori">Kategori</label>
            <div class="col custom-controls-stacked">
               <label class="custom-control custom-control-lg custom-radio">
                  <input type="radio" class="custom-control-input" name="kategori" id="radio-internal" value="internal" checked>
                  <span class="custom-control-indicator"></span>
                  <span class="custom-control-description">Internal</span>
               </label>
               <label class="custom-control custom-control-lg custom-radio">
                  <input type="radio" class="custom-control-input" name="kategori" id="radio-eksternal" value="eksternal">
                  <span class="custom-control-indicator"></span>
                  <span class="custom-control-description">Eksternal</span>
               </label>
            </div>
            <span class="invalid-feedback" id="error_kategori"></span>
         </div>
      </div>
      <div class="row">
         <div class="form-group col-lg-12 internal" id="tujuan-internal">
            <label class="text-dark require" for="tujuan_internal">Tujuan Internal (Unit/Biro/Lembaga)</label>
            <select name="tujuan_internal" id="pil-tujuan-internal" class="btn form-control" data-provide="selectpicker" data-live-search="true">
               <option value="">-- Pilih Unit/Biro/Lembaga --</option>
               <?php foreach ($ref_unit as $unt) { ?>
                  <option value="<?php echo $unt['id']; ?>"><?php echo $unt['name']; ?></option>
               <?php } ?>
            </select>
            <span class="invalid-feedback" id="error_tujuan_internal"></span>
         </div>
         <div class="form-group col-lg-12 eksternal d-none" id="tujuan-eksternal">
            <label class="text-dark require" for="tujuan_eksternal">Tujuan Surat (Luar Universitas)</label>
            <input name="tujuan_eksternal" class="btn form-control" type="text" placeholder="Tujuan Surat Luar Universitas" autocomplete="off">
            <span class="invalid-feedback" id="error_tujuan_eksternal"></span>
         </div>
      </div>
   </div>

   <!-- <div class="card card-outline-secondary d-none" id="card-form-surat-kolom"> -->
   <div class="d-none" id="form-surat-kolom"></div>
   <!-- </div> -->

   <div class="d-none" id="formDefault">
      <div class="row">
         <div class="form-group col-lg-12 text-right">
            <a class="btn nav-link col-lg-12 text-center show-ref-surat" data-toggle="tab" href="#" id="referensi"><i class="fa fa-plus"></i> Referensi Surat (Opsional)</a>
         </div>
         <div class="card-body d-none" id="tbl-referensi-surat">
            <table class="table table-separated table-striped tab" cellspacing="0" id="datatables_referensi" width="100%">
               <thead class="bg-color-primary1">
                  <tr>
                     <th class="font-weight-bold" width="10px">No.</th>
                     <th class="font-weight-bold" width="10px">
                        Pilih
                     </th>
                     <th class="font-weight-bold" width="15%">Tanggal</th>
                     <th class="font-weight-bold" width="15%">No. Surat</th>
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
               <input type="text" class="form-control file-value file-browser" placeholder="Choose file..." readonly>
               <input type="file" name="file_lampiran[]" class="form-control" multiple>
               <span class="input-group-addon">
                  <i class="fa fa-upload"></i>
               </span>
            </div>
            <span class="help-inline text-success">
               <i class="middle">Dapat mengunggah file lebih dari satu. File PDF maks. 2MB</i>
            </span>
         </div>
      </div>

      <div class="divider color-primary">Tembusan Internal</div>
      <div class="row">
         <div class="form-group col-lg-12">
            <label class="text-dark" for="tembusan_internal">Unit Kerja </label>
            <select name="tembusan_internal[]" class="btn form-control" data-provide="selectpicker" data-live-search="true" multiple>
               <option value="">-- Pilih Unit Kerja --</option>
               <?php foreach ($ref_unit as $tbs) { ?>
                  <option value="<?php echo $tbs['id']; ?>"><?php echo $tbs['name']; ?></option>
               <?php } ?>
            </select>
            <span class="help-inline text-success">
               <i class="middle">Dapat memilih lebih dari satu unit kerja.</i>
            </span>
         </div>
      </div>
      <div class="divider color-primary">Tembusan Eksternal (Instansi atau Personal)</div>
      <div class="row">
         <div class="col-lg-12">
            <div id="newinput"></div>
            <button id="rowAdder" type="button" class="btn btn-xs btn-cyan">
               <i class="fa fa-plus"></i> Tambah Tembusan
            </button>
         </div>
      </div>
   </div>
</div>
<div class="card-footer text-right">
   <input type="hidden" name="action" value="submit">
   <button type="button" class="btn btn-label btn-bold btn-secondary" data-dismiss="modal">Batal<label><i class="ti-close"></i></label></button>
   <button id="btn-simpan" class="btn btn-label btn-bold btn-success d-none" data-perform="confirm" type="submit">Simpan<label><i class="ti-save"></i></label></button>
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

      $('#pil-jenis').on('change', function() {
         var jenis = $(this).val();
         if (jenis != '') {
            $('#info-jenis').addClass('d-none');
            $('#formSurat').removeClass('d-none');
            $('#formDefault').removeClass('d-none');
            $('#btn-simpan').removeClass('d-none');
            // alert(jenis);
            $.ajax({
               url: "<?= site_url($module . 'surat_kolom/') ?>" + jenis,
               type: "GET",
               dataType: "JSON",
               success: function(obj) {
                  $("#form-surat-kolom").empty();
                  $("#card-form-surat-kolom").addClass('d-none');
                  $("#form-surat-kolom").removeClass('d-none');
                  if (obj != null) {
                     $("#card-form-surat-kolom").removeClass('d-none');
                     data = Object.keys(obj).map(function(key) {
                        return obj[key];
                     });

                     for (var i = 0; i < data.length; i++) {
                        `<input type="hidden" name="jnskol_` + data[i].jnsurkolId + `" value="` + data[i].jnsurkolId + `">`
                        if (data[i].kolTipe == 'text') {
                           $("#form-surat-kolom").append(`
                              <div class="row">
                                 <label class="col-sm-3 col-form-label">` + data[i].kolNama + `</label>
                                 <div class="col-sm-9">
                                    <textarea class="form-control" rows="5" name="kolom_` + data[i].kolId + `" id="kolom_` + data[i].kolId + `" placeholder="` + data[i].kolNama + `"></textarea>
                                    <span class="help-inline text-success"><i class="middle ">Isian berupa text atau deskripsi.</i></span>
                                    <p></p>
                                 </div>
                              </div>
                        `);
                        } else if (data[i].kolTipe == 'varchar') {
                           $("#form-surat-kolom").append(`
                              <div class="row">
                                 <label class="col-sm-3 col-form-label">` + data[i].kolNama + `</label>
                                 <div class="col-sm-9">
                                    <input class="form-control" rows="5" name="kolom_` + data[i].kolId + `" id="kolom_` + data[i].kolId + `" placeholder="` + data[i].kolNama + `">
                                    <span class="help-inline text-success"><i class="middle ">Isian berupa text.</i></span>
                                    <p></p>
                                 </div>
                              </div>
                        `);
                        } else if (data[i].kolTipe == 'date') {
                           $("#form-surat-kolom").append(`
                              <div class="row">
                                 <label class="col-sm-3 col-form-label">` + data[i].kolNama + `</label>
                                 <div class="col-sm-4">
                                    <input class="form-control date" type="text" name="kolom_` + data[i].kolId + `" id="kolom_` + data[i].kolId + `" placeholder="` + data[i].kolNama + `">
                                    <span class="help-inline text-success"><i class="middle ">Format : Tanggal-Bulan-Tahun.</i></span>
                                    <p></p>
                                 </div>
                              </div>
                        `);
                        }
                     }
                     $('.date').datepicker({
                        autoclose: true,
                        clearBtn: true,
                        todayHighlight: true,
                        format: 'dd-mm-yyyy',
                        orientation: "bottom auto"
                     });
                  } else {
                     $("#form-surat-kolom").addClass('d-none');
                  }
               },
               error: function(jqXHR, textStatus, errorThrown) {}
            });
         } else {
            $('#pil-jenis').removeClass('d-none');
            $('#formSurat').addClass('d-none');
            $('#formDefault').addClass('d-none');
            $('#btn-simpan').addClass('d-none');
            $("#card-form-surat-kolom").addClass('d-none');
            $("#form-surat-kolom").addClass('d-none');

         }
      });

      $('#info-template').addClass('d-none');
      $('#pil-jenis').on('change', function() {
         var jns = $(this).val();
         if (jns != '') {
            $.ajax({
               url: "<?= site_url($module . 'check_template/') ?>" + jns,
               type: "GET",
               dataType: "JSON",
               success: function(obj) {
                  if (obj != null) {
                     if (obj.is_template == true) {
                        $('#info-template').addClass('d-none');
                     } else {
                        $('#info-template').removeClass('d-none');
                     }
                  } else {
                     $('#info-template').addClass('d-none');
                  }
               },
               error: function(jqXHR, textStatus, errorThrown) {}
            });
         } else {
            $('#info-template').removeClass('d-none');
         }
      });

      $('#referensi').on('click', function(e) {
         e.preventDefault();
         $('#tbl-referensi-surat').removeClass('d-none');
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

      $('#checkbox-template').click(function() {

         if ($('#checkbox-template').is(':checked')) {
            $("#form-no-template").addClass('d-none');
            $("#field-nomor-surat").removeClass('d-none');
         } else {
            $("#form-no-template").removeClass('d-none');
            $("#field-nomor-surat").addClass('d-none');
         }
      });

      $('#btn-simpan').on('click', function(e) {
         e.preventDefault(); // avoid to execute the actual submit of the form.
         var modal = $('.modal.fade.show').attr('id');
         var formData = new FormData($('#formAdd')[0]);
         var actionUrl = "<?php echo site_url($module . 'add/'); ?>";
         // console.log(formData);
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
   });
</script>

<script type="text/javascript">
   var ajaxParams = {};
   var setAjaxParams = function(name, value) {
      ajaxParams[name] = value;
   };

   var dt = $('#datatables_referensi').DataTable({
      "processing": true,
      "serverSide": true,
      // "searching": true,
      "ajax": {
         "url": "<?php echo site_url($module . 'datatables_referensi_surat/') . $id_enc; ?>",
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
</script>