<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
  .modal-body {
    max-height: calc(100vh - 143px);
    overflow-y: auto;
  }
</style>

<form id="form-arsip" class="form-horizontal form-type-round" method="POST" action="" enctype="multipart/form-data">

  <div class="row">
    <div class="form-group col-12">
      <label class="text-dark require">Berkas</label>
      <select class="form-control selectpicker" data-provide="selectpicker" data-live-search="false" data-title="Pilih Berkas" name="berkas" id="berkas">
        <?php foreach ($berkas as $val) { ?>
          <option value="<?= $val['id'] ?>" <?= (($detail == '') ? '' : (($detail['berkas'] == $val['id']) ? 'selected="select"' : '')); ?>><?= $val['nama'] ?></option>
        <?php } ?>
      </select>
      <small class="text-danger" id="error_berkas"></small>
    </div>
  </div>
  <div class="row">
    <div class="form-group col-12">
      <label class="text-dark require">Jenis Eksemplar</label>
      <select class="form-control selectpicker" data-provide="selectpicker" data-live-search="false" data-title="Pilih Jenis Eksemplar" name="eksemplar" id="eksemplar">
        <?php foreach ($eksemplar as $val) { ?>
          <option value="<?= $val['id'] ?>" <?= (($detail['is_arsip'] == '') ? '' : (($detail['eksemplar'] == $val['id']) ? 'selected="select"' : '')); ?>><?= $val['nama'] ?></option>
        <?php } ?>
      </select>
      <small class="text-danger" id="error_eksemplar"></small>
    </div>
  </div>
  <input type="hidden" name="id" id="id" value="<?= encode(urlencode($detail['is_arsip'])) ?>">
  <input type="hidden" name="suratId" id="suratId" value="<?= encode(urlencode($detail['id'])) ?>">

  <input type="hidden" name="arsip_mode" id="arsip-mode" value="<?= $mode; ?>">

  <footer class="mx-4 my-4 text-right">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
    <button class="btn btn-round btn-custom" type="button" onclick="proses();">Simpan</button>
  </footer>
</form>

<script type="text/javascript">
  function proses() {
    var formdata = new FormData($('#form-arsip')[0]);
    var input = $("form#form-arsip input[type=text]");
    var modal = $(".modal.show:last").attr('id');
    var id = $('#id').val();

    $(input).removeClass('is-invalid');
    $('.has-error').empty();
    $('.has-error').removeClass('has-error');
    $('.has-invalid').removeClass('is-invalid .has-invalid');
    $.ajax({
      url: "<?= site_url($module . '/arsip?tipe=proses&mode=') . $mode ?>",
      type: "POST",
      data: formdata,
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function(data) {
        if (data.error == 'null') {

          $('#' + modal + '').modal('hide');
          app.toast(data.text);
          render_arsip('<?= encode(urlencode($detail['id'])) ?>');
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

  function render_arsip(id) {
    $.ajax({
      url: "<?= site_url($module . '/arsip?tipe=ajax_arsip&id=') ?>" + id,
      type: "GET",
      dataType: "JSON",
      success: function(obj) {
        $("#btn-arsip").addClass('d-none');
        $("#info-arsip").empty();
        $("#info-arsip").removeClass('d-none');
        if (obj != null) {
          data = Object.keys(obj).map(function(key) {
            return obj[key];
          });
          for (var i = 0; i < data.length; i++) {
            $("#info-arsip").append(`
              <p class="text-dark fw-600">Surat telah diarsipkan.</p>
              <div class="form-group row">
                <label class="col-4 col-lg-2 col-form-label" for="input-2">Berkas</label>
                <div class="col-8 col-lg-10">
                  <p class="col-form-label" id="arsip_berkas">: ` + data[i].berkas_nama + `</p>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-4 col-lg-2 col-form-label" for="input-2">Jenis Eksemplar</label>
                <div class="col-8 col-lg-10">
                  <p class="col-form-label" id="arsip_eksemplar">: ` + data[i].eksemplar_nama + `</p>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <a id="ubah_arsip" class="btn btn-sm btn-round btn-custom mb-3" href="javascript:void()" onclick="ubah_arsip('` + data[i].arsipId + `','` + data[i].suratId + `')">Ubah Arsip</a>
                </div>
              </div>
            `);
          }
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {}
    });
  }
</script>