<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="col-lg-12">
  <div class="card shadow-2">
    <div class="card-body form-type-round">
      <div class="form-group row">
        <label class="col-4 col-lg-2 col-form-label" for="input-2">Jenis</label>
        <div class="col-8 col-lg-10">
          <p class="col-form-label">: <?= $detail['jenis_surat'] ?></p>
          <div class="invalid-feedback"></div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-4 col-lg-2 col-form-label" for="input-2">Asal</label>
        <div class="col-8 col-lg-10">
          <p class="col-form-label">: <?= $detail['asal'] ?></p>
          <div class="invalid-feedback"></div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-4 col-lg-2 col-form-label" for="input-2">Nomor Surat</label>
        <div class="col-8 col-lg-10">
          <p class="col-form-label">: <?= $detail['nomor'] ?></p>
          <div class="invalid-feedback"></div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-4 col-lg-2 col-form-label" for="input-2">Perihal</label>
        <div class="col-8 col-lg-10">
          <p class="col-form-label">: <?= $detail['perihal'] ?></p>
          <div class="invalid-feedback"></div>
        </div>
      </div>
      <div class="form-group row d-none">
        <label class="col-4 col-lg-2 col-form-label" for="input-2">Tujuan</label>
        <div class="col-8 col-lg-10">
          <p class="col-form-label">: Biro Sistem Informasi</p>
          <div class="invalid-feedback"></div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-4 col-lg-2 col-form-label" for="input-2">Tanggal Diterima</label>
        <div class="col-8 col-lg-10">
          <p class="col-form-label">: <?= IndonesianDate($detail['tanggal']) ?></p>
          <div class="invalid-feedback"></div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-4 col-lg-2 col-form-label" for="input-2">Sifat Urgensi</label>
        <div class="col-8 col-lg-10">
          <p class="col-form-label">: <?= $detail['sifat'] ?></p>
          <div class="invalid-feedback"></div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-4 col-lg-2 col-form-label" for="input-2">Isi Ringkasan</label>
        <div class="col-8 col-lg-10">
          <p class="col-form-label">: <?= $detail['ringkasan'] ?></p>
          <div class="invalid-feedback"></div>
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

      <!-- <div class="card card-bordered card-body w-50"> -->
      <ul class="nav nav-tabs nav-tabs-warning nav-justified">
        <li class="nav-item col-lg-2">
          <a class=" nav-link fw-600 active" data-toggle="tab" href="#disposisi">Disposisi</a>
        </li>
        <li class="nav-item col-lg-3 <?= ($is_admin == TRUE) ? '' : 'd-none' ?>">
          <a class="nav-link fw-600" data-toggle="tab" href="#arahan">Permohonan Arahan</a>
        </li>
        <li class="nav-item col-lg-3 <?= ($is_admin == TRUE) ? '' : 'd-none' ?>">
          <a class="nav-link fw-600" data-toggle="tab" href="#tanggapan">Balas dengan Tanggapan</a>
        </li>
        <li class="nav-item col-lg-2">
          <a class="nav-link fw-600" data-toggle="tab" href="#arsip">Arsip</a>
        </li>
      </ul>
      <!-- </div> -->

      <div class="tab-content">
        <div class="tab-pane active" id="disposisi">
          <div class="card card-bordered card-body">
            <div class="">
              <a class="btn btn-sm btn-round btn-custom mb-3" href="javascript:void()" onclick="add_dispo('<?= encode(urlencode($detail['id'])) ?>')">+ Tambah Disposisi</a>
            </div>

            <div class="table-responsive">
              <table class="table table-separated table-striped" id="datatables_ajax" cellspacing="0" width="100%">
                <thead class="bg-color-primary1">
                  <tr>
                    <th class="font-weight-bold" width="15%" style="vertical-align: middle;">Sifat Urgensi</th>
                    <th class="font-weight-bold" width="25%" style="vertical-align: middle;">Catatan</th>
                    <th class="font-weight-bold" width="35%" style="vertical-align: middle;">Unit Penerima</th>
                    <th class="font-weight-bold" width="18%" style="vertical-align: middle;">Staff Penerima</th>
                    <th class="font-weight-bold" width="7%" style="vertical-align: middle;">Aksi</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="arahan" <?= ($is_admin == TRUE) ? '' : 'd-none' ?>>
          <div class="card card-bordered card-body">

            <div class="">
              <a class="btn btn-sm btn-round btn-custom mb-3" href="javascript:void()" onclick="add_arahan('<?= encode(urlencode($detail['id'])) ?>')">+ Permohonan Arahan</a>
            </div>

            <div class="card">
              <table class="table table-sm p-0 table-separated table-striped" width="100%">
                <thead class="bg-color-primary1">
                  <tr>
                    <th width="5%"></th>
                    <th width="25%">Pejabat</th>
                    <th width="25%">Permohonan</th>
                    <th width="25%">Jawaban</th>
                    <th width="25%">Dibaca</th>
                  </tr>
                </thead>
                <tbody id="arahan-area">
                  <?php if ($arahan == NULL) { ?>
                    <tr>
                      <td colspan="5" class="text-center">Belum ada permohonan arahan.</td>
                    </tr>
                    <?php } else {
                    foreach ($arahan as $key => $val) { ?>
                      <tr>
                        <td>
                          <div class="btn-group btn-group-sm <?= (($val['tglBaca'] == '') ? '' : 'd-none') ?>">
                            <button class="btn dropdown-toggle bg-warning" data-toggle="dropdown">Aksi</button>
                            <div class="dropdown-menu dropdown-menu-left">

                              <a class="dropdown-item" href="javascript:void()" onclick="ubah_arahan('<?= $val['id'] ?>')">Ubah</a>

                              <a class="dropdown-item" href="javascript:void()" onclick="hapus_arahan('<?= $val['id'] ?>')">Hapus</a>
                            </div>
                          </div>
                        </td>
                        <td><?= $val['pejabat'] ?></td>
                        <td><?= $val['catatan'] ?></td>
                        <td><?= $val['jawaban'] ?></td>
                        <td> <span class="badge badge-sm rounded badge-<?= (($val['tglBaca'] == '') ? 'warning' : 'success') ?>"><?= (($val['tglBaca'] == '') ? 'Belum' : 'Sudah') ?></span>
                          <?= (($val['tglBaca'] == '') ? '' : '<br><small class="text-muted">' . IndonesianDate($val['tglBaca']) . '</small>') ?></td>
                      </tr>
                  <?php }
                  } ?>
                </tbody>
              </table>
            </div>

          </div>
        </div>

        <div class="tab-pane fade" id="tanggapan" <?= ($is_admin == TRUE) ? '' : 'd-none' ?>>
          <div class="card card-bordered card-body">
            <div class="">
              <a class="btn btn-sm btn-round btn-custom mb-3 <?= (($tanggapan == NULL) ? '' : 'd-none'); ?>" id="btn-tanggapan" href="javascript:void()" onclick="add_tanggapan('<?= encode(urlencode($detail['id'])) ?>')">+ Tambah Tanggapan</a>
            </div>

            <div class="card">
              <table class="table table-sm p-0 table-separated table-striped" width="50%">
                <thead class="bg-color-primary1">
                  <tr>
                    <!-- <th width="5%"></th> -->
                    <!-- <th width="20%">Tindakan</th> -->
                    <th width="50%">Isi Balasan</th>
                  </tr>
                </thead>
                <tbody id="tanggapan-area">
                  <?php if ($tanggapan == NULL) { ?>
                    <tr>
                      <td colspan="1" class="">Belum ada tanggapan.</td>
                    </tr>
                    <?php } else {
                    foreach ($tanggapan as $key => $val) { ?>
                      <tr>
                        <td><?= $val['catatan'] ?></td>
                      </tr>
                  <?php }
                  } ?>
                </tbody>
              </table>
            </div>


            <tbody id="tanggapan-area">

            </tbody>
          </div>
        </div>

        <div class="tab-pane fade" id="arsip">
          <div class="card card-bordered card-body">

            <div class="row">
              <div class="col-lg-6">
                <a id="btn-arsip" class="btn btn-sm btn-round btn-custom mb-3 <?= ($detail['is_arsip'] == '') ? '' : 'd-none' ?>" href="javascript:void()" onclick="set_arsip('<?= encode(urlencode($detail['id'])) ?>')">Arsipkan</a>
              </div>
            </div>

            <div id="info-arsip" class="<?= ($detail['is_arsip'] != '') ? '' : 'd-none' ?>">
              <p class="text-dark fw-600">Surat telah diarsipkan.</p>
              <div class="form-group row">
                <label class="col-4 col-lg-2 col-form-label" for="input-2">Berkas</label>
                <div class="col-8 col-lg-10">
                  <p class="col-form-label" id="arsip_berkas">: <?= $detail['berkas_nama'] ?></p>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-4 col-lg-2 col-form-label" for="input-2">Jenis Eksemplar</label>
                <div class="col-8 col-lg-10">
                  <p class="col-form-label" id="arsip_eksemplar">: <?= $detail['eksemplar_nama'] ?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <a id="ubah_arsip" class="btn btn-sm btn-round btn-custom mb-3" href="javascript:void()" onclick="ubah_arsip('<?= encode(urlencode($detail['is_arsip'])) ?>','<?= encode(urlencode($detail['id'])) ?>')">Ubah Arsip</a>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- </div> -->
    </div>
  </div>
