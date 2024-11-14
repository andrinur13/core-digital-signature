<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!-- <div class="col-lg-12">
  <div class="card shadow-2">
    <div class="card-body form-type-round"> -->

<div class="accordion mb-3" id="accordion-1">
  <div class="card">
    <h5 class="card-title">
      <a data-toggle="collapse" data-parent="#accordion-1" href="#collapse-1-1">Detail Surat</a>
    </h5>

    <div id="collapse-1-1" class="collapse show">
      <div class="card-body">

        <div class="form-group row mb-0">
          <label class="col-4 col-lg-2 col-form-label" for="input-2">Asal</label>
          <div class="col-8 col-lg-10">
            <p class="col-form-label fw-600">: <?= $detail['asal'] ?></p>
          </div>
        </div>
        <div class="form-group row mb-0">
          <label class="col-4 col-lg-2 col-form-label" for="input-2">Nomor Surat</label>
          <div class="col-8 col-lg-10">
            <p class="col-form-label fw-600">: <?= $detail['nomor'] ?></p>
          </div>
        </div>
        <div class="form-group row mb-0">
          <label class="col-4 col-lg-2 col-form-label" for="input-2">Perihal</label>
          <div class="col-8 col-lg-10">
            <p class="col-form-label">: <?= $detail['perihal'] ?></p>
          </div>
        </div>
        <div class="form-group row d-none">
          <label class="col-4 col-lg-2 col-form-label" for="input-2">Tujuan</label>
          <div class="col-8 col-lg-10">
            <p class="col-form-label">: Biro Sistem Informasi</p>
          </div>
        </div>
        <div class="form-group row mb-0">
          <label class="col-4 col-lg-2 col-form-label" for="input-2">Tanggal Surat</label>
          <div class="col-8 col-lg-10">
            <p class="col-form-label">: <?= IndonesianDate($detail['tanggal']) ?></p>
          </div>
        </div>
        <div class="form-group row mb-0">
          <label class="col-4 col-lg-2 col-form-label" for="input-2">Sifat Urgensi</label>
          <div class="col-8 col-lg-10">
            <p class="col-form-label">: <?= $detail['sifat'] ?></p>
          </div>
        </div>
        <div class="form-group row mb-0">
          <label class="col-4 col-lg-2 col-form-label" for="input-2">Isi Ringkasan</label>
          <div class="col-8 col-lg-10">
            <p class="col-form-label">: <?= $detail['ringkasan'] ?></p>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="divider color-primary">Intruksi</div>
<div class="form-group row">
  <label class="col-4 col-lg-2 col-form-label" for="input-2">Tanggal</label>
  <div class="col-8 col-lg-10">
    <p class="col-form-label">: <?= IndonesianDate($detail['tgl_dispo']) ?></p>
    <div class="invalid-feedback"></div>
  </div>
</div>
<div class="form-group row">
  <label class="col-4 col-lg-2 col-form-label" for="input-2">Dari</label>
  <div class="col-8 col-lg-10">
    <p class="col-form-label fw-500">: <?= $detail['asal_dispo'] ?></p>
    <div class="invalid-feedback"></div>
  </div>
</div>
<div class="form-group row">
  <label class="col-4 col-lg-2 col-form-label" for="input-2">Sifat Disposisi</label>
  <div class="col-8 col-lg-10">
    <p class="col-form-label fw-500">: <?= $detail['sifat'] ?></p>
    <div class="invalid-feedback"></div>
  </div>
</div>
<div class="form-group row">
  <label class="col-4 col-lg-2 col-form-label" for="input-2">Catatan Disposisi</label>
  <div class="col-8 col-lg-10">
    <p class="col-form-label">: <?= $detail['catatan_dispo'] ?></p>
    <div class="invalid-feedback"></div>
  </div>
</div>
<?php if ($isStaff != 'staff') { ?>
  <div class="form-group row d-none">
    <label class="col-4 col-lg-2 col-form-label" for="input-2">Tujuan</label>
    <div class="col-8 col-lg-10">
      <p class="col-form-label">: <?= $detail['tujuan'] ?></p>
      <div class="invalid-feedback"></div>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-4 col-lg-2 col-form-label" for="input-2">Instruksi</label>
    <div class="col-8 col-lg-10">
      <p class="col-form-label fw-600">: <?= $detail['instruksi'] ?></p>
      <div class="invalid-feedback"></div>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-4 col-lg-2 col-form-label" for="input-2">Catatan Instruksi</label>
    <div class="col-8 col-lg-10">
      <p class="col-form-label fw-600">: <?= $detail['catatan_instruksi'] ?></p>
      <div class="invalid-feedback"></div>
    </div>
  </div>
<?php } ?>


<div class="mx-4 my-4 text-right">
  <?php if ($isStaff == 'staff') { ?>
    <button class="btn btn-round btn-custom mx-1" type="button" onclick="draft('<?= encode(urlencode($detail['id'])) ?>');">Buat Draft</button>
  <?php } ?>
  <button type="button" class="btn btn-secondary float-right" onclick="tutup()">Tutup</button>
  <!-- <button type="button" class="btn btn-secondary mt-3 float-right" data-dismiss="modal">Tutup</button> -->
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $(".close").addClass('d-none');
  });

  function tutup() {
    $('#datatables_ajax').DataTable().ajax.reload(false, null);
    $(".modal.fade.show").modal('hide');
  }

  var modalAdd = function(opt) {
    opt = opt || {};
    app.modaler({
      title: 'Tambah Konsep Surat Keluar',
      url: opt.url,
      footerVisible: false,
      size: "lg",
      bodyExtraClass: "form-type-round",
      onConfirm: opt.callback,
      backdrop: false,
    });
  }

  function draft(id) {
    var module = "<?php echo site_url('surat_keluar/Konsep/add/') ?>" + id;
    modalAdd({
      url: module,
      callback: function(modal) {}
    });

    $(".modal.fade.show").modal('hide');
  }
</script>