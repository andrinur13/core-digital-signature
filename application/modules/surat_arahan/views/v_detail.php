<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!-- <div class="col-lg-12">
  <div class="card shadow-2">
    <div class="card-body form-type-round"> -->
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
<?php if ($isPejabat == FALSE) { ?>
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
        <p class="col-form-label">: <?= $detail['file'] ?></p>
      </div>
    </div>
  </div>
<?php } ?>

<div class="card card-bordered card-body <?= ($isPejabat == TRUE) ? 'd-none' : ''; ?>">
  <form id="form_arahan" class="form-horizontal form-type-round" method="POST" action="" enctype="multipart/form-data">

    <div class="form-group">
      <label class="text-dark require">Catatan Arahan/Rapat</label>
      <p class="form-control"><?= $detail['pejabat'] ?></p>
    </div>
    <div class="form-group">
      <label class="text-dark require">Catatan Arahan/Rapat</label>
      <textarea class="form-control" rows="5" name="catatan" id="catatan"><?= $detail['arahan'] ?></textarea>
      <span class="invalid-feedback" id="error_catatan"></span>
    </div>
    <input type="hidden" name="id" value="<?= encode(urlencode($detail['logId'])) ?>">
  </form>
</div>

<div class="card card-bordered card-body <?= ($isPejabat == FALSE) ? 'd-none' : ''; ?>">
  <form id="form_jawaban" class="form-horizontal form-type-round" method="POST" action="" enctype="multipart/form-data">

    <div class="row">
      <div class="col-3">Tanggal Permohonan</div>
      <div class="col-8">
        <p class="fw-600">: <?= IndonesianDate($detail['tgl_permohonan']) ?></p>
      </div>
    </div>
    <div class="row">
      <div class="col-3">Nama Pemohon</div>
      <div class="col-8">
        <p class="fw-600">: <?= $detail['pemohon'] ?></p>
      </div>
    </div>

    <div class="row">
      <div class="col-3">Catatan Pemohon</div>
      <div class="col-8">
        <p class="fw-600">: <?= $detail['arahan'] ?></p>
      </div>
    </div>
    <div class="form-group mt-3">
      <label class="text-dark require">Catatan Arahan/Jawaban</label>
      <textarea class="form-control" rows="5" name="catatan_jawaban" id="catatan_jawaban"><?= $detail['jawaban'] ?></textarea>
      <span class="invalid-feedback" id="error_catatan_jawaban"></span>
    </div>
    <input type="hidden" name="id" value="<?= encode(urlencode($detail['logId'])) ?>">
  </form>
</div>


<footer class="mx-4 my-4 text-right">
  <button type="button" class="btn btn-secondary" onclick="tutup()">Batal</button>
  <button class="btn btn-round btn-custom" type="button" onclick="proses();">Simpan</button>
</footer>

<script type="text/javascript">
  $(document).ready(function() {
    $(".close").addClass('d-none');
  });

  function tutup() {
    var modal = $(".modal.show:last").attr('id');
    $('#datatables_ajax').DataTable().ajax.reload(false, null);
    $('#' + modal + '').modal('hide');
  }

  function proses() {
    var modal = $(".modal.show:last").attr('id');
    <?php if ($isPejabat == FALSE) { ?>
      var formdata = new FormData($('#form_arahan')[0]);
      var input = $("form#form_arahan input[type=text]");
      var url = "<?= site_url('surat_masuk/Surat/arahan?tipe=proses&mode=update') ?>";
    <?php } else { ?>
      var formdata = new FormData($('#form_jawaban')[0]);
      var input = $("form#form_jawaban input[type=text]");
      var url = "<?= site_url($module . '/jawab?tipe=proses&mode=update') ?>";
    <?php } ?>

    $(input).removeClass('is-invalid');
    $('.has-error').empty();
    $('.has-error').removeClass('has-error');
    $('.has-invalid').removeClass('is-invalid .has-invalid');
    $.ajax({
      url: url,
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
            $('#error_' + i + '').text(log);
            $('#error_' + i + '').addClass('has-error');
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