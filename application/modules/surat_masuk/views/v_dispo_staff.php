<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
  .modal-body {
    max-height: calc(100vh - 143px);
    overflow-y: auto;
  }
</style>

<form id="form_staff" class="form-horizontal form-type-round" method="POST" action="" enctype="multipart/form-data">
  <div class="form-group">
    <label class="text-dark require">Staff</label>
    <select class="form-control selectpicker" name="" id="staff_staff" data-provide="selectpicker" data-live-search="true" data-title="Pilih Staff Tujuan" data-header="Daftar Staff Unit" <?= (!isset($detail) ? '' : 'disabled'); ?>>
      <?php foreach ($staff as $val) { ?>
        <option value="<?= $val['id'] ?>" <?= (!isset($detail) ? '' : (($detail['staffId'] == $val['id']) ? 'selected="select"' : '')); ?>><?= $val['nama'] ?></option>
      <?php } ?>
    </select>
    <small class="text-danger" id="error_staff_staff"></small>
  </div>

  <div class="form-group">
    <label class="text-dark require">Sifat Urgensi</label>
    <select class="form-control" name="sifat_staff" id="sifat_staff">
      <option value="">Pilih Sifat Urgensi</option>
      <?php foreach ($sifat as $val) { ?>
        <option value="<?= $val['id'] ?>" <?= (!isset($detail) ? '' : (($detail['sifatId'] == $val['id']) ? 'selected="select"' : '')); ?>><?= $val['nama'] ?></option>
      <?php } ?>
    </select>
    <small class="text-danger" id="error_sifat_staff"></small>
  </div>

  <div class="form-group">
    <label class="text-dark require">Catatan</label>
    <textarea class="form-control" rows="5" name="catatan_staff" id="catatan_staff"><?= ((!isset($detail)) ? '' : $detail['catatan']); ?></textarea>
    <span class="invalid-feedback" id="error_catatan_staff"></span>
  </div>
  <input type="hidden" name="id" value="<?= encode(urlencode($id)) ?>">

  <footer class="mx-4 my-4 text-right">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
    <?php if ($mode == 'update') { ?>
      <button class="btn btn-round btn-custom" type="button" onclick="ubah_staff_penerima();">Ubah</button>
    <?php } else { ?>
      <button class="btn btn-round btn-custom" type="button" onclick="staff_penerima();">Tambahkan</button>
    <?php } ?>
  </footer>
</form>

<script type="text/javascript">
  function staff_penerima() {
    var count = $("#key_staff").val();
    var id = +count + +1;
    var modal = $(".modal.show:last").attr('id');
    // var id = <?= $key + 1 ?>;
    var sifatId = $("#sifat_staff").find(":selected").val();
    var sifat = $("#sifat_staff").find(":selected").text();
    var staffId = $("#staff_staff").find(":selected").val();
    var staff = $("#staff_staff").find(":selected").text();
    var catatan = $("#catatan_staff").val();

    $('.has-error-staff').empty();
    $('.has-error-staff').removeClass('has-error-staff');
    $('.has-invalid-staff').removeClass('is-invalid .has-invalid-staff');

    if (staffId == '') {
      $("#staff_staff").addClass('is-invalid');
      $("#staff_staff").addClass('has-invalid-staff');
      $("#error_staff_staff").addClass('has-error-staff');
      $("#error_staff_staff").text('staff harus dipilih!');
      $('.selectpicker').selectpicker('refresh');
      return false;
    }

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
      <tr id="staff_` + id + `">
        <td class="text-center">
          <button type="button" class="btn btn-xs" onclick="hapus_staff('` + id + `')"><i class="ti-trash text-danger"></i></button>
        </td>
        <td>` + staff + `</td>
        <td>` + sifat + `</td>
        <td>` + catatan + `</td>
        <input type="hidden" name="key_staff[` + id + `]" value="` + id + `"> 
        <input type="hidden" name="dispo_staff[` + id + `]" value="` + staffId + `"> 
        <input type="hidden" name="dispo_sifat_staff[` + id + `]" value="` + sifatId + `">
        <input type="hidden" name="dispo_catatan_staff[` + id + `]" value="` + catatan + `">
      </tr>
    `);
    $("#key_staff").val(id);
    $('#' + modal + '').modal('hide');
  }

  function ubah_staff_penerima() {

    var formdata = new FormData($('#form_staff')[0]);
    var input = $("form#form_staff input[type=text]");
    var modal = $(".modal.show:last").attr('id');
    var key = "<?= $key; ?>"
    var catatan = $('#catatan_staff').val();
    var sifat = $("#sifat_staff").find(":selected").text();

    $(input).removeClass('is-invalid');
    $('.has-error').empty();
    $('.has-error').removeClass('has-error');
    $('.has-invalid').removeClass('is-invalid .has-invalid');
    $.ajax({
      url: "<?= site_url($module . '/disposisi?tipe=proses&mode=dispo_staff') ?>",
      type: "POST",
      data: formdata,
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function(data) {
        if (data.error == 'null') {
          $("#view_sifat_" + key).text(sifat);
          $("#view_catatan_staff_" + key).text(catatan);

          $('#' + modal + '').modal('hide');
          app.toast(data.text);
          $('#datatables_ajax').DataTable().ajax.reload(false, null);

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
</script>