<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
  .modal-body {
    max-height: calc(100vh - 143px);
    overflow-y: auto;
  }

  tr {
    cursor: default !important;
  }
</style>

<div class="accordion mb-3" id="accordion-1">
  <div class="card">
    <h5 class="card-title">
      <a data-toggle="collapse" data-parent="#accordion-1" href="#collapse-1-1">Detail Surat</a>
    </h5>

    <div id="collapse-1-1" class="collapse show">
      <div class="card-body">
        <div class="form-group row">
          <label class="col-4 col-lg-2 col-form-label" for="input-2">Nomor Surat</label>
          <div class="col-8 col-lg-10">
            <p class="col-form-label fw-600">: <?= $detail['nomor'] ?></p>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-4 col-lg-2 col-form-label" for="input-2">Tanggal Surat</label>
          <div class="col-8 col-lg-10">
            <p class="col-form-label fw-600">: <?= IndonesianDate($detail['tanggal']) ?></p>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-4 col-lg-2 col-form-label" for="input-2">Perihal Surat</label>
          <div class="col-8 col-lg-10">
            <p class="col-form-label fw-600">: <?= $detail['perihal'] ?></p>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<?php if ($for == 'staff') { ?>
  <div class="divider color-primary">Penerima Disposisi</div>
<?php } ?>
<div class="form-group row">
  <label class="col-4 col-lg-2 col-form-label" for="input-2">Staff</label>
  <div class="col-8 col-lg-10">
    <p class="col-form-label">: <?= $detail['staff'] ?></p>
  </div>
</div>
<div class="form-group row">
  <label class="col-4 col-lg-2 col-form-label" for="input-2">Sifat Urgensi</label>
  <div class="col-8 col-lg-10">
    <p class="col-form-label">: <?= $detail['sifat_nama'] ?></p>
  </div>
</div>

<div class="form-group row">
  <label class="col-4 col-lg-2 col-form-label" for="input-2">Catatan</label>
  <div class="col-8 col-lg-10">
    <p class="col-form-label">: <?= $detail['catatan'] ?></p>
  </div>
</div>

<?php if ($for == 'unit') { ?>
  <div class="divider color-primary">Penerima Disposisi</div>
  <table class="table table-separated table-striped tab">
    <thead class="bg-color-primary1">
      <tr>
        <th class="font-weight-bold">Penerima</th>
        <th class="font-weight-bold">Instruksi</th>
        <th class="font-weight-bold">Catatan</th>
        <th class="font-weight-bold">Status Dibaca</th>
      </tr>
    </thead>
    <?php if ($detail_dispo == NULL) { ?>
      <tbody>
        <tr>
          <td colspan="4" class="text-center">Belum ada penerima disposisi.</td>
        </tr>
      </tbody>
      <?php } else {
      foreach ($detail_dispo as $val) { ?>
        <tbody>
          <tr>
            <td><?= $val['unit'] ?></td>
            <td><?= $val['instruksi'] ?></td>
            <td><?= $val['catatan'] ?></td>
            <td>
              <div class="btn btn-sm btn-bold btn-round btn-flat btn-<?= (($val['tgl_baca'] == '') ? 'warning' : 'success'); ?> w-100px"><?= $val['isbaca'] ?><?= (($val['tgl_baca'] == '') ? '' : '<br><small class="text-muted">' . IndonesianDate($val['tgl_baca']) . '</small>') ?></div>
            </td>
          </tr>
        </tbody>
    <?php }
    } ?>
  <?php } ?>
  </table>

  <footer class="mx-4 my-4 text-right">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
  </footer>