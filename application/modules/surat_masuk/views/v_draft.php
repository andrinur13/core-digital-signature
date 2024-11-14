<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
  /* .modal-dialog {
    max-width: 80%;
    max-height: 80%;
  } */

  .modal-body {
    max-height: calc(100vh - 143px);
    overflow-y: auto;
  }
</style>

<div class="accordion mb-3" id="accordion-1">
  <div class="card">
    <h5 class="card-title">
      <a data-toggle="collapse" data-parent="#accordion-1" href="#collapse-1-1">Referensi Surat Masuk</a>
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
        <div class="form-group row mb-0">
          <label class="col-4 col-lg-2 col-form-label" for="input-2">Jenis</label>
          <div class="col-8 col-lg-10">
            <p class="col-form-label">: <?= $detail['jenis_surat'] ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<h5 class="fw-600 mb-3">Form Draft Surat</h5>
<form id="form_draft" class="form-horizontal form-type-round" method="POST" action="" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?= urlencode(encode($detail['id'])) ?>">
  <input type="hidden" name="arsipId" value="<?= urlencode(encode($detail['is_arsip'])) ?>">
  <div class="row">
    <div class="form-group col-lg-12" id="opt_jenis">
      <label class="text-dark require">Jenis Surat</label>
      <select class="form-control selectpicker" name="jenis" id="jenis" data-provide="selectpicker" data-live-search="true" data-title="Pilih Jenis Surat" data-header="Jenis Surat" onchange="opt_jenis()">
        <option value="">Pilih Jenis Surat</option>
        <?php foreach ($jenis as $val) { ?>
          <option value="<?= $val['id'] ?>"><?= $val['nama'] ?></option>
        <?php } ?>
      </select>
      <small class="text-danger" id="error_jenis"></small>
    </div>
  </div>


  <!-- 
    <div class="d-none" id="info-set-kolom">
      <div class="alert alert-danger">Referensi kolom untuk jenis surat ini belum ada. Silahkan dilengkapi dahulu!</div>
    </div> 
  -->

  <div id="input-surat" class="d-none">
    <div class="row">
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
        <label class="text-dark require">Perihal</label>
        <input class="form-control" type="text" name="hal" id="hal">
        <span class="invalid-feedback" id="error_hal"></span>
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
      <div class="form-group col-lg-6">
        <label class="text-dark require">Kategori Surat</label>
        <select class="form-control" name="kategori" id="kategori" onchange="opt_kategori()">
          <option value="">Pilih Kategori</option>
          <option value="internal">Internal</option>
          <option value="eksternal">Eksternal</option>
        </select>
        <span class="invalid-feedback" id="error_kategori"></span>
      </div>

      <div class="form-group col-lg-6 d-none" id="asal_eksternal">
        <label class="text-dark require">Tujuan Surat</label>
        <input class="form-control" type="text" name="tujuan_eksternal" id="tujuan_eksternal">
        <span class="invalid-feedback" id="error_tujuan"></span>
      </div>

      <div class="form-group col-lg-6 d-none" id="asal_internal">
        <label class="text-dark require">Tujuan Surat</label>
        <select class="form-control selectpicker" data-provide="selectpicker" data-live-search="true" data-title="Pilih Unit" data-header="Unit" name="tujuan_internal" id="tujuan_internal">
          <?php foreach ($unit as $val) { ?>
            <option value="<?= $val['id'] ?>"><?= $val['nama'] ?></option>
          <?php } ?>
        </select>
        <small class="text-danger" id="error_tujuan_internal"></small>
      </div>
    </div>

    <div class="row">
      <div class="form-group file-group col-lg-6">
        <label class="text-dark">Upload file surat</label>
        <div class="input-group">
          <input type="text" class="form-control file-value file-browser" placeholder="Pilih draft surat" id="file" readonly>
          <input type="file" name="file" multiple>
          <span class="input-group-addon">
            <i class="fa fa-upload"></i>
          </span>
        </div>
        <small class="text-danger" id="error_file"></small>
      </div>

      <div class="form-group file-group col-lg-6">
        <label class="text-dark">Upload file lampiran</label>
        <div class="input-group">
          <input type="text" class="form-control file-value file-browser" placeholder="Pilih file lampiran 1" id="file_lampiran" readonly>
          <input type="file" name="file_lampiran[]" multiple>
          <span class="input-group-addon">
            <i class="fa fa-upload"></i>
          </span>
        </div>
        <small class="text-danger" id="error_file_lampiran"></small>
      </div>
    </div>


    <!-- 
      <div class="row no-gutters">
        <div class="form-group file-group col-lg-6">
          <label class="text-dark">Upload file lampiran</label>
          <div class="input-group">
            <input type="text" class="form-control file-value file-browser" placeholder="Pilih file lampiran 1" id="file_lampiran" readonly>
            <input type="file" name="file_lampiran[]" multiple>
            <span class="input-group-addon">
              <i class="fa fa-upload"></i>
            </span>
          </div>
          <small class="text-danger" id="error_file_lampiran"></small>

        </div>
        <div class="col-lg-6">
          <div class="btn-lampiran pt-35 ml-3">
            <button type="button" class="btn btn-sm" onclick="add_lampiran()"><i class="fa fa-plus text-success"></i></button>
          </div>
        </div>
      </div>

      <div id="lampiran-area"></div> 
    -->


    <div class="row">
      <div class="form-group col-lg-6">
        <label class="text-dark">Tembusan Internal</label>
        <select class="form-control selectpicker" data-provide="selectpicker" data-live-search="true" data-title="Pilih Unit" data-header="Tembusan" name="tembusan_internal[]" id="tembusan_internal" multiple>
          <?php foreach ($unit as $val) { ?>
            <option value="<?= $val['id'] ?>"><?= $val['nama'] ?></option>
          <?php } ?>
        </select>
        <small class="text-danger" id="error_tembusan"></small>
      </div>
      <div class="form-group col-lg-6">
        <label class="text-dark">Tembusan Eksternal</label>
        <textarea class="form-control" name="tembusan_eksternal" id="tembusan_eksternal"></textarea>
        <small class="font-italic">*Gunakan separator |</small>
      </div>
    </div>

  </div>

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

    $(".modal-footer").append(`
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
      <button class="btn btn-round btn-custom" type="button" onclick="add_action();">Simpan</button>
      `);

  });


  function add_action() {
    var formdata = new FormData($('#form_draft')[0]);
    var input = $("form#form_draft input[type=text]");
    var modal = $(".modal.show").attr('id');
    $(input).removeClass('is-invalid');
    $('.has-error').empty();
    $('.has-error').removeClass('has-error');
    $('.has-invalid').removeClass('is-invalid .has-invalid');
    $.ajax({
      url: "<?= site_url($module . '/draft?tipe=proses') ?>",
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

  /*
    var count = 0;

    function add_lampiran() {
      var id = count++;
      $("#lampiran-area").append(`
        <div class="row no-gutters" id="lampiran_` + id + `">
          <div class="form-group file-group col-lg-6">
            <div class="input-group">
              <input type="text" class="form-control file-value file-browser" placeholder="Pilih file lampiran ` + (id + 2) + `" id="file_lampiran" readonly>
              <input type="file" name="file_lampiran[]" multiple>
              <span class="input-group-addon">
                <i class="fa fa-upload"></i>
              </span>
            </div>
            <small class="text-danger" id="error_file_lampiran"></small>

          </div>
          <div class="col-lg-6">
            <div class="btn-lampiran ml-3">
              <button type="button" class="btn btn-sm" onclick="del_lampiran('` + id + `')"><i class="fa fa-trash text-danger"></i></button>
            </div>
          </div>
        </div>
      `);
    }

    function del_lampiran(id) {
      $("#lampiran_" + id).remove();
    }
  */

  function opt_kategori() {
    var kat = $("#kategori").find(':selected').val();
    if (kat == 'internal') {
      $("#asal_internal").removeClass('d-none');
      $("#asal_eksternal").addClass('d-none');
    } else {
      $("#asal_eksternal").removeClass('d-none');
      $("#asal_internal").addClass('d-none');
    }
  }

  /*
    function add_action() {
      var formdata = new FormData($('#form_add')[0]);
      var input = $("form#form_add input[type=text]");
      var modal = $(".modal.show").attr('id');
      $(input).removeClass('is-invalid');
      $('.has-error').empty();
      $('.has-error').removeClass('has-error');
      $('.has-invalid').removeClass('is-invalid .has-invalid');
      $.ajax({
        url: "<?= site_url($module . '/draft?tipe=proses') ?>",
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
  */


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
                  <label class="text-dark">` + data[i].nama + `</label>
                  <textarea class="form-control" rows="5" name="` + data[i].id + `" id="` + data[i].id + `"></textarea>
                  <span class="invalid-feedback" id="error_` + data[i].id + `"></span>
                </div>
              `);
            } else {
              $("#kolom-area").append(`
                <div class="form-group col-lg-6">
                  <label class="text-dark ` + ((data[i].id != 1) ? '' : '') + `">` + data[i].nama + `</label>
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