<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

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
      <label class="col-4 col-lg-2 col-form-label">Tanggal</label>
      <div class="col-8 col-lg-10">
         <p class="col-form-label">: <?php echo IndonesianDate($data['srtTglDraft']); ?></p>
      </div>
   </div>
   <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label">Klasifikasi</label>
      <div class="col-8 col-lg-10">
         <p class="col-form-label">: <?php echo $data['klasifikasi']; ?></p>
      </div>
   </div>
   <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label">Nomor Surat</label>
      <div class="col-8 col-lg-10">
         <p class="col-form-label">: <?php echo $data['srtNomorSurat']; ?></p>
      </div>
   </div>
   <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label">Perihal</label>
      <div class="col-8 col-lg-10">
         <p class="col-form-label">: <?php echo $data['srtPerihal']; ?></p>
      </div>
   </div>
   <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label">Kategori</label>
      <div class="col-8 col-lg-10">
         <p class="col-form-label">: <?php echo ($data['srtUnitTujuanUtama'] != "") ? 'Internal' : 'Eksternal'; ?></p>
      </div>
   </div>
   <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label">Tujuan</label>
      <div class="col-8 col-lg-10">
         <p class="col-form-label">: <?php echo $data['tujuan']; ?></p>
      </div>
   </div>
   <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label">Isi Ringkasan</label>
      <div class="col-8 col-lg-10">
         <p class="col-form-label">: <?php echo $data['srtIsiRingkasan']; ?></p>
      </div>
   </div>
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
</div>
<div class="divider color-primary">Referensi Surat</div>
<?php
// print_r($dt_referensi_surat);
?>
<table class="table table-separated table-striped tab">
   <thead class="bg-color-primary1">
      <tr>
         <th class="font-weight-bold" width="10%">No. Surat</th>
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
               <td><?php echo $srtref['tujuan']; ?></td>
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