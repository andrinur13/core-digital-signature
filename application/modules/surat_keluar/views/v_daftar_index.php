<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if ($this->session->flashdata('msg_register')) {
   $msg = $this->session->flashdata('msg_register');
?>
   <div class="callout callout-<?php echo $msg['type']; ?>" role="alert">
      <button type="button" class="close" data-dismiss="callout" aria-label="Close">
         <span>×</span>
      </button>
      <h5><?php echo $msg['title']; ?></h5>
      <p><?php echo $msg['text']; ?></p>
   </div>

<?php } ?>
<div class="card card-outline-primary">
   <div class="card-header">
      <h4 class="card-title"><strong>Data Surat Keluar</strong></h4>
      <div class="btn-toolbar">
      </div>
   </div>

   <div class="card-body" id="tbl-container">
      <form class="" method="POST" action="" id="frmFilter">
         <div class="" role="alert">
            <!-- <h5 class="alert-heading">Pencarian : </h5> -->
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     <label>Status Baca :</label>
                     <select name="filter_status_baca" id="filter_status_baca" class="form-control" data-provide="selectpicker" data-live-search="true">
                        <option value="">-- Filter Status Baca --</option>
                        <?php foreach ($ref_status_baca as $baca) { ?>
                           <option value="<?php echo $baca['id']; ?>" <?php echo ($baca['id'] == $this->input->post('filter_status_baca')) ? 'selected="selected"' : ''; ?>><?php echo $baca['name']; ?></option>
                        <?php } ?>
                     </select>
                  </div>
               </div>

               <div class="col-md-4">
                  <div class="form-group">
                     <label>Sifat Urgensi :</label>
                     <select name="filter_sifat" id="filter_sifat" class="form-control" data-provide="selectpicker" data-live-search="true">
                        <option value="">-- Filter Sifat Urgensi --</option>
                        <?php foreach ($ref_sifat as $sft) { ?>
                           <option value="<?php echo $sft['id']; ?>" <?php echo ($sft['id'] == $this->input->post('filter_sifat')) ? 'selected="selected"' : ''; ?>><?php echo $sft['name']; ?></option>
                        <?php } ?>
                     </select>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label>Tujuan Surat :</label>
                     <select name="filter_tujuan" id="filter_tujuan" class="form-control" data-provide="selectpicker" data-live-search="true">
                        <option value="">-- Filter Tujuan --</option>
                        <?php foreach ($ref_tujuan as $tjn) { ?>
                           <option value="<?php echo $tjn['id']; ?>" <?php echo ($tjn['id'] == $this->input->post('filter_tujuan')) ? 'selected="selected"' : ''; ?>><?php echo $tjn['name']; ?></option>
                        <?php } ?>
                     </select>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Tanggal Awal :</label>
                     <input name="filter_tanggal_awal" class="btn form-control" placeholder="dd/mm/yyyy" data-provide="datepicker" data-date-today-highlight="true" data-date-format="dd/mm/yyyy" type="text" value="<?php echo set_value('filter_tanggal_awal', $tgl_awal); ?>">
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Tanggal Akhir :</label>
                     <input name="filter_tanggal_akhir" class="btn form-control" placeholder="dd/mm/yyyy" data-provide="datepicker" data-date-today-highlight="true" data-date-format="dd/mm/yyyy" type="text" value="<?php echo set_value('filter_tanggal_akhir', $tgl_akhir); ?>">
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-2">
                  <div class="form-group">
                     <button type="submit" class="btn btn-primary"><i class="ti-search"></i> Tampilkan</button>
                  </div>
               </div>
            </div>
         </div>
      </form>

      <table class="table table-separated table-striped tab" cellspacing="0" id="datatables_ajax">
         <thead class="bg-color-primary1">
            <tr>
               <th class="font-weight-bold">No.</th>
               <th class="font-weight-bold">Tanggal</th>
               <th class="font-weight-bold">Sifat Urgensi</th>
               <th class="font-weight-bold" width="15%">No. Surat</th>
               <th class="font-weight-bold">Perihal</th>
               <th class="font-weight-bold">Jenis</th>
               <th class="font-weight-bold">Tujuan</th>
               <th class="font-weight-bold" width="5%" nowrap>Tanda Tangan</th>
               <th class="font-weight-bold" width="5%">Aksi</th>
            </tr>
         </thead>
      </table>
   </div>
