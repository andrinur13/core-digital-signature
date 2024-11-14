<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="modal-body form-type-round">
   <div class="form-group row">
      <label class="col-4 col-lg-3 col-form-label">Tanggal Surat</label>
      <div class="col-8 col-lg-9">
         <p class="col-form-label">: <?php echo IndonesianDate($data['srtTglDraft']); ?></p>
      </div>
   </div>
   <div class="form-group row">
      <label class="col-4 col-lg-3 col-form-label">Nomor Surat</label>
      <div class="col-8 col-lg-9">
         <p class="col-form-label">: <?php echo $data['srtNomorSurat']; ?></p>
      </div>
   </div>
   <div class="form-group row">
      <label class="col-4 col-lg-3 col-form-label">Perihal</label>
      <div class="col-8 col-lg-9">
         <p class="col-form-label">: <?php echo $data['srtPerihal']; ?></p>
      </div>
   </div>
</div>
<div class="divider color-primary">Distribusi</div>
<?php
// print_r($dt_referensi_surat);
?>
<table class="table table-separated table-striped tab">
   <thead class="bg-color-primary1">
      <tr>
         <th class="font-weight-bold">Penerima</th>
         <th class="font-weight-bold" width="25%">Email</th>
         <th class="font-weight-bold" width="25%">Nomor WhatsApp</th>
         <th class="font-weight-bold" width="15%" nowrap>Status Kirim</th>
      </tr>
   </thead>
   <tbody>
      <?php if (!empty($distribusi)) {
         foreach ($distribusi as $k => $dist) {
      ?>
            <tr>
               <td nowrap><?php echo $dist['distNamaPenerima']; ?></td>
               <td><?php echo $dist['distEmail']; ?></td>
               <td><?php echo $dist['distNoWA']; ?></td>
               <td><?php echo ($dist['distStatusKirim'] == '1') ? '<div class="btn btn-sm btn-bold btn-round btn-flat w-100px btn-success">TERKIRIM</div>' : '<div class="btn btn-sm btn-bold btn-round btn-flat w-100px btn-warning">BELUM</div>'; ?></td>
            </tr>
         <?php }
      } else { ?>
         <tr>
            <td colspan="4">Tidak ada data.</td>
         </tr>
      <?php } ?>
   </tbody>
</table>