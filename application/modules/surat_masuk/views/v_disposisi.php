<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
  .modal-dialog.besar {
    max-width: <?= (!isset($tipe_update) ? '80%' : '50%') ?>;
    max-height: 80%;
  }

  .modal-body {
    max-height: calc(100vh - 143px);
    overflow-y: auto;
  }

  tr {
    cursor: default !important;
  }
</style>

<form id="input_form" class="form-horizontal form-type-round" method="POST" action="" enctype="multipart/form-data">
  <div class="row">
    <div class="<?= (!isset($tipe_update) ? 'col-md-6 col-12' : (($tipe_update == 'unit') ? 'col-12' : 'd-none')) ?>">
      <div class="card p-3" style="min-height: 100%;">
        <h5 class="<?= (!isset($tipe_update) ? '' : (($tipe_update == 'unit') ? 'd-none' : '')) ?>"><u>Kepada Unit Kerja</u></h5>
        <div class="row">
          <div class="form-group col-8 d-none">
            <label class="text-dark require">Tujuan</label>
            <input class="form-control" type="text" name="tujuan" id="tujuan" value="<?= (!isset($detail) ? '' : $detail['tujuan']) ?>">
            <span class="invalid-feedback" id="error_tujuan"></span>
          </div>
          <div class="form-group col-12">
            <label class="text-dark require">Sifat Urgensi</label>
            <select class="form-control" name="sifat" id="sifat">
              <option value="">Pilih Sifat Urgensi</option>
              <?php foreach ($sifat as $val) { ?>
                <option value="<?= $val['id'] ?>" <?= (!isset($detail) ? '' : (($detail['sifat'] == $val['id']) ? 'selected="select"' : '')); ?>><?= $val['nama'] ?></option>
              <?php } ?>
            </select>
            <small class="text-danger" id="error_sifat"></small>
          </div>
          <div class="form-group col-12">
            <label class="text-dark require">Catatan</label>
            <textarea class="form-control" rows="3" name="catatan" id="catatan"><?= ((isset($detail)) ? $detail['catatan'] : '') ?></textarea>
            <span class="invalid-feedback" id="error_catatan"></span>
          </div>
        </div>

        <input type="hidden" name="id" value="<?= encode(urlencode($id)) ?>">


        <div class="mt-3">

          <div class="row">
            <div class="col-12">
              <div class="">
                <button type="button" class="btn btn-xs btn-round btn-warning mb-3 float-right" href="javascript:void()" onclick='add_unit()'>+ Unit Penerima</button>
                <input type="hidden" id="key_unit" value="<?= ((isset($detail_dispo)) ? (($detail_dispo == NULL) ? 0 : count($detail_dispo)) : 0) ?>">

              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">

              <table class="table table-sm table-bordered table-striped" width="100%" id="table-unit">
                <thead>
                  <tr>
                    <td width="5%" class="fw-500">Aksi</td>
                    <td class="fw-500">Unit</td>
                    <td width="15%%" class="fw-500">Instruksi</td>
                    <td class="fw-500">Catatan</td>
                    <?php if (isset($detail_dispo)) { ?>
                      <td width="15%" class="fw-500">Dibaca</td>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody id="unit_area">
                  <tr id="kosong_area">
                    <?php if (isset($detail_dispo)) {
                      if ($detail_dispo == NULL) { ?>
                        <td colspan="4" class="text-center">Belum ada unit kerja penerima disposisi.</td>
                      <?php }
                    } else { ?>
                      <td colspan="4" class="text-center">Belum ada unit kerja penerima disposisi.</td>
                    <?php } ?>
                  </tr>

                  <?php if (isset($detail_dispo)) {
                    foreach ($detail_dispo as $key => $val) { ?>
                      <tr id="unit_<?= $key ?>">
                        <td class="text-center">
                          <div class="btn-group btn-group-sm">
                            <button class="btn btn-sm dropdown-toggle bg-warning py-0 px-1" data-toggle="dropdown">Aksi</button>
                            <div class="dropdown-menu dropdown-menu-left">
                              <a class="dropdown-item" href="javascript:void()" onclick="ubah_dispo_unit('<?= encode(urlencode($val['unitId'])) ?>','<?= $key ?>')">Ubah disposisi unit</a>
                              <a class="dropdown-item" href="javascript:void()" onclick="hapus_dispo_unit('<?= encode(urlencode($val['unitId'])) ?>','<?= $key ?>')">Hapus</a>
                            </div>
                          </div>
                        </td>
                        <td id="view_unit_<?= $key; ?>"><?= $val['unit'] ?></td>
                        <td id="view_instruksi_<?= $key; ?>"><?= $val['instruksi'] ?></td>
                        <td id="view_catatan_<?= $key; ?>"><?= $val['catatan'] ?></td>
                        <td>
                          <span class="badge badge-sm rounded badge-<?= (($val['tgl_baca'] == '') ? 'warning' : 'success') ?>"><?= $val['isbaca']; ?></span>
                          <?= (($val['tgl_baca'] == '') ? '' : '<br><small class="text-muted">' . IndonesianDate($val['tgl_baca']) . '</small>') ?>

                        </td>
                      </tr>

                  <?php }
                  } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <input type="hidden" name="id" value="<?= encode(urlencode($id)) ?>">
    <div class="<?= (!isset($tipe_update) ? 'col-md-6 col-12' : (($tipe_update == 'staff') ? 'col-12' : 'd-none')) ?>">
      <div class="card p-3" style="min-height: 100%;">
        <h5 class="<?= (!isset($tipe_update) ? '' : (($tipe_update == 'staff') ? 'd-none' : '')) ?>"><u>Kepada Staff</u></h5>

        <div class="row">
          <div class="col-12">
            <div class="">
              <button type="button" class="btn btn-xs btn-round btn-warning mb-3 float-right" href="javascript:void()" onclick="add_staff('<?= encode(urlencode($id)) ?>')">+ Staff Penerima</button>
            </div>
            <input type="hidden" id="key_staff" value="<?= (isset($detail_staff) ? count($detail_staff) : 0) ?>">
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <table class="table table-sm table-bordered table-striped" width="100%" id="table-staff">
              <thead>
                <tr>
                  <td width="5%" class="fw-500">Aksi</td>
                  <td class="fw-500">Staff</td>
                  <td width="15%%" class="fw-500">Sifat</td>
                  <td class="fw-500">Catatan</td>
                  <?php if (isset($detail_dispo)) { ?>
                    <td width="15%" class="fw-500">Dibaca</td>
                  <?php } ?>
                </tr>
              </thead>
              <tbody id="staff_area">
                <tr id="kosong_staff_area">
                  <?php if (isset($detail_staff)) {
                    if ($detail_staff == NULL) { ?>
                      <td colspan="4" class="text-center">Belum ada staff penerima disposisi.</td>
                    <?php }
                  } else { ?>
                    <td colspan="4" class="text-center">Belum ada staff penerima disposisi.</td>
                  <?php } ?>
                </tr>

                <?php if (isset($detail_staff)) {
                  foreach ($detail_staff as $key => $val) { ?>
                    <tr id="staff_<?= $key ?>">
                      <td class="text-center">
                        <div class="btn-group btn-group-sm">
                          <button class="btn btn-sm dropdown-toggle bg-warning py-0 px-1" data-toggle="dropdown">Aksi</button>
                          <div class="dropdown-menu dropdown-menu-left">
                            <a class="dropdown-item" href="javascript:void()" onclick="ubah_dispo_staff('<?= encode(urlencode($val['id'])) ?>','<?= $key ?>')">Ubah disposisi staff</a>
                            <a class="dropdown-item" href="javascript:void()" onclick="hapus_dispo_staff('<?= encode(urlencode($val['id'])) ?>','<?= $key ?>')">Hapus</a>
                          </div>
                        </div>
                      </td>
                      <td id="view_staff_<?= $key; ?>"><?= $val['staff'] ?></td>
                      <td id="view_sifat_<?= $key; ?>"><?= $val['sifat_nama'] ?></td>
                      <td id="view_catatan_staff_<?= $key; ?>"><?= $val['catatan'] ?></td>
                      <td>
                        <span class="badge badge-sm rounded badge-<?= (($val['tgl_baca'] == '') ? 'warning' : 'success') ?>"><?= $val['isbaca']; ?></span>
                        <?= (($val['tgl_baca'] == '') ? '' : '<br><small class="text-muted">' . IndonesianDate($val['tgl_baca']) . '</small>') ?>
                      </td>
                    </tr>

                <?php }
                } ?>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>


<script type="text/javascript">
  $(document).ready(function() {
    $(".close").addClass('d-none');

    $(".modal-footer").append(`
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
      <button class="btn btn-round btn-custom" type="button" onclick="set_disposisi();">Simpan</button>
    `);
    $(".modal-body").addClass('bg-lighter');
  });

  var count = <?= ((isset($detail_dispo)) ? (($detail_dispo == NULL) ? 0 : count($detail_dispo) + 1) : 0) ?>

  function add_penerima() {
    var id = count++;
    var unitId = $("#unit").find(":selected").val();
    var unit = $("#unit").find(":selected").text();
    var instruksiId = $("#instruksi").find(":selected").val();
    var instruksi = $("#instruksi").find(":selected").text();
    var catatan = $("#catatan_unit").val();

    $('.has-error').empty();
    $('.has-error').removeClass('has-error');
    $('.has-invalid').removeClass('is-invalid .has-invalid');

    if (unitId == '') {
      $("#unit").addClass('is-invalid');
      $("#unit").addClass('has-invalid');
      $("#error_unit").addClass('has-error');
      $("#error_unit").text('Unit harus dipilih!');
      $('#unit').selectpicker('refresh');
      return false;
    }

    if (instruksiId == '') {
      $("#instruksi").addClass('is-invalid');
      $("#instruksi").addClass('has-invalid');
      $("#error_instruksi").addClass('has-error');
      $("#error_instruksi").text('instruksi harus dipilih!');
      return false;
    }

    if (catatan == '') {
      $("#catatan_unit").addClass('is-invalid');
      $("#catatan_unit").addClass('has-invalid');
      $("#error_catatan_unit").addClass('has-error');
      $("#error_catatan_unit").text('catatan harus dipilih!');
      return false;
    }

    $("#kosong_area").empty();

    $("#unit_area").append(`
      <tr id="unit_` + id + `">
        <td class="text-center">
          <button type="button" class="btn btn-xs" onclick="hapus_unit('` + id + `')"><i class="ti-trash text-danger"></i></button>
        </td>
        <td>` + unit + `</td>
        <td>` + instruksi + `</td>
        <td>` + catatan + `</td>
        <input type="hidden" name="key[` + id + `]" value="` + id + `"> 
        <input type="hidden" name="dispo_unit[` + id + `]" value="` + unitId + `"> 
        <input type="hidden" name="dispo_instruksi[` + id + `]" value="` + instruksiId + `">
        <input type="hidden" name="dispo_catatan[` + id + `]" value="` + catatan + `">
      </tr>
    `);

  }

  function hapus_unit(id) {
    $("#unit_" + id).empty();
  }

  var Modal_del = function(opt) {
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
      html: opt.html
    });
  }

  function ubah_dispo_unit(id, key) {
    var params = "<?= '&id=' . encode(urlencode($id)) . '&unitId=' ?>" + id + '&key=' + key;
    module = "<?= site_url($module . '/disposisi?tipe=view&mode=dispo_unit') ?>" + params;
    Modal_del({
      url: module,
      title: 'Ubah Disposisi Unit',
      size: 'md',
      callback: function(modal) {}
    })
  }

  function set_disposisi() {
    var formdata = new FormData($('#input_form')[0]);
    var input = $("form#input_form input[type=text]");
    var modal = $(".modal.show").attr('id');
    $(input).removeClass('is-invalid');
    $('.has-error').empty();
    $('.has-error').removeClass('has-error');
    $('.has-invalid').removeClass('is-invalid .has-invalid');

    $.ajax({
      url: "<?= site_url($module . '/disposisi?tipe=proses&mode=') . $mode ?>",
      type: "POST",
      data: formdata,
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function(data) {
        if (data.error == 'null') {
          $('#' + modal + '').modal('hide');
          app.toast(data.text);
          $('#datatables_ajax').DataTable().ajax.reload(false, null);

        } else {
          $.each(data.error, function(i, log) {
            $('[id="' + i + '"]').addClass('has-invalid');
            $('[id="' + i + '"]').addClass('is-invalid');
            $('#error_' + i).text(log);
            $('#error_' + i).addClass('has-error');
            $('.selectpicker').selectpicker('refresh');
          });
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $('#' + modal + '').modal('hide');
        $('#datatables_ajax').DataTable().ajax.reload(false, null);
        app.toast('Gagal memproses! Silahkan hubungi administrator.');
      }
    });
  }

  function hapus_dispo_unit(id, key) {
    var html = ` <input type="hidden" id="id_dispo_unit" value="` + id + `">
      <input type="hidden" id="delete_key" value="` + key + `">
      <div class="modal-body">
        <div class="container">
          Apakah anda yakin ingin menghapus penerima disposisi ini?
        </div>
      </div>
      <footer class="mx-4 my-4 text-right">
        <button type="button" class="btn btn-bold btn-pure btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" onclick="del_unit_action();" class="btn btn-bold btn-pure btn-danger">Hapus</button>
      </footer>`;

    Modal_del({
      title: 'Hapus Penerima Disposisi',
      size: 'md',
      html: html,
      callback: function(modal) {}
    });
  }

  function del_unit_action() {
    var id = $('#id_dispo_unit').val();
    var key = $("#delete_key").val();
    var modal = $(".modal.show:last").attr('id');
    var params = '?tipe=delete&id=' + id + '&dispoId=' + '<?= encode(urlencode($id)) ?>';
    $.ajax({
      url: "<?= site_url($module . '/disposisi') ?>" + params,
      dataType: 'JSON',
      type: 'POST',
      data: {
        id: id,
      },
      success: function(data) {
        if (data.error == 'null') {
          hapus_unit(key);
          // $('#modal_delete').modal('hide');
          $('#' + modal + '').modal('hide');
          app.toast(data.text);
          $('#datatables_ajax').DataTable().ajax.reload(false, null);
        } else {
          // $('#modal_delete').modal('hide');
          $('#' + modal + '').modal('hide');
          app.toast(data.text);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        // $('#modal_delete').modal('hide');
        $('#' + modal + '').modal('hide');
        app.toast('Gagal memproses! Silahkan hubungi administrator.');
      }
    });
  }

  var cnt_staff = 0;

  function add_penerima_staff() {
    var id = cnt_staff++;
    var sifatId = $("#sifat_staff").find(":selected").val();
    var sifat = $("#sifat_staff").find(":selected").text();
    var catatan = $("#catatan_staff").val();
    var staff = 'cuknan';

    $('.has-error-staff').empty();
    $('.has-error-staff').removeClass('has-error-staff');
    $('.has-invalid-staff').removeClass('is-invalid .has-invalid-staff');

    if (sifatId == '') {
      $("#sifat_staff").addClass('is-invalid');
      $("#sifat_staff").addClass('has-invalid-staff');
      $("#error_sifat_staff").addClass('has-error-staff');
      $("#error_sifat_staff").text('sifat urgensi harus dipilih!');
      return false;
    }

    if (catatan == '') {
      $("#catatan_staff").addClass('is-invalid');
      $("#catatan_staff").addClass('has-invalid-staff');
      $("#error_catatan_staff").addClass('has-error-staff');
      $("#error_catatan_staff").text('catatan harus dipilih!');
      return false;
    }

    $("#kosong_staff_area").empty();

    $("#staff_area").append(`
      <tr id="unit_` + id + `">
        <td class="text-center">
          <button type="button" class="btn btn-xs" onclick="hapus_staff('` + id + `')"><i class="ti-trash text-danger"></i></button>
        </td>
        <td>` + staff + `</td>
        <td>` + sifat + `</td>
        <td>` + catatan + `</td>
        <input type="hidden" name="key_staff[` + id + `]" value="` + id + `"> 
        <input type="hidden" name="dispo_staff[` + id + `]" value="` + staff + `"> 
        <input type="hidden" name="dispo_sifat_staff[` + id + `]" value="` + sifatId + `">
        <input type="hidden" name="dispo_catatan_staff[` + id + `]" value="` + catatan + `">
      </tr>
    `);

  }

  function add_staff(id) {
    var key = $("#key_staff").val();
    var params = "<?= '&id=' . encode(urlencode($id)) ?>" + '&key=' + key;
    module = "<?= site_url($module . '/disposisi?tipe=view_staff') ?>" + params;
    Modal_del({
      url: module,
      title: 'Tambah Staff Penerima',
      size: 'md',
      callback: function(modal) {}
    })
  }

  function ubah_dispo_staff(id) {
    var key = $("#key_staff").val();
    var params = '&id=' + id + '&key=' + key;
    module = "<?= site_url($module . '/disposisi?tipe=view_staff&mode=update') ?>" + params;
    Modal_del({
      url: module,
      title: 'Ubah Staff Penerima',
      size: 'md',
      callback: function(modal) {}
    })
  }

  function add_unit(id) {
    var sifatId = $("#sifat").find(":selected").val();
    var sifat = $("#sifat").find(":selected").text();
    var catatan = $("#catatan").val();

    $('.has-error').empty();
    $('.has-error').removeClass('has-error');
    $('.has-invalid').removeClass('is-invalid .has-invalid');

    if (sifatId == '') {
      $("#sifat").addClass('is-invalid');
      $("#sifat").addClass('has-invalid');
      $("#error_sifat").addClass('has-error');
      $("#error_sifat").text('sifat harus dipilih!');
      $('#sifat').selectpicker('refresh');
      return false;
    }

    if (catatan == '') {
      $("#catatan").addClass('is-invalid');
      $("#catatan").addClass('has-invalid');
      $("#error_catatan").addClass('has-error');
      $("#error_catatan").text('catatan harus diisi!');
      return false;
    }

    var key = $("#key_staff").val();
    var params = "<?= '&id=' . encode(urlencode($id)) ?>" + '&key=' + key;
    module = "<?= site_url($module . '/disposisi?tipe=view_unit&mode=add') ?>";
    Modal_del({
      url: module,
      title: 'Tambah Unit Penerima',
      size: 'md',
      callback: function(modal) {}
    })
  }
</script>