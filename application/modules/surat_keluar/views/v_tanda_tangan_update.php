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
<div class="form-type-round" id="modalSignature">
   <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label">Jenis</label>
      <div class="col-8 col-lg-10">
         <p class="col-form-label">: <?php echo $data['jnsrtNama']; ?></p>
      </div>
   </div>
   <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label">Sifat Urgensi</label>
      <div class="col-8 col-lg-10">
         <p class="col-form-label">: <?php echo $data['sifdisNama']; ?></p>
      </div>
   </div>
   <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label">Klasifikasi</label>
      <div class="col-8 col-lg-10">
         <p class="col-form-label">: <?php echo $data['klasifikasi']; ?></p>
      </div>
   </div>
   <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label">Kategori</label>
      <div class="col-8 col-lg-10">
         <p class="col-form-label">: <?php echo ($data['srtUnitTujuanUtama'] != "") ? 'Internal' : 'Eksternal'; ?></p>
      </div>
   </div>
   <?php if (!empty($dt_kolom_surat)) {
      foreach ($dt_kolom_surat as $k => $kolsurat) {
         $konten = ($kolsurat['kolTipe'] == 'date') ? IndonesianDate($kolsurat['surkolKonten']) : $kolsurat['surkolKonten'];
   ?>
         <div class="form-group row">
            <label class="col-4 col-lg-2 col-form-label"><?php echo $kolsurat['kolNama']; ?></label>
            <div class="col-8 col-lg-10">
               <p class="col-form-label">: <?php echo $konten; ?></p>
            </div>
         </div>
   <?php }
   }  ?>
   <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label">Status Konsep</label>
      <div class="col-8 col-lg-10">
         <div class="d-flex flex-row align-items-start">
            <p class="col-form-label mr-2">:</p>
            <div class="btn btn-sm btn-bold btn-round btn-flat w-100px btn-<?php echo $data['stColor']; ?>"><?php echo $data['stNama']; ?></div>
         </div>
      </div>
   </div>
   <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label">File</label>
      <div class="col-8 col-lg-10">
         <div class="d-flex flex-row align-items-start">
            <p class="col-form-label">:</p>
            <?php
            if (is_file($this->config->item('upload_path') . $data['srtFile'])) { ?>
               <div class="card card-bordered ml-2">
                  <div class="media align-items-center">
                     <i class="fa fa-file-text fs-20 text-success"></i>
                     <p class="font-weight-bold" id="filename-surat">
                        <a target="_blank" href="<?php echo site_url($module . 'view_by_file/' . $data['srtFile']); ?>" class="" title="Lihat File"> Lihat File</a>
                     </p>
                  </div>
               </div>
            <?php } else {
               echo '<div class="card card-bordered ml-2"><div class="media align-items-center"><span class="fa fa-warning fs-20 text-warning"></span> File tidak ditemukan.</div></div>';
            }
            ?>
         </div>
      </div>
   </div>

   <?php
   // print_r($data);
   // print_r($ref_pejabat);
   ?>
   <div class="divider color-primary">Generate Nomor Surat dan Tanda Tangan Surat Keluar</div>
   <div id="formFixed" class="formFixed">
      <div id="hasil_generate_nomor">
         <div class="form-group row">
            <label class="col-4 col-lg-3 col-form-label">Nomor Surat</label>
            <div class="col-8 col-lg-9">
               <input type="hidden" name="nomor_surat" id="nomor_surat" value="<?php echo $data['srtNomorSurat']; ?>">
               <p class="col-form-label text-success">: <?php echo $data['srtNomorSurat']; ?></p>
            </div>
            <span class="invalid-feedback" id="error_nomor_surat"></span>
         </div>
      </div>
      <form class="form-horizontal" id="formNomor" role="form" action="<?php echo site_url($module . 'update_nomor/') . encode($data['srtId']); ?>">
         <input type="hidden" name="filename_surat_uploaded" id="filename_surat_uploaded" value="<?php echo $data['srtFile']; ?>">
         <div class="row">
            <div class="form-group col-lg-12">
               <label class="text-dark" for="penandatangan">Pejabat Penandatangan</label>
               <select name="penandatangan" id="penandatangan" class="btn form-control" onchange="changePtd(this);" data-provide="selectpicker" data-live-search="true">
                  <option value="">-- PILIH --</option>
                  <?php foreach ($ref_pejabat as $pjb) {
                     $selected = ($data['srtPejabatPtdId'] == $pjb['id']) ? 'selected="selected"' : '';
                  ?>
                     <option value="<?php echo $pjb['id']; ?>" <?php echo $selected; ?>><?php echo $pjb['name']; ?></option>
                  <?php } ?>
               </select>
               <span class="invalid-feedback" id="error_penandatangan"></span>
            </div>
         </div>
         <div id="formPenandatangan" class="d-none">
            <div class="row">
               <div class="form-group col-lg-6">
                  <label class="text-dark require" for="pejabat_nama">Pejabat Nama</label>
                  <input type="hidden" name="action_final" value="submit">
                  <input type="text" name="pejabat_nama" id="pejabat_nama" class="btn form-control" autocomplete="off" placeholder="Pejabat Nama" value="<?php echo $detail_pejabat['pjbNama'] ?>">
                  <span class="invalid-feedback" id="error_pejabat_nama"></span>
               </div>
               <div class="form-group col-lg-6">
                  <label class="text-dark require" for="pejabat_nipm">Pejabat NIPM</label>
                  <input type="text" name="pejabat_nipm" id="pejabat_nipm" class="btn form-control" autocomplete="off" placeholder="Pejabat NIPM" value="<?php echo $detail_pejabat['pjbNipm'] ?>">
                  <span class="invalid-feedback" id="error_pejabat_nipm"></span>
               </div>
            </div>
            <div class="row">
               <div class="form-group col-lg-12">
                  <label class="text-dark require" for="pejabat_jabatan">Pejabat Jabatan</label>
                  <input type="text" name="pejabat_jabatan" id="pejabat_jabatan" class="btn form-control" autocomplete="off" placeholder="Pejabat Jabatan" value="<?php echo $detail_pejabat['pjbJabatan'] ?>">
                  <span class="invalid-feedback" id="error_pejabat_jabatan"></span>
               </div>
            </div>
         </div>

         <div id="formGenerateNomor" class="d-none">
            <div class="row">
               <div class="form-group col-lg-12">
                  <label class="text-dark require" for="nomor">Nomor Surat</label>
                  <div class="input-group">
                     <input type="hidden" name="action_nomor" value="submit">
                     <input type="text" name="nomor" id="nomor" value="" class="btn form-control" autocomplete="off" placeholder="Nomor Surat" disabled>
                     <a class="btn btn-label btn-dark text-white" id="generate-btn" data-id="<?php echo encode($data['srtId']); ?>">Generate Nomor<label><i class="fa fa-refresh"></i></label></a>
                     <button class="btn btn-label btn-cyan text-white" id="simpan-generate-btn" data-perform="confirm" type="submit">Simpan Nomor<label><i class="ti-save"></i></label></button>
                  </div>
               </div>
            </div>
         </div>
      </form>
      <div id="link-unduh-surat">
         <?php if ($data['srtNomorSurat'] != '') { ?>
            <div class="form-group row">
               <label class="col-4 col-lg-3 col-form-label">Unduh Surat Keluar</label>
               <div class="col-8 col-lg-9">
                  <p class="col-form-label">: <a class="link" target="_blank" href="<?php echo site_url($module . 'view_by_file/') . $data['srtFile']; ?>" title="Unduh Surat">Link Unduh Surat</a></p>
               </div>
            </div>
      </div>
   <?php }  ?>
   </div>

   <div id="info-form-unggah"></div>

   <form class="form-horizontal" id="formDoSignature" role="form" action="" enctype="multipart/form-data">
      <input type="hidden" name="file_final_uploaded" id="file_final_uploaded" value="<?php echo $data['srtFile']; ?>">
      <input type="hidden" name="id_surat" value="<?php echo encode($data['srtId']); ?>">
      <input type="hidden" name="action_final" id="action_final" value="submit">
      <div id="formUnggahSurat" class="d-none">
         <?php if ($is_file_template) { ?>
            <div class="row">
               <div class="form-group file-group col-lg-12">
                  <label class="text-dark">Unggah Surat</label>
                  <div class="input-group">
                     <input type="text" name="file_final" class="form-control file-value file-browser" placeholder="Choose file..." readonly>
                     <input type="file" name="file_surat_final" class="form-control">
                     <span class="input-group-addon">
                        <i class="fa fa-upload"></i>
                     </span>
                  </div>
                  <span class="invalid-feedback" id="error_file_final"></span>
                  <span class="help-inline text-success">
                     <i class="middle">File PDF maks. 2MB</i>
                  </span>
               </div>
            </div>
         <?php } ?>
      </div>
   </form>

