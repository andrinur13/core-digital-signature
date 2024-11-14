<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal-body form-type-round">
   <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label">Jenis</label>
      <div class="col-8 col-lg-10">
         <p class="col-form-label font-weight-bold">: <?php echo $data['jnsrtNama']; ?></p>
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
   } ?>
   <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label">Status Konsep</label>
      <div class="col-8 col-lg-10">
         <div class="d-flex flex-row align-items-start">
            <p class="col-form-label mr-2">:</p>
            <div class="btn btn-sm btn-bold btn-round btn-flat w-100px btn-<?php echo $data['stColor']; ?>"><?php echo $data['stNama']; ?></div>
         </div>
      </div>
   </div>
   <?php if ($data['logsCatatan'] != '') { ?>
      <div class="form-group row">
         <label class="col-4 col-lg-2 col-form-label">Catatan</label>
         <div class="col-8 col-lg-10">
            <div class="d-flex flex-row align-items-start">
               <p class="col-form-label">: <?php echo $data['logsCatatan']; ?></p>
            </div>
         </div>
      </div>
   <?php } ?>
   <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label">File</label>
      <div class="col-8 col-lg-10">
         <div class="d-flex flex-row align-items-start">
            <p class="col-form-label">:</p>
            <?php
            if (is_file($this->config->item('upload_path') . $data['srtFile']) && $data['srtFile'] != '') { ?>
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

   <div class="divider color-primary">Referensi Surat</div>
   <table class="table table-separated table-striped tab">
      <thead class="bg-color-primary1">
         <tr>
            <th class="font-weight-bold" width="15%">No. Surat</th>
            <th class="font-weight-bold">Perihal</th>
            <th class="font-weight-bold" width="20%">Jenis</th>
            <th class="font-weight-bold" width="20%">Tujuan</th>
            <th class="font-weight-bold" width="10px">File</th>
         </tr>
      </thead>
      <tbody>
         <?php if (!empty($dt_referensi_surat)) {
            foreach ($dt_referensi_surat as $k => $srtref) {
         ?>
               <tr>
                  <td nowrap><?php echo $srtref['srtNomorSurat']; ?></td>
                  <td><?php echo $srtref['srtPerihal']; ?></td>
                  <td><?php echo $srtref['jnsrtNama']; ?></td>
                  <td nowrap><?php echo $srtref['tujuan']; ?></td>
                  <td>
                     <?php
                     if (is_file($this->config->item('upload_path') . $srtref['srtFile'])) { ?>
                        <a target="_blank" href="<?php echo site_url($module . 'view_by_file/' . $srtref['srtFile']); ?>" class="" title="Lihat File"><i class="fa fa-file-text fs-20 text-success" title="Lihat File"></i></a>
                     <?php } else {
                        echo '<i class="fa fa-file-text fs-20 text-danger" title="File tidak ditemukan."></i>';
                     }
                     ?>
                  </td>
               </tr>
            <?php }
         } else { ?>
            <tr>
               <td colspan="5">Tidak ada data.</td>
            </tr>
         <?php } ?>
      </tbody>
   </table>
   <div class="divider color-primary">Lampiran Surat</div>
   <div class="form-group row">
      <div class="col-8 col-lg-10">
         <?php
         if ($data['lampiran'] != '') {
            $arr_lampiran = explode("|", $data['lampiran']);
            for ($l = 0; $l < count($arr_lampiran); $l++) {
               if (is_file($this->config->item('upload_path') . $arr_lampiran[$l]) && $arr_lampiran[$l] != '') { ?>
                  <a target="_blank" href="<?php echo site_url($module . 'view_by_file/' . $arr_lampiran[$l]); ?>" class="" title="Lihat Lampiran"> Lampiran <?php echo ($l + 1); ?></a><br>
               <?php } else { ?>
                  <span class="fa fa-warning fs-20 text-warning"></span> File tidak ditemukan.
         <?php }
            }
         }
         ?>
      </div>
   </div>
   <div class="divider color-primary">Tembusan Surat</div>
   <div class="form-group row">
      <div class="col-8 col-lg-10">
         <?php if ($data['tembusan'] != '') {
            $arr_tembusan = explode("|", $data['tembusan']);
            for ($t = 0; $t < count($arr_tembusan); $t++) {
               $no_temb = ($t + 1);
               echo ($no_temb . '. ') . $arr_tembusan[$t] . '<br>';
            }
         } ?>
      </div>
   </div>
</div>