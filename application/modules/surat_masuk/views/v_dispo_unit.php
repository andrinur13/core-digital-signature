<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
  .modal-body {
    max-height: calc(100vh - 143px);
    overflow-y: auto;
  }
</style>

<form id="form_unit" class="form-horizontal form-type-round" method="POST" action="" enctype="multipart/form-data">
  <div class="form-group">
    <label class="text-dark">Unit</label>
    <select class="form-control selectpicker" name="unit" id="unit" data-provide="selectpicker" data-live-search="true" data-title="Pilih Unit Tujuan" data-header="Daftar Unit" <?= (!isset($detail) ? '' : 'disabled'); ?>>
      <?php foreach ($unit as $val) { ?>
        <option value="<?= $val['id'] ?>" <?= (!isset($detail) ? '' : (($detail['unitId'] == $val['id']) ? 'selected="select"' : '')); ?>><?= $val['nama'] ?></option>
      <?php } ?>
    </select>
    <small class="text-danger" id="error_unit"></small>
  </div>

  <div class="form-group">
    <label class="text-dark require">Instruksi</label>
    <select class="form-control" name="instruksi" id="instruksi_units">
      <option value="">Pilih Instruksi</option>
      <?php foreach ($instruksi as $val) { ?>
        <option value="<?= $val['id'] ?>" <?= (!isset($detail) ? '' : (($detail['instruksiId'] == $val['id']) ? 'selected="select"' : '')); ?>><?= $val['nama'] ?></option>
      <?php } ?>
    </select>
    <small class="text-danger" id="error_instruksi_units"></small>
  </div>

  <div class="form-group">
    <label class="text-dark require">Catatan</label>
    <textarea class="form-control" rows="5" name="catatan_units" id="catatan_units"><?= (!isset($detail) ? '' : $detail['catatan']); ?></textarea>
    <span class="invalid-feedback" id="error_catatan_units"></span>
  </div>

  <?php if (isset($detail)) { ?>
    <input type="hidden" name="id" value="<?= encode(urlencode($id)) ?>">
    <input type="hidden" name="unitId" value="<?= encode(urlencode($detail['unitId'])) ?>">
  <?php } ?>
  </div>

  <footer class="mx-4 my-4 text-right">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
    <?php if (isset($detail)) { ?>
      <button class="btn btn-round btn-custom" type="button" onclick="proses();">Ubah</button>
    <?php } else { ?>
      <button class="btn btn-round btn-custom" type="button" onclick="unit_penerima();">Tambahkan</button>
    <?php } ?>
  </footer>
</form>

<script type="text/javascript">
  function proses() {
    var formdata = new FormData($('#form_unit')[0]);
    var input = $("form#form_unit input[type=text]");
    var modal = $(".modal.show:last").attr('id');
    var key = "<?= $key; ?>"
    var catatan = $('#catatan_units').val();
    var instruksi = $("#instruksi_units").find(":selected").text();

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
          $("#view_instruksi_" + key).text(instruksi);
          $("#view_catatan_" + key).text(catatan);

          $('#' + modal + '').modal('hide');
          app.toast(data.text);
          // $('#datatables_ajax').DataTable().ajax.reload(false, null);

        } else {
          $.each(data.error, function(i, log) {
            $('[id="' + i + '_units"]').addClass('has-invalid');
            $('[id="' + i + '_units"]').addClass('is-invalid');
            $('#error_' + i + '_units').text(log);
            $('#error_' + i + '_units').addClass('has-error');
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

  function unit_penerima() {
    var count = $("#key_unit").val();
    var id = +count + +1;
    var unitId = $("#unit").find(":selected").val();
    var unit = $("#unit").find(":selected").text();
    var instruksiId = $("#instruksi_units").find(":selected").val();
    var instruksi = $("#instruksi_units").find(":selected").text();
    var catatan = $("#catatan_units").val();
    var modal = $(".modal.show:last").attr('id');

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
      $("#instruksi_units").addClass('is-invalid');
      $("#instruksi_units").addClass('has-invalid');
      $("#error_instruksi_units").addClass('has-error');
      $("#error_instruksi_units").text('instruksi harus dipilih!');
      return false;
    }

    if (catatan == '') {
      $("#catatan_units").addClass('is-invalid');
      $("#catatan_units").addClass('has-invalid');
      $("#error_catatan_units").addClass('has-error');
      $("#error_catatan_units").text('catatan harus dipilih!');
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
    $("#key_unit").val(id);
    $('#' + modal + '').modal('hide');
  }
</script>