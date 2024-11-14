<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
  .modal-body {
    max-height: calc(100vh - 143px);
    overflow-y: auto;
  }
</style>

<form id="form_add" class="form-horizontal form-type-round" method="POST" action="" enctype="multipart/form-data">

  <div class="row">
    <div class="form-group col-lg-6">
      <label class="text-dark require">Jenis Surat</label>
      <select class="form-control selectpicker" name="jenis" id="jenis" data-provide="selectpicker" data-live-search="true" data-title="Pilih Jenis Surat" data-header="Jenis Surat" onchange="opt_jenis()">
        <option value="">Pilih Jenis Surat</option>
        <?php foreach ($jenis as $val) { ?>
          <option value="<?= $val['id'] ?>"><?= $val['nama'] ?></option>
        <?php } ?>
      </select>
      <small class="text-danger" id="error_jenis"></small>
    </div>

    <div class="form-group col-lg-6">
      <label class="text-dark require">Sifat Urgensi</label>
      <select class="form-control" name="sifat" id="sifat">
        <option value="">Pilih Sifat Urgensi</option>
        <?php foreach ($sifat as $val) { ?>
          <option value="<?= $val['id'] ?>"><?= $val['nama'] ?></option>
        <?php } ?>
      </select>
      <small class="text-danger" id="error_sifat"></small>
    </div>
  </div>

  <div class="row">
    <div class="form-group col-lg-6">
      <label class="text-dark require">Tanggal</label>
      <div class="input-group">
        <input type="text" class="form-control" value="<?= date("d-m-Y") ?>" name="tanggal" id="tanggal">
        <span class="input-group-addon">
          <i class="fa fa-calendar"></i>
        </span>
      </div>
      <small class="text-danger" id="error_tanggal"></small>
    </div>
    <div class="form-group col-lg-6">
      <label class="text-dark require">Klasifikasi</label>
      <select class="form-control selectpicker" data-provide="selectpicker" data-live-search="true" data-title="Pilih Klasifikasi" data-header="Klasifikasi" name="klasifikasi" id="klasifikasi">
        <option value="">Pilih Klasifikasi</option>
        <?php foreach ($klasifikasi as $val) { ?>
          <option value="<?= $val['id'] ?>"><?= $val['nama'] ?></option>
        <?php } ?>
      </select>
      <small class="text-danger" id="error_klasifikasi"></small>
    </div>
  </div>

  <div class="row">
    <div class="form-group col-lg-6">
      <label class="text-dark require">Nomor surat</label>
      <input class="form-control" type="text" name="nomor" id="nomor">
      <span class="invalid-feedback" id="error_nomor"></span>
    </div>
    <div class="form-group col-lg-6">
      <label class="text-dark require">Perihal</label>
      <input class="form-control" type="text" name="hal" id="hal">
      <span class="invalid-feedback" id="error_hal"></span>
    </div>
  </div>

  <div class="row">
    <div class="form-group col-lg-12">
      <label class="text-dark require">Asal Surat</label>
      <input class="form-control" type="text" name="asal" id="asal">
      <span class="invalid-feedback" id="error_asal"></span>
    </div>
  </div>

  <div class="row">
    <div class="form-group col-lg-12">
      <label class="text-dark require">Isi Ringkas</label>
      <textarea class="form-control" rows="5" name="isi" id="isi"></textarea>
      <span class="invalid-feedback" id="error_isi"></span>
    </div>
  </div>

  <div class="row" id="kolom-area">

  </div>

  <div class="row">
    <div class="form-group file-group col-lg-6">
      <label class="text-dark">Upload file</label>
      <div class="input-group">
        <input type="text" class="form-control file-value file-browser" placeholder="Choose file..." id="file" readonly>
        <input type="file" name="file" multiple>
        <span class="input-group-addon">
          <i class="fa fa-upload"></i>
        </span>
      </div>
      <small class="text-danger" id="error_file"></small>

    </div>
  </div>

  <div class="row">
    <label class="switch switch-warning switch-lg ml-3 mb-3">
      <input type="checkbox" name="check_arsip" id="check_arsip">
      <span class="switch-indicator"></span>
      <span class="switch-description">Arsipkan?</span>
    </label>
  </div>

  <div class="row d-none" id="arsip-form">
    <div class="form-group col-lg-6">
      <label class="text-dark require">Berkas</label>
      <select class="form-control selectpicker" data-provide="selectpicker" data-live-search="false" data-title="Pilih Berkas" name="berkas" id="berkas">
        <?php foreach ($berkas as $val) { ?>
          <option value="<?= $val['id'] ?>"><?= $val['nama'] ?></option>
        <?php } ?>
      </select>
      <small class="text-danger" id="error_berkas"></small>
    </div>
    <div class="form-group col-lg-6">
      <label class="text-dark require">Jenis Eksemplar</label>
      <select class="form-control selectpicker" data-provide="selectpicker" data-live-search="false" data-title="Pilih Jenis Eksemplar" name="eksemplar" id="eksemplar">
        <?php foreach ($eksemplar as $val) { ?>
          <option value="<?= $val['id'] ?>"><?= $val['nama'] ?></option>
        <?php } ?>
      </select>
      <small class="text-danger" id="error_eksemplar"></small>
    </div>
  </div>
  </div>

  <footer class="mx-4 my-4 text-right">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
    <button class="btn btn-round btn-custom" type="button" onclick="add_action();">Simpan</button>
  </footer>
