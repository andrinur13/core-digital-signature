<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="modal-body form-type-round">
   <div class="form-group row">
      <label class="col-4 col-lg-3 col-form-label">Tanggal Disposisi</label>
      <div class="col-8 col-lg-9">
         <p class="col-form-label">: <?php echo IndonesianDate($disposisi['dispTglCreate']); ?></p>
      </div>
   </div>
   <div class="form-group row">
      <label class="col-4 col-lg-3 col-form-label">Nomor Surat</label>
      <div class="col-8 col-lg-9">
         <p class="col-form-label">: <?php echo $disposisi['srtNomorSurat']; ?></p>
      </div>
   </div>
   <div class="form-group row">
      <label class="col-4 col-lg-3 col-form-label">Perihal</label>
      <div class="col-8 col-lg-9">
         <p class="col-form-label">: <?php echo $disposisi['srtPerihal']; ?></p>
      </div>
   </div>
   <div class="form-group row">
      <label class="col-4 col-lg-3 col-form-label">Sifat Urgensi</label>
      <div class="col-8 col-lg-9">
         <p class="col-form-label">: <?php echo $disposisi['sifdisNama']; ?></p>
      </div>
   </div>
   <div class="form-group row">
      <label class="col-4 col-lg-3 col-form-label">Tujuan</label>
      <div class="col-8 col-lg-9">
         <p class="col-form-label">: <?php echo $disposisi['dispTujuan']; ?></p>
      </div>
   </div>
   <div class="form-group row">
      <label class="col-4 col-lg-3 col-form-label">Catatan</label>
      <div class="col-8 col-lg-9">
         <p class="col-form-label">: <?php echo $disposisi['dispCatatan']; ?></p>
      </div>
   </div>
</div>
<div class="divider color-primary">Isi Disposisi</div>
<?php
// print_r($dt_referensi_surat);
?>
<table class="table table-separated table-striped tab">
   <thead class="bg-color-primary1">
      <tr>
         <th class="font-weight-bold" width="20%">Penerima</th>
         <th class="font-weight-bold">Isi</th>
         <th class="font-weight-bold" width="15%" nowrap>Status Baca</th>
      </tr>
   </thead>
   <tbody>
      <?php if (!empty($disposisi_unit)) {
         foreach ($disposisi_unit as $k => $dispo) {
      ?>
            <tr>
               <td nowrap><?php echo $dispo['UnitName']; ?></td>
               <td><?php echo $dispo['disunitCatatan']; ?></td>
               <td><?php echo ($dispo['disunitTglBaca'] != '') ? '<div class="btn btn-sm btn-bold btn-round btn-flat w-100px btn-success">DIBACA</div>' : '<div class="btn btn-sm btn-bold btn-round btn-flat w-100px btn-warning">BELUM</div>'; ?></td>
            </tr>
         <?php }
      } else { ?>
         <tr>
            <td colspan="3">Tidak ada data.</td>
         </tr>
      <?php } ?>
   </tbody>
</table>