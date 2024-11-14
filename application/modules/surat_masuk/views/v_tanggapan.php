<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
  .modal-body {
    max-height: calc(100vh - 143px);
    overflow-y: auto;
  }
</style>

<form id="form_tanggapan" class="form-horizontal form-type-round" method="POST" action="" enctype="multipart/form-data">
  <div class="form-group d-none">
    <label class="text-dark require">Pejabat/Staff</label>
    <select class="form-control selectpicker" name="user" id="user" data-provide="selectpicker" data-live-search="true" data-title="Pilih Pejabat / Staff" data-header="Pejabat / Staff">
      <?php foreach ($user as $val) { ?>
        <option value="<?= $val['id'] ?>" <?= (!isset($detail) ? '' : (($detail['UserId'] == $val['id']) ? 'selected="select"' : '')); ?>><?= $val['nama'] ?></option>
      <?php } ?>
    </select>
    <small class="text-danger" id="error_user"></small>
  </div>

  <div class="form-group d-none">
    <label class="text-dark require">Jenis Tindakan</label>
    <select class="form-control selectpicker" name="tindakan" id="tindakan" data-provide="selectpicker" data-live-search="true" data-title="Pilih Jenis Tindakan" data-header="Jenis Tindakan">
      <?php foreach ($tindakan as $val) { ?>
        <option value="<?= $val['id'] ?>" <?= (!isset($detail) ? '' : (($detail['tindakanId'] == $val['id']) ? 'selected="select"' : '')); ?>><?= $val['nama'] ?></option>
      <?php } ?>
    </select>
    <small class="text-danger" id="error_tindakan"></small>
  </div>

  <div class="form-group">
    <label class="text-dark require">Isi Balasan Surat</label>
    <textarea class="form-control" rows="5" name="catatan" id="catatan"><?= (!isset($detail) ? '' : $detail['catatan']); ?></textarea>
    <span class="invalid-feedback" id="error_catatan"></span>
  </div>
  <input type="hidden" name="id" value="<?= (!isset($detail) ? encode(urlencode($id)) : $tinId) ?>">
  </div>

  <footer class="mx-4 my-4 text-right">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
    <button class="btn btn-round btn-custom" type="button" onclick="proses();">Simpan</button>
  </footer>
</form>

<script type="text/javascript">
  function proses() {
    var formdata = new FormData($('#form_tanggapan')[0]);
    var input = $("form#form_tanggapan input[type=text]");
    var modal = $(".modal.show:last").attr('id');
    var id = "<?= ($mode == 'add') ? encode(urlencode($id)) : encode(urlencode($detail['suratId'])) ?>";

    $(input).removeClass('is-invalid');
    $('.has-error').empty();
    $('.has-error').removeClass('has-error');
    $('.has-invalid').removeClass('is-invalid .has-invalid');
    $.ajax({
      url: "<?= site_url($module . '/tanggapan?tipe=proses&mode=') . $mode ?>",
      type: "POST",
      data: formdata,
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function(data) {
        if (data.error == 'null') {
          render_tanggapan(id);
          render_status();
          $("#btn-tanggapan").addClass('d-none');
          $('#' + modal + '').modal('hide');
          app.toast(data.text);

        } else {
          $.each(data.error, function(i, log) {
            $('[id="' + i + '"]').addClass('has-invalid');
            $('[id="' + i + '"]').addClass('is-invalid');
            $('#error_' + i + '').text(log);
            $('#error_' + i + '').addClass('has-error');
          });
          $('.selectpicker').selectpicker('refresh');
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $('#' + modal + '').modal('hide');
        app.toast('Gagal memproses! Silahkan hubungi administrator.');
      }
    });
  }

  function render_tanggapan(id) {
    $.ajax({
      url: "<?= site_url($module . '/tanggapan?tipe=ajax_tanggapan&id=') ?>" + id,
      type: "GET",
      dataType: "JSON",
      success: function(obj) {
        $("#tanggapan-area").empty();
        if (obj != null) {
          data = Object.keys(obj).map(function(key) {
            return obj[key];
          });
          for (var i = 0; i < data.length; i++) {
            $('#tanggapan-area').append(`
              <tr>

              <td>` + data[i].catatan + `</td>
              </tr>
              `);
          }
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {}
    });
  }
  /*
    <td>` + data[i].tindakan + `</td>
    <td>
      <div class="btn-group btn-group-sm">
        <button class="btn dropdown-toggle bg-warning" data-toggle="dropdown">Aksi</button>
        <div class="dropdown-menu dropdown-menu-left">

          <a class="dropdown-item" href="javascript:void()" onclick="ubah_tanggapan('` + data[i].id + `')">Ubah</a>

          <a class="dropdown-item" href="javascript:void()" onclick="hapus_tanggapan('` + data[i].id + `')">Hapus</a>
        </div>
      </div>
    </td>
  */
</script>