</form>

<script type="text/javascript">
  $(document).ready(function() {
    $('#tanggal').datepicker({
      autoclose: true,
      clearBtn: true,
      todayHighlight: true,
      format: 'dd-mm-yyyy',
      orientation: "bottom auto"
    });

    var check = $("#check_arsip");
    check.click(function() {
      if (check.prop("checked")) {
        $("#arsip-form").removeClass('d-none');
      } else {
        $("#arsip-form").addClass('d-none');
      }
    });
  });

  function add_action() {
    var formdata = new FormData($('#form_add')[0]);
    var input = $("form#form_add input[type=text]");
    var modal = $(".modal.show").attr('id');
    $(input).removeClass('is-invalid');
    $('.has-error').empty();
    $('.has-error').removeClass('has-error');
    $('.has-invalid').removeClass('is-invalid .has-invalid');
    $.ajax({
      url: "<?= site_url($module . '/add?tipe=proses') ?>",
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

  function opt_jenis() {
    var jenis = $("#jenis").find(':selected').val();
    $("#input-surat").addClass('d-none');
    $("#info-set-kolom").addClass('d-none');
    $('.has-error').empty();
    $('.has-error').removeClass('has-error');
    $('.has-invalid').removeClass('is-invalid .has-invalid');
    $.ajax({
      url: "<?= site_url($module . '/draft?tipe=ajax_kolom&id=') ?>" + jenis,
      type: "GET",
      dataType: "JSON",
      success: function(obj) {
        $("#kolom-area").empty();
        if (obj != null) {
          data = Object.keys(obj).map(function(key) {
            return obj[key];
          });

          for (var i = 0; i < data.length; i++) {
            if (data[i].tipe == 'text') {
              $("#kolom-area").append(`
                <div class="form-group col-lg-12">
                  <label class="text-dark require">` + data[i].nama + `</label>
                  <textarea class="form-control" rows="5" name="` + data[i].id + `" id="` + data[i].id + `"></textarea>
                  <span class="invalid-feedback" id="error_` + data[i].id + `"></span>
                </div>
              `);
            } else {
              $("#kolom-area").append(`
                <div class="form-group col-lg-6">
                  <label class="text-dark ` + ((data[i].id != 1) ? 'require' : '') + `">` + data[i].nama + `</label>
                  <input class="form-control ` + ((data[i].tipe == 'date') ? ' tanggal' : '') + `" type="text" name="` + data[i].id + `" id="` + data[i].id + `">
                  <span class="invalid-feedback" id="error_` + data[i].id + `"></span>
                </div>
              `);
            }
          }
          $('.tanggal').datepicker({
            autoclose: true,
            clearBtn: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy',
            orientation: "bottom auto"
          });
          $("#input-surat").removeClass('d-none');
        } else {
          $("#info-set-kolom").removeClass('d-none');
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {}
    });
  }
</script>