</div>

<!-- tambah Modal -->
<div class="modal fade" id="modal_form" tabindex="-1">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h4>Tambah Surat Keluar</h4>
            <button type="button" class="close" data-dismiss="modal">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body form-type-round">
            <form class="form-horizontal" name="form" id="form" method="POST" action="<?php echo site_url($moduleAdd); ?>" role="form" enctype="multipart/form-data">
               <div class="card card-bordered card-body">
                  <div class="row">
                     <div class="form-group col-lg-4">
                        <label class="text-dark" for="jenis">Status Baca</label>
                        <select name="jenis" class="form-control">
                           <option value="">-- PILIH --</option>
                           <?php foreach ($ref_jenis_surat as $jns) { ?>
                              <option value="<?php echo $jns['id']; ?>"><?php echo $jns['name']; ?></option>
                           <?php } ?>
                        </select>
                        <span class="invalid-feedback" id="error_jenis"></span>
                     </div>
                     <div class="form-group col-lg-4">
                        <label class="text-dark" for="jenis">Jenis Surat</label>
                        <select name="jenis" class="form-control">
                           <option value="">-- PILIH --</option>
                           <?php foreach ($ref_jenis_surat as $jns) { ?>
                              <option value="<?php echo $jns['id']; ?>"><?php echo $jns['name']; ?></option>
                           <?php } ?>
                        </select>
                        <span class="invalid-feedback" id="error_jenis"></span>
                     </div>
                     <div class="form-group col-lg-4">
                        <label class="text-dark" for="sifat">Sifat Urgensi</label>
                        <select name="sifat" class="form-control">
                           <option value="">-- PILIH --</option>
                           <?php foreach ($ref_sifat as $sft) { ?>
                              <option value="<?php echo $sft['id']; ?>"><?php echo $sft['name']; ?></option>
                           <?php } ?>
                        </select>
                        <span class="invalid-feedback" id="error_sifat"></span>
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-lg-6">
                        <label class="text-dark" for="tanggal">Tanggal</label>
                        <input name="tanggal" class="file-value form-control" placeholder="dd/mm/yyyy" data-provide="datepicker" data-date-today-highlight="true" data-date-format="dd/mm/yyyy" type="text" value="<?php echo set_value('tanggal'); ?>">
                        <span class="invalid-feedback" id="error_tanggal"></span>
                     </div>
                     <div class="form-group col-lg-6">
                        <label class="text-dark" for="klasifikasi">Klasifikasi</label>
                        <select name="klasifikasi" class="form-control">
                           <option value="">-- PILIH --</option>
                           <?php foreach ($ref_klasifikasi as $kla) { ?>
                              <option value="<?php echo $kla['id']; ?>"><?php echo $kla['name']; ?></option>
                           <?php } ?>
                        </select>
                        <span class="invalid-feedback" id="error_klasifikasi"></span>
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-lg-6">
                        <label class="text-dark" for="no_surat">Nomor Surat</label>
                        <input name="no_surat" class="btn form-control" type="text" value="<?php echo set_value('no_surat'); ?>">
                        <span class="invalid-feedback" id="error_no_surat"></span>
                     </div>
                     <div class="form-group col-lg-6">
                        <label class="text-dark" for="perihal">Perihal</label>
                        <input name="perihal" class="btn form-control" type="text" value="<?php echo set_value('perihal'); ?>">
                        <span class="invalid-feedback" id="error_perihal"></span>
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-lg-12">
                        <label class="text-dark" for="ringkasan">Isi Ringkasan</label>
                        <textarea name="ringkasan" class="form-control" rows="5"><?php echo set_value('ringkasan'); ?></textarea>
                        <span class="invalid-feedback" id="error_ringkasan"></span>
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-lg-6">
                        <label class="text-dark" for="kategori">Kategori</label>
                        <div class="col custom-controls-stacked">
                           <label class="custom-control custom-control-lg custom-radio">
                              <input type="radio" class="custom-control-input" name="kategori" id="radio-internal" value="internal" checked>
                              <span class="custom-control-indicator"></span>
                              <span class="custom-control-description">Internal</span>
                           </label>
                           <label class="custom-control custom-control-lg custom-radio">
                              <input type="radio" class="custom-control-input" name="kategori" id="radio-eksternal" value="eksternal">
                              <span class="custom-control-indicator"></span>
                              <span class="custom-control-description">Eksternal</span>
                           </label>
                        </div>
                        <span class="invalid-feedback" id="error_kategori"></span>
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-lg-12 internal">
                        <label class="text-dark" for="tujuan_internal">Unit/Biro/Lembaga</label>
                        <select name="tujuan_internal" class="form-control">
                           <option value="">-- PILIH --</option>
                           <?php foreach ($ref_unit as $unt) { ?>
                              <option value="<?php echo $unt['id']; ?>"><?php echo $unt['name']; ?></option>
                           <?php } ?>
                        </select>
                        <span class="invalid-feedback" id="error_tujuan_internal"></span>
                     </div>
                     <div class="form-group col-lg-12 eksternal" style="display: none;">
                        <label class="text-dark" for="tujuan_eksternal">Tujuan Surat (Luar Universitas)</label>
                        <input name="tujuan_eksternal" class="btn form-control" type="text">
                        <span class="invalid-feedback" id="error_tujuan_eksternal"></span>
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group file-group col-lg-12">
                        <label class="text-dark">Upload file</label>
                        <div class="input-group">
                           <input type="text" class="form-control file-value file-browser" placeholder="Choose file..." readonly>
                           <input type="file" name="file_surat" class="form-control">
                           <span class="input-group-addon">
                              <i class="fa fa-upload"></i>
                           </span>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="form-group col-lg-12">
                        <label class="text-dark">Referensi Surat</label>
                        <a class="nav-link col-lg-6 text-center" data-toggle="tab" href="#" id="referensi">Referensi Surat (Opsional)</a>
                     </div>
                     <div class="card-body" id="tbl-referensi-surat" style="display: none;">
                        <table class="table table-separated table-striped tab" cellspacing="0" id="datatables_ajax_referensi" width="100%">
                           <thead class="bg-color-primary1">
                              <tr>
                                 <th class="font-weight-bold">No.</th>
                                 <th width="1%">
                                    <input type="checkbox" name="select_all" class="select-all" onchange="selectAll()">
                                 </th>
                                 <th class="font-weight-bold">Tanggal</th>
                                 <th class="font-weight-bold">No. Surat</th>
                                 <th class="font-weight-bold">Perihal</th>
                              </tr>
                           </thead>
                        </table>
                     </div>
                  </div>
               </div>

               <footer class="mx-4 my-4 text-right">
                  <button class="btn btn-round btn-custom" type="submit" href="">Simpan</button>
               </footer>
            </form>
         </div>
      </div>
   </div>
