<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="col-lg-12">
  <div class="card shadow-2">
    <div class="card-body form-type-round">
      <div class="form-group row">
        <label class="col-4 col-lg-2 col-form-label" for="input-2">Jenis</label>
        <div class="col-8 col-lg-10">
          <p class="col-form-label">: <?= $detail['jenis_surat'] ?></p>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-4 col-lg-2 col-form-label" for="input-2">Asal</label>
        <div class="col-8 col-lg-10">
          <p class="col-form-label">: <?= $detail['asal'] ?></p>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-4 col-lg-2 col-form-label" for="input-2">Nomor Surat</label>
        <div class="col-8 col-lg-10">
          <p class="col-form-label">: <?= $detail['nomor'] ?></p>
        </div>
      </div>
      <div class="form-group row">
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
      <div class="form-group row">
        <label class="col-4 col-lg-2 col-form-label" for="input-2">Tanggal Diterima</label>
        <div class="col-8 col-lg-10">
          <p class="col-form-label">: <?= IndonesianDate($detail['tanggal']) ?></p>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-4 col-lg-2 col-form-label" for="input-2">Sifat Urgensi</label>
        <div class="col-8 col-lg-10">
          <p class="col-form-label">: <?= $detail['sifat'] ?></p>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-4 col-lg-2 col-form-label" for="input-2">Isi Ringkasan</label>
        <div class="col-8 col-lg-10">
          <p class="col-form-label">: <?= $detail['ringkasan'] ?></p>

        </div>
      </div>
      <div class="form-group row">
        <label class="col-4 col-lg-2 col-form-label" for="input-2">Status Tindakan</label>
        <div class="col-8 col-lg-10">
          <div class="d-flex flex-row align-items-start">
            <p class="col-form-label mr-2">:</p>
            <div id="status-area">
              <?php if ($status != NULL) {
                foreach ($status as $val) { ?>
                  <div class="mr-1 btn btn-sm btn-bold btn-round btn-flat btn-<?= $val['color'] ?>"><?= $val['nama'] ?></div>
              <?php }
              } else {
                echo '-';
              } ?>
            </div>
          </div>
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

      <div class="card shadow-2 p-3">
        <div class="form-group row">
          <div class="col-4">
            <a class="btn btn-sm btn-round btn-custom" href="javascript:void()" onclick="draft('<?= encode(urlencode($detail['id'])) ?>')">Balas Surat</a>
          </div>
        </div>

        <h5>Daftar Surat Balasan</h5>
        <table class="table table-bordered table-striped table-sm" width="100%" id="datatables_ajax">
          <thead class="bg-color-primary1">
            <tr>
              <th>Tanggal</th>
              <th>Sifat</th>
              <th>Perihal</th>
              <th>Jenis</th>
              <th>Status</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($balasan != NULL) {
              foreach ($balasan as $val) { ?>
                <tr>
                  <td><?= IndonesianDate($val['tanggal']) ?></td>
                  <td><?= $val['sifat'] ?></td>
                  <td><?= $val['perihal'] ?></td>
                  <td><?= $val['jenis_surat'] ?></td>
                  <td>
                    <div class="btn btn-sm btn-bold btn-round btn-flat w-100px btn-<?= $val['color'] ?>"><?= $val['status'] ?></div>
                  </td>
                  <td class="text-center">
                    <a href="#" type="button" class="btn btn-square btn-round btn-warning" data-toggle="tooltip" title="buka file surat"><i class="fa fa-file" onclick="file('<?= $val['file'] ?>')" style="cursor:pointer"></i></a>
                    <a href="#" type="button" class="btn btn-square btn-round btn-success d-none" data-toggle="tooltip" title="ubah draft surat" onclick="arahan('nCDI6QYQbbIsw-kvn_kqvkWxXwSmmLpDUbJv1WTFanvg_<?= urlencode(encode($val['id'])) ?>')"><i class="fa fa-pencil"></i></a>
                  </td>
                </tr>
              <?php }
            } else { ?>
              <tr>
                <td colspan="6" class="text-center">Balasan surat tidak ada.</td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
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
      footerVisible: opt.footer,
      confirmVisible: false,
      onConfirm: opt.callback,
      title: opt.title,
      headerVisible: true,
      size: opt.size,
      type: opt.type,
      backdrop: false,
    });
  }

  function draft_(id) {
    module = "<?= site_url('surat_masuk/Surat/draft?tipe=view&id=') ?>" + id;
    Modal({
      url: module,
      title: 'Draft Surat',
      footer: true,
      size: 'lg',
      callback: function(modal) {}
    })
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
  }

  $(function() {
    var ajaxParams = {};
    var setAjaxParams = function(name, value) {
      ajaxParams[name] = value;
    };
    $dt = $('#datatables_ajax').DataTable({
      "processing": false,
      "serverSide": false,
      "searching": false,
      "paging": false,

      'order': [
        [0, 'DESC']
      ],
      "columnDefs": [{
          "orderable": false,
          "targets": [1, 2, 3, 4, 5]
        },
        {
          "className": "text-center",
          "targets": []
        },
        {
          "className": "nowrap",
          "targets": [0, 1, 2]
        },
      ]
    });
  });
</script>