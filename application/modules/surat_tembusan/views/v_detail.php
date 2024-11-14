<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="card shadow-2">
  <div class="card-body form-type-round">
    <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label" for="input-2">Jenis</label>
      <div class="col-8 col-lg-10">
        <p class="col-form-label">: <?= $detail['jenis_surat'] ?></p>
        <div class="invalid-feedback"></div>
      </div>
    </div>
    <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label" for="input-2">Asal</label>
      <div class="col-8 col-lg-10">
        <p class="col-form-label">: <?= $detail['asal'] ?></p>
        <div class="invalid-feedback"></div>
      </div>
    </div>
    <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label" for="input-2">Nomor Surat</label>
      <div class="col-8 col-lg-10">
        <p class="col-form-label">: <?= $detail['nomor'] ?></p>
        <div class="invalid-feedback"></div>
      </div>
    </div>
    <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label" for="input-2">Perihal</label>
      <div class="col-8 col-lg-10">
        <p class="col-form-label">: <?= $detail['perihal'] ?></p>
        <div class="invalid-feedback"></div>
      </div>
    </div>
    <div class="form-group row d-none">
      <label class="col-4 col-lg-2 col-form-label" for="input-2">Tujuan</label>
      <div class="col-8 col-lg-10">
        <p class="col-form-label">: Biro Sistem Informasi</p>
        <div class="invalid-feedback"></div>
      </div>
    </div>
    <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label" for="input-2">Tanggal Diterima</label>
      <div class="col-8 col-lg-10">
        <p class="col-form-label">: <?= IndonesianDate($detail['tanggal']) ?></p>
        <div class="invalid-feedback"></div>
      </div>
    </div>
    <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label" for="input-2">Sifat Urgensi</label>
      <div class="col-8 col-lg-10">
        <p class="col-form-label">: <?= $detail['sifat'] ?></p>
        <div class="invalid-feedback"></div>
      </div>
    </div>
    <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label" for="input-2">Isi Ringkasan</label>
      <div class="col-8 col-lg-10">
        <p class="col-form-label">: <?= $detail['ringkasan'] ?></p>
        <div class="invalid-feedback"></div>
      </div>
    </div>

    <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label" for="input-2">Status Arsip</label>
      <div class="col-8 col-lg-10">
        <div class="d-flex flex-row align-items-start">
          <p class="col-form-label mr-2">:</p>
          <?php if ($detail['is_arsip'] == '') { ?>
            <div class="btn btn-sm btn-bold btn-round btn-flat btn-warning w-100px">Belum</div>
          <?php } else { ?>
            <div class="btn btn-sm btn-bold btn-round btn-flat btn-success w-100px">diarsip</div>
          <?php } ?>
        </div>
      </div>
    </div>
    <div class="form-group row">
      <label class="col-4 col-lg-2 col-form-label" for="input-2">File</label>
      <div class="col-8 col-lg-10">
        <div class="d-flex flex-row align-items-start">
          <p class="col-form-label">:</p>
          <div class="card card-bordered ml-2" onclick="file('<?= $detail['file'] ?>')" style="cursor:pointer">
            <div class="media align-items-center">
              <i class="fa fa-file-text fs-20 text-danger"></i>
              <p class="font-weight-bold"><?= $detail['file'] ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card card-bordered card-body">

      <div class="row">
        <div class="col-lg-6">
          <a id="btn-arsip" class="btn btn-sm btn-round btn-custom mb-3 <?= ($detail['is_arsip'] == '') ? '' : 'd-none' ?>" href="javascript:void()" onclick="set_arsip('<?= encode(urlencode($detail['id'])) ?>')">Arsipkan</a>
        </div>
      </div>

      <div id="info-arsip" class="<?= ($detail['is_arsip'] != '') ? '' : 'd-none' ?>">
        <p class="text-dark fw-600">Surat telah diarsipkan.</p>
        <div class="form-group row">
          <label class="col-4 col-lg-2 col-form-label" for="input-2">Berkas</label>
          <div class="col-8 col-lg-10">
            <p class="col-form-label" id="arsip_berkas">: <?= $detail['berkas_nama'] ?></p>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-4 col-lg-2 col-form-label" for="input-2">Jenis Eksemplar</label>
          <div class="col-8 col-lg-10">
            <p class="col-form-label" id="arsip_eksemplar">: <?= $detail['eksemplar_nama'] ?></p>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-6">
            <a id="ubah_arsip" class="btn btn-sm btn-round btn-custom mb-3" href="javascript:void()" onclick="ubah_arsip('<?= encode(urlencode($detail['is_arsip'])) ?>','<?= encode(urlencode($detail['id'])) ?>')">Ubah Arsip</a>
          </div>
        </div>
      </div>

    </div>

  </div>

  <!-- modal file -->
  <div class="modal" id="modal-file" tabindex="-1" style="min-height: 100%;">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-body">
        <div class="modal-content">
          <object id="embed-file" data="" type="application/pdf" style="min-height:100vh;width:100%"></object>
        </div>
      </div>
    </div>
  </div>


  <script type="text/javascript">
    function file(data) {
      var file = "<?= base_url($path . '/'); ?>" + data;
      $('#embed-file').attr('data', file);
      $('#modal-file').modal('show');
    }


    var Modal = function(opt) {
      opt = opt || {};
      app.modaler({
        onHide: opt.hide,
        url: opt.url,
        footerVisible: false,
        onConfirm: opt.callback,
        title: opt.title,
        headerVisible: true,
        size: opt.size,
        type: opt.type,
        backdrop: false,
      });
    }

    function set_arsip(id) {
      module = "<?= site_url('surat_masuk/Surat/arsip?tipe=view&mode=add&suratId=') ?>" + id;
      Modal({
        url: module,
        title: 'Arsipkan Surat',
        size: 'md',
        callback: function(modal) {}
      })
    }

    function ubah_arsip(id, suratId) {
      var params = '&id=' + id + '&suratId=' + suratId;
      module = "<?= site_url('surat_masuk/Surat/arsip?tipe=view&mode=update') ?>" + params;
      Modal({
        url: module,
        title: 'Ubah Arsipkan Surat',
        size: 'md',
        callback: function(modal) {}
      })
    }
  </script>