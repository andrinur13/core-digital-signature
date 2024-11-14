<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
  .modal-body {
    max-height: calc(100vh - 143px);
    overflow-y: auto;
  }
</style>

<form id="form_arahan" class="form-horizontal form-type-round" method="POST" action="" enctype="multipart/form-data">
  <div class="form-group">
    <label class="text-dark require">Pejabat</label>
    <select class="form-control selectpicker" name="pejabat" id="pejabat" data-provide="selectpicker" data-live-search="true" data-title="Pilih Pejabat" data-header="Pejabat" <?= (!isset($detail) ? '' : 'disabled') ?>>
      <?php foreach ($pejabat as $val) { ?>
        <option value="<?= $val['id'] ?>" <?= (!isset($detail) ? '' : (($detail['pejabatId'] == $val['id']) ? 'selected="select"' : '')); ?>><?= $val['nama'] ?></option>
      <?php } ?>
    </select>
    <small class="text-danger" id="error_pejabat"></small>
  </div>
  <input type="hidden" name="pejabat_nama" id="pejabat_nama">

  <div class="form-group">
    <label class="text-dark require">Catatan</label>
    <textarea class="form-control" rows="5" name="catatan" id="catatan"><?= (!isset($detail) ? '' : $detail['catatan']); ?></textarea>
    <span class="invalid-feedback" id="error_catatan"></span>
  </div>
  <input type="hidden" name="id" value="<?= encode(urlencode($id)) ?>">
  </div>

  <footer class="mx-4 my-4 text-right">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
    <button class="btn btn-round btn-custom" type="button" onclick="proses();">Simpan</button>
  </footer>
</form>

<script type="text/javascript">
  $(document).ready(function() {
    $("#pejabat").change(function() {
      var nama = $("#pejabat").find(':selected').text();
      $("#pejabat_nama").val(nama);
    })
  });

  function proses() {
    var formdata = new FormData($('#form_arahan')[0]);
    var input = $("form#form_arahan input[type=text]");
    var modal = $(".modal.show:last").attr('id');

    $(input).removeClass('is-invalid');
    $('.has-error').empty();
    $('.has-error').removeClass('has-error');
    $('.has-invalid').removeClass('is-invalid .has-invalid');
    $.ajax({
      url: "<?= site_url($module . '/arahan?tipe=proses&mode=') . $mode ?>",
      type: "POST",
      data: formdata,
      processData: false,
      contentType: false,
      dataType: "JSON",
      success: function(data) {
        if (data.error == 'null') {
          render_arahan('<?= ($mode == 'add') ? encode(urlencode($id)) : encode(urlencode($detail['suratId'])) ?>');
          $('#' + modal + '').modal('hide');
          app.toast(data.text);
          // $('#datatables_ajax').DataTable().ajax.reload(false, null);

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
        $('#datatables_ajax').DataTable().ajax.reload(false, null);
        app.toast('Gagal memproses! Silahkan hubungi administrator.');
      }
    });
  }

  function render_arahan(id) {
    $.ajax({
      url: "<?= site_url($module . '/arahan?tipe=ajax_arahan&id=') ?>" + id,
      type: "GET",
      dataType: "JSON",
      success: function(obj) {
        $("#arahan-area").empty();
        if (obj != null) {
          data = Object.keys(obj).map(function(key) {
            return obj[key];
          });
          for (var i = 0; i < data.length; i++) {
            /*
            $('#arahan-area').append(`
                <div class="media media-single w-50">
                  <span class="text-danger" data-toggle="tooltip" title="Hapus arahan" style="cursor: pointer;" onclick="hapus_arahan('` + data[i].id + `')"><i class="fa fa-trash"></i></span>
                  <span class="text-dark ">` + (i + 1) + `</span>
                  <div class="media-body">
                    <p>` + data[i].catatan + `</p>
                  </div>
                </div>
              `);
              */

            $('#arahan-area').append(`
              <tr>
                <td>
                  <div class="btn-group btn-group-sm ` + ((data[i].tglBaca == null) ? '' : 'd-none') + `">
                    <button class="btn dropdown-toggle bg-warning" data-toggle="dropdown">Aksi</button>
                    <div class="dropdown-menu dropdown-menu-left">

                      <a class="dropdown-item" href="javascript:void()" onclick="ubah_arahan('` + data[i].id + `')">Ubah</a>

                      <a class="dropdown-item" href="javascript:void()" onclick="hapus_arahan('` + data[i].id + `')">Hapus</a>
                    </div>
                  </div>
                </td>
                <td>` + data[i].pejabat + `</td>
                <td>` + data[i].catatan + `</td>
                <td>` + ((data[i].jawaban == null) ? '' : data[i].jawaban) + `</td>
                <td><span class="badge badge-sm rounded badge-` + ((data[i].tglBaca == null) ? 'warning' : 'success') + `">Belum</span></td>
              </tr>
              `);

          }
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {}
    });
  }
</script>