</div>

<!-- modal delete -->
<div class="modal modal-center" id="modal_del" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="del_title"></h4>
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <input type="hidden" id="id_delete">
      <input type="hidden" id="tipe_delete">
      <div class="modal-body">
        <div class="container" id="info_delete">

        </div>
      </div>

      <div class="ml-auto">
        <button type="button" class="btn btn-bold btn-pure btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" onclick="del_action();" class="btn btn-bold btn-pure btn-danger">Hapus</button>
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

  function add_dispo(id) {
    module = "<?= site_url($module . '/disposisi?tipe=view&mode=add&id=') ?>" + id;
    Modal({
      url: module,
      title: 'Tambah Disposisi Surat',
      footer: true,
      size: 'besar',
      callback: function(modal) {}
    })
  }

  function ubah_dispo(id, tipe) {
    if (tipe == 'unit') {
      module = "<?= site_url($module . '/disposisi?tipe=view&mode=update&id=') ?>" + id + '&tipe_update=' + tipe;
    } else {
      var params = '&id=' + id + '&key='
      module = "<?= site_url($module . '/disposisi?tipe=view_staff&mode=update') ?>" + params;
    }

    Modal({
      url: module,
      title: 'Ubah Disposisi Surat',
      footer: true,
      size: 'besar',
      callback: function(modal) {}
    })
  }

  function detail_dispo(id, tipe) {
    module = "<?= site_url($module . '/detail?tipe=detail_dispo&id=') ?>" + id + '&for=' + tipe;
    Modal({
      url: module,
      title: 'Detail Disposisi Surat',
      size: 'lg',
      callback: function(modal) {}
    })
  }

  function add_arahan(id) {
    module = "<?= site_url($module . '/arahan?tipe=view&mode=add&id=') ?>" + id;
    Modal({
      url: module,
      title: 'Tambah Arahan Surat',
      size: 'md',
      callback: function(modal) {}
    })
  }

  function ubah_arahan(id) {
    module = "<?= site_url($module . '/arahan?tipe=view&mode=update&id=') ?>" + id;
    Modal({
      url: module,
      title: 'Ubah Arahan Surat',
      size: 'md',
      callback: function(modal) {}
    })
  }

  function add_tanggapan(id) {
    module = "<?= site_url($module . '/tanggapan?tipe=view&mode=add&id=') ?>" + id;
    Modal({
      url: module,
      title: 'Tambah Tanggapan Surat',
      size: 'md',
      callback: function(modal) {}
    })
  }

  function ubah_tanggapan(id) {
    module = "<?= site_url($module . '/tanggapan?tipe=view&mode=update&id=') ?>" + id;
    Modal({
      url: module,
      title: 'Ubah Tanggapan Surat',
      size: 'md',
      callback: function(modal) {}
    })
  }

  function hapus_arahan(id) {
    var modal = $(".show").attr('id');
    $('#' + modal + '').modal('hide');
    $('#del_title').text('Hapus Atahan');
    $('#tipe_delete').val('arahan');
    $('#info_delete').text('Apa anda yakin ingin menghapus arahan ini?');
    $('#id_delete').val(id);
    $('#modal_del').modal('show');
  }

  function hapus_tanggapan(id) {
    var modal = $(".show").attr('id');
    $('#' + modal + '').modal('hide');
    $('#del_title').text('Hapus Tanggapan');
    $('#tipe_delete').val('tanggapan');
    $('#info_delete').text('Apa anda yakin ingin menghapus tanggapan ini?');
    $('#id_delete').val(id);
    $('#modal_del').modal('show');
  }

  function hapus_dispo(id) {
    var modal = $(".show").attr('id');
    $('#' + modal + '').modal('hide');
    $('#del_title').text('Hapus Disposisi');
    $('#tipe_delete').val('disposisi');
    $('#info_delete').text('Apa anda yakin ingin menghapus disposisi ini?');
    $('#id_delete').val(id);
    $('#modal_del').modal('show');
  }

  function del_action() {
    var id = $('#id_delete').val();
    var tipe = $("#tipe_delete").val();
    var params = tipe + '?tipe=delete&id=' + id;
    $.ajax({
      url: "<?= site_url($module . '/') ?>" + params,
      dataType: 'JSON',
      type: 'POST',
      data: {
        id: id,
      },
      success: function(data) {
        if (data.error == 'null') {

          if (tipe == 'arahan') {
            render_arahan('<?= encode(urlencode($detail['id'])) ?>');
          }

          if (tipe == 'tanggapan') {
            render_tanggapan('<?= encode(urlencode($detail['id'])) ?>');
            render_status();
          }

          if (tipe == 'disposisi') {
            $('#datatables_ajax').DataTable().ajax.reload(false, null);
          }
          $('#modal_del').modal('hide');
          app.toast(data.text);
        } else {
          $('#modal_del').modal('hide');
          app.toast(data.text);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $('#modal_del').modal('hide');
        app.toast('Gagal memproses! Silahkan hubungi administrator.');
      }
    });
  }

  function ubah_arsip(id, suratId) {
    var params = '&id=' + id + '&suratId=' + suratId;
    module = "<?= site_url($module . '/arsip?tipe=view&mode=update') ?>" + params;
    Modal({
      url: module,
      title: 'Ubah Arsipkan Surat',
      size: 'md',
      callback: function(modal) {}
    })
  }

  function set_arsip(id) {
    module = "<?= site_url($module . '/arsip?tipe=view&mode=add&suratId=') ?>" + id;
    Modal({
      url: module,
      title: 'Arsipkan Surat',
      size: 'md',
      callback: function(modal) {}
    })
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
          console.log(data);
          for (var i = 0; i < data.length; i++) {
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
  }

  function render_status() {
    $.ajax({
      url: "<?= site_url($module . '/detail?tipe=ajax_status&id=') . encode(urlencode($detail['id'])) ?>",
      type: "GET",
      dataType: "JSON",
      success: function(obj) {
        $("#status-area").empty();
        if (obj != null) {
          data = Object.keys(obj).map(function(key) {
            return obj[key];
          });
          for (var i = 0; i < data.length; i++) {
            $('#status-area').append(`
                <div class="mr-1 btn btn-sm btn-bold btn-round btn-flat btn-` + data[i].color + `">` + data[i].nama + `</div>
              `);
          }
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {}
    });
  }

  $(function() {
    var ajaxParams = {};
    var setAjaxParams = function(name, value) {
      ajaxParams[name] = value;
    };
    $dt = $('#datatables_ajax').DataTable({
      "processing": true,
      "serverSide": true,
      "searching": true,
      // "lengthMenu": [
      //    [20, 35, 50, -1],
      //    [20, 35, 50, "All"]
      // ],
      // "pageLength": 20,

      "ajax": {
        "url": "<?php echo site_url($module . '/datatables?tipe=disposisi&id=') . encode(urlencode($detail['id'])); ?>",
        "type": "POST",
        "data": function(d) {
          $.each(ajaxParams, function(key, value) {
            d[key] = value;
          });
        }
      },
      'drawCallback': function(settings) {
        $('[data-provide="tooltip"]').tooltip();
      },
      'language': {
        'search': 'Cari',
        'searchPlaceholder': 'Sifat/Tujuan/Unit',
        'lengthMenu': "Tampil _MENU_",
        'info': "_START_ - _END_ dari _TOTAL_",
        "paginate": {
          "previous": "Prev",
          "next": "Next",
          "last": "Last",
          "first": "First",
          "page": "Page",
          "pageOf": "of"
        }
      },
      'order': [
        [0, 'DESC']
      ],
      "columnDefs": [{
          "orderable": false,
          "targets": [0, 1, 2, 3, 4]
        },
        {
          "className": "text-center",
          "targets": []
        },
        {
          "className": "nowrap",
          "targets": [0, 1, 2, 3, 4]
        },
      ]
    });

    $('#frmFilter').on('submit', function(e) {
      e.preventDefault();

      var _this = $(this);
      $('input, select', _this).each(function() {
        setAjaxParams($(this).attr('name'), $(this).val());
      });

      $dt.ajax.reload();
    });
  });
</script>