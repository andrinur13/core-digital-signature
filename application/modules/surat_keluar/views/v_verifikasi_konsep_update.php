<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php echo form_open_multipart($this->uri->uri_string(),  'class="form-horizontal" id="form" role="form"'); ?>
<div class="modal-body form-type-round">
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
                     <p class="font-weight-bold">
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

   <div class="divider color-primary">Verifikasi Konsep Surat Keluar</div>
   <div class="row">
      <div class="form-group col-lg-12">
         <label class="text-dark" for="catatan">Catatan</label>
         <textarea name="catatan" class="form-control" rows="5"></textarea>
         <span class="invalid-feedback" id="error_catatan"></span>
      </div>
   </div>
   <div class="row">
      <div class="form-group col-lg-12">
         <label class="text-dark" for="status">Status Surat</label>
         <select name="status" class="form-control" onchange="changeStatus(this)">
            <option value="">-- PILIH --</option>
            <?php foreach ($ref_status as $stat) { ?>
               <option value="<?php echo $stat['id']; ?>"><?php echo $stat['name']; ?></option>
            <?php } ?>
         </select>
         <span class="invalid-feedback" id="error_status"></span>
      </div>
   </div>

   <div id="formFixed" class="formFixed" style="display: none;">
      <div class="row">
         <div class="form-group col-lg-6">
            <label class="text-dark" for="pejabat">Pejabat Penandatangan</label>
            <select name="pejabat" class="form-control">
               <option value="">-- PILIH --</option>
               <?php foreach ($ref_pejabat as $pjb) { ?>
                  <option value="<?php echo $pjb['id']; ?>"><?php echo $pjb['name']; ?></option>
               <?php } ?>
            </select>
            <span class="invalid-feedback" id="error_pejabat"></span>
         </div>

         <!-- <div class="form-group col-lg-6">
            <label class="text-dark" for="ttd">Tanda Tangan</label>
            <select name="ttd" class="form-control" onchange="changeTtd(this)">
               <option value="">-- PILIH --</option>
               <?php foreach ($ref_ttd as $ttd) { ?>
                  <option value="<?php echo $ttd['id']; ?>"><?php echo $ttd['name']; ?></option>
               <?php } ?>
            </select>
            <span class="invalid-feedback" id="error_ttd"></span>
         </div> -->
      </div>
      <div class="row">
         <div class="form-group col-lg-12 ttdText" id="ttdText" style="display: none;">
            <label class="text-dark" for="ttd_isi">Isi Ringkas</label>
            <textarea name="ttd_isi" class="form-control" rows="5"></textarea>
            <span class="invalid-feedback" id="error_ttd_isi"></span>
         </div>
         <div class="form-group file-group col-lg-12 ttdFile" id="ttdFile" style="display: none;">
            <label class="text-dark">File Tanda Tangan</label>
            <div class="input-group">
               <input type="text" class="form-control file-value file-browser" placeholder="Choose file..." readonly>
               <input type="file" name="file_ttd" class="form-control">
               <span class="input-group-addon">
                  <i class="fa fa-upload"></i>
               </span>
            </div>
            <span>
               File Sebelumnya :
               <?php
               $url = $path_file . $data['srtFile'];
               if ((($data['srtFile'] != '')) && (is_file($url))) { ?>
                  <a target="_blank" href="<?php echo site_url($module . 'view_by_file/' . $data['srtFile']); ?>" class="btn btn-xs btn-cyan" title="Lihat File"> <i class="fa fa-search "></i>&nbsp;&nbsp; Lihat File</a>
               <?php } else {  ?>
                  <i class="fa fa-warning " title="File tidak ditemukan."></i>
               <?php } ?>
            </span>
            <input type="hidden" name="file_ttd_uploaded" value="<?= $data['srtFile'] ?>">
         </div>
      </div>
   </div>
</div>
<div class="card-footer text-right">
   <input type="hidden" name="action" value="submit">
   <button class="btn btn-label btn-bold btn-success" data-perform="confirm" type="submit">Simpan Verifikasi<label><i class="ti-save"></i></label></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
   $(function() {

   });

   /*function changeStatus(selectStatus) {
      var value = selectStatus.value;
      if (value == '4') {
         $('.formFixed').css("display", "block");
      } else {
         $('.formFixed').css("display", "none");
      }
   }

   function changeTtd(selectTtd) {
      var value = selectTtd.value;
      if (value == '0') {
         $('.ttdText').css("display", "block");
         $('.ttdFile').css("display", "none");
      } else {
         $('.ttdFile').css("display", "block");
         $('.ttdText').css("display", "none");
      }
   }*/
</script>