</div>
</div>
<div class="card-footer text-right">
   <button type="button" class="btn btn-label btn-bold btn-secondary" data-dismiss="modal">Batal<label><i class="ti-close"></i></label></button>
   <button class="btn btn-label btn-bold btn-success btn-simpan" id="btn-simpan" data-perform="confirm" type="button">Simpan<label><i class="ti-save"></i></label></button>
</div>

<script type="text/javascript">
   $(function() {
      $('#btn-simpan').addClass('d-none');
      $('#simpan-generate-btn').addClass('d-none');
      $('#formUnggahSurat').addClass('d-none');

      if ($('#nomor_surat').val() != '') {
         $('#btn-simpan').removeClass('d-none');
         $('#formUnggahSurat').removeClass('d-none');
         $('#formPenandatangan').removeClass('d-none');
      }

      $('#generate-btn').on('click', function() {
         var idsurat = $(this).attr('data-id');
         var idptd = $('#penandatangan').val();
         if (idptd != '') {
            $.ajax({
               url: "<?= site_url($module . 'generate/') ?>" + idsurat + '/' + idptd,
               type: "POST",
               dataType: "JSON",
               success: function(result) {
                  $('#nomor').empty();
                  if (result != null) {
                     $('#formPenandatangan').removeClass('d-none');
                     $('#nomor').val(result.nomor);
                     $('#nomor').removeAttr('disabled');
                     $('#simpan-generate-btn').removeClass('d-none');
                     $('#pejabat_nama').val(result.ptd.pjbNama);
                     $('#pejabat_nipm').val(result.ptd.pjbNipm);
                     $('#pejabat_jabatan').val(result.ptd.pjbJabatan);
                  } else {
                     $('#nomor').empty();
                  }
               },
               error: function(jqXHR, textStatus, errorThrown) {}
            });
         } else {
            Swal.fire({
               title: "Peringatan",
               text: "Penandatangan belum dipilih.",
               icon: "warning"
            });
         }
      });

      // ----------------- Simpan Generate Nomor -----------------//
      // $("#formNomor").submit(function(e) {
      $('#formNomor').on('click', '#simpan-generate-btn', function(e) {
         e.preventDefault(); // avoid to execute the actual submit of the form.
         var actionUrl = "<?php echo site_url($module . 'update_nomor/') . $surat_id; ?>";
         var modal = $('.modal.fade.show').attr('id');
         app.modaler({
            html: 'Apakah Anda yakin ingin menyimpan Nomor Surat ini?',
            title: 'Konfirmasi Simpan Nomor Surat',
            cancelVisible: true,
            confirmText: 'YA',
            cancelText: 'Tidak',
            cancelClass: 'btn btn-w-sm btn-secondary',
            confirmClass: 'btn btn-w-sm btn-success',
            type: 'right',
            onConfirm: function() {
               $.ajax({
                  type: "POST",
                  url: actionUrl,
                  data: $('#formNomor').serialize(), // serializes the form's elements.
                  success: function(response) {
                     if (response.status == true) {
                        $('#generate-btn').addClass('d-none');
                        $('#simpan-generate-btn').addClass('d-none');
                        $('#formUnduhSurat').removeClass('d-none');
                        $('#formGenerateNomor').addClass('d-none');
                        $('#formUnggahSurat').removeClass('d-none');
                        $('#link-unduh-surat').replaceWith(`
                           <div id="link-unduh-surat">
                              <div class="form-group row">
                                 <label class="col-4 col-lg-3 col-form-label">Unduh Surat Keluar</label>
                                 <div class="col-8 col-lg-9">
                                    <p class="col-form-label">: <a class="link" target="_blank" href="<?php echo site_url($module . '/view_by_file/'); ?>` + response.filename_surat + `" title="Unduh Surat">Link Unduh Surat</a></p>
                                 </div>
                              </div>
                           </div>
                        `);

                        $('#hasil_generate_nomor').replaceWith(`
                           <div id="hasil_generate_nomor">
                              <div class="form-group row">
                                 <label class="col-4 col-lg-3 col-form-label">Nomor Surat</label>
                                 <div class="col-8 col-lg-9">
                                    <input type="hidden" name="nomor_surat" id="nomor_surat" value="` + response.nomor + `">
                                    <p class="col-form-label text-success">: ` + response.nomor + `</p>
                                 </div>
                              </div>
                           </div>
                        `);

                        $('#filename-surat').replaceWith(`
                           <p class="font-weight-bold" id="filename-surat">
                              <input type="hidden" name="filename_surat_uploaded" id="filename_surat_uploaded" value="` + response.filename_surat + `">
                              <a target="_blank" href="<?php echo site_url($module . 'view_by_file/'); ?>` + response.filename_surat + `" class="" title="Lihat File"> Lihat File</a>
                           </p>
                        `);

                        $('#file_final_uploaded').val(response.filename_surat);
                        $('#formPenandatangan').removeClass('d-none');
                        $('#btn-simpan').removeClass('d-none');
                        $('#datatables_ajax').DataTable().ajax.reload(null, false);

                        Swal.fire({
                           title: "Informasi",
                           text: response.text,
                           icon: response.type
                        });
                        // return false;
                     } else {
                        $.each(response.error, function(i, log) {
                           if (log != '') {
                              $('[name="' + i + '"]').addClass('is-invalid');
                           }
                           $('#error_' + i).text(log);
                        });

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
            }
         });
      });

      $('#btn-simpan').on('click', function(e) {
         e.preventDefault(); // avoid to execute the actual submit of the form.
         var modal = $('.modal.fade.show').attr('id');
         var formData = new FormData($('#formDoSignature')[0]);
         var actionUrl = "<?php echo site_url($module . 'signature/') . $surat_id; ?>";

         $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(result) {
               if (result.error == 'null') {
                  $('#' + modal).modal('hide');
                  $('#datatables_ajax').DataTable().ajax.reload(null, false);
                  Swal.fire({
                     title: "Informasi",
                     text: result.text,
                     icon: result.type
                  });
               } else {
                  $.each(result.error, function(i, log) {
                     if (log != '') {
                        $('[name="' + i + '"]').addClass('is-invalid');
                     }

                     $('#error_' + i).text(log);

                  });

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

   function changePtd(selectPtd) {
      $('#formUnggahSurat').addClass('d-none');
      $('#link-unduh-surat').addClass('d-none');
      $('#formPenandatangan').addClass('d-none');

      var value = selectPtd.value;
      if (value == '') {
         $('#formNomorPenandatangan').addClass('d-none');
         $('#formGenerateNomor').addClass('d-none');
         $('#btn-simpan').addClass('d-none');
         $('#simpan-generate-btn').addClass('d-none');
      } else {
         $('#nomor').val("");
         $('#nomor').prop('disabled', true);
         $('#formGenerateNomor').removeClass('d-none');
         $('#generate-btn').removeClass('d-none');
         // $('#formNomorPenandatangan').removeClass('d-none');
         // $('#btn-simpan').removeClass('d-none');
         $('#pejabat_nama').val("");
         $('#pejabat_nipm').val("");
         $('#pejabat_jabatan').val("");
         $('#simpan-generate-btn').addClass('d-none');
      }
   }
</script>