</div>
<!-- modal -->

<div class="modal modal-center" id="modal_dell" tabindex="-1">
   <div class="modal-dialog modal-sm">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel"></h4>
            <button type="button" class="close" data-dismiss="modal">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <?php $attributes = array('class' => 'form-horizontal', 'id' => 'form_dell');
         echo form_open_multipart('#', $attributes); ?>
         <input type="hidden" name="id_dell" id="id_dell">
         <div class="modal-body">
            <div class="container">
               Apa anda yakin ingin menghapus data ini?
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-bold btn-pure btn-secondary" data-dismiss="modal">Batal</button>
               <button type="button" onclick="del_action();" class="btn btn-bold btn-pure btn-danger">Hapus</button>
            </div>
         </div>
         </form>
      </div>
   </div>
</div>

<script type="text/javascript">
   $(function() {
      var modalAlert = function(t) {
         t = $.extend(!0, {
            container: '#tbl-container',
            type: 'success',
            message: '',
            close: !0,
            icon: (t.type == 'success') ? 'check' : 'warning'
         }, t);
         var e = 'prefix_' + Math.floor(Math.random() * (new Date).getTime());
         o = '<div id="' + e + '" class="custom-alerts alert alert-dismissible alert-' + t.type + ' fade show">' + (t.close ? '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' : '') + '<i class="fa fa-' + t.icon + '"></i> ' + t.message + '</div>';
         return $(t.container).prepend(o), $(t.container).focus();
      }

      var ajaxParams = {};
      var setAjaxParams = function(name, value) {
         ajaxParams[name] = value;
      };

      var dt = $('#datatables_ajax').DataTable({
         "processing": true,
         "serverSide": true,
         // "searching": true,
         "ajax": {
            "url": "<?php echo site_url($module . '/datatables_data/') ?>",
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
         //"dom": "<'row'<'col-md-4 col-sm-12'l<'table-group-actions pull-right'>>r><'table-scrollable't><'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'p>>",
         'language': {
            'search': 'Cari',
            'searchPlaceholder': 'Nomor Surat / Perihal',
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
            [1, 'asc']
         ],
         'columnDefs': [{
               "visible": false,
               "targets": [0]
            }, {
               "orderable": false,
               "searchable": false,
               "targets": [0]
            },
            {
               "orderable": false,
               "searchable": false,
               "className": "table-actions text-nowrap",
               "targets": [3, 8]
            }
         ]
      });

      dt.on('order.dt search.dt', function() {
         dt.column(0, {
            search: 'applied',
            order: 'applied'
         }).nodes().each(function(cell, i) {
            cell.innerHTML = i + 1;
         });
      }).draw();

      $('#frmFilter').on('submit', function(e) {
         e.preventDefault();

         var _this = $(this);
         $('input, select', _this).each(function() {
            setAjaxParams($(this).attr('name'), $(this).val());
         });

         dt.ajax.reload(null, false);
      });

      // ----------------- Detail -----------------//
      var modalDetail = function(opt) {
         opt = opt || {};
         app.modaler({
            title: 'Detail Surat Keluar',
            url: opt.url,
            footerVisible: false,
            size: "lg",
            bodyExtraClass: "form-type-round",
            onConfirm: opt.callback
         });
      }

      $('#datatables_ajax').on('click', '#detail-btn', function() {
         var id = $(this).attr('data-id'),
            module = "<?php echo site_url($module . '/detail/') ?>" + id;
         modalDetail({
            url: module
         });
         dt.ajax.reload(null, false);
      });

      $('#datatables_ajax').on('click', '#arsipkan-btn', function(e) {
         e.preventDefault();
         var dataid = $(this).attr('data-id');
         var actionUrl = "<?php echo site_url($module . '/arsipkan/'); ?>" + dataid;
         app.modaler({
            html: 'Apakah Anda yakin ingin mengarsipkan surat ini?',
            title: 'Konfirmasi Arsipkan Surat',
            cancelVisible: true,
            confirmText: 'YA',
            cancelText: 'Tidak',
            cancelClass: 'btn btn-w-sm btn-secondary',
            confirmClass: 'btn btn-w-sm btn-danger',
            onConfirm: function() {
               $.ajax({
                  type: "POST",
                  url: actionUrl,
                  success: function(response) {
                     if (response.status == true) {
                        dt.ajax.reload(null, false);
                     }
                     Swal.fire({
                        title: "Informasi",
                        text: response.text,
                        icon: response.type
                     });
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                     console.log(textStatus + errorThrown);
                  }
               });
            }
         });
      });
   });
</script>