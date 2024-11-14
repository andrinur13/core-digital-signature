<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if ($this->session->flashdata('message_form')) {
   $msg = $this->session->flashdata('message_form');
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
      <h4 class="card-title"><strong>Data Konsep Surat Keluar</strong></h4>
      <div class="btn-toolbar">
         <a href="#" id="add-btn" class="btn btn-sm btn-round btn-custom" title="Tambah Surat Keluar">+ Tambah</a>
      </div>
   </div>

   <div class="card-body" id="tbl-container">
      <form class="" method="POST" action="" id="frmFilter">
         <div class="" role="alert">
            <!-- <h5 class="alert-heading">Pencarian : </h5> -->
            <div class="row">
               <div class="col-md-4">
                  <div class="form-group">
                     <label>Sifat :</label>
                     <select name="filter_sifat" id="filter_sifat" class="form-control" data-provide="selectpicker" data-live-search="true">
                        <option value="">-- Filter Urgensi --</option>
                        <?php foreach ($ref_sifat as $sft) { ?>
                           <option value="<?php echo $sft['id']; ?>" <?php echo ($sft['id'] == $this->input->post('filter_sifat')) ? 'selected="selected"' : ''; ?>><?php echo $sft['name']; ?></option>
                        <?php } ?>
                     </select>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label>Kategori :</label>
                     <select name="filter_kategori" id="filter_kategori" class="form-control" data-provide="selectpicker" data-live-search="true">
                        <option value="">-- Filter Kategori --</option>
                        <?php foreach ($ref_kategori as $kat) { ?>
                           <option value="<?php echo $kat['id']; ?>" <?php echo ($kat['id'] == $this->input->post('filter_kategori')) ? 'selected="selected"' : ''; ?>><?php echo $kat['name']; ?></option>
                        <?php } ?>
                     </select>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group">
                     <label>Status Surat :</label>
                     <select name="filter_status" id="filter_status" class="form-control" data-provide="selectpicker" data-live-search="true">
                        <option value="">-- Filter Status Surat --</option>
                        <?php foreach ($ref_status as $stat) { ?>
                           <option value="<?php echo $stat['id']; ?>" <?php echo ($stat['id'] == $this->input->post('filter_status')) ? 'selected="selected"' : ''; ?>><?php echo $stat['name']; ?></option>
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
               <th class="font-weight-bold">No. Surat</th>
               <th class="font-weight-bold" width="10%">Sifat</th>
               <th class="font-weight-bold">Perihal</th>
               <th class="font-weight-bold">Jenis</th>
               <th class="font-weight-bold">Tujuan</th>
               <th class="font-weight-bold" width="10%">Status</th>
               <th class="font-weight-bold" width="5%">Aksi</th>
            </tr>
         </thead>
      </table>
   </div>
</div>

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
            'searchPlaceholder': 'Nomor Surat / Jenis Surat',
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
            [1, 'desc']
         ],
         'columnDefs': [{
               "visible": false,
               "targets": [0]
            },
            {
               "orderable": false,
               "searchable": false,
               "targets": [0]
            },
            {
               "orderable": false,
               "searchable": false,
               "className": "table-actions text-nowrap",
               "targets": [7, 8]
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

      $('#datatables_ajax').on('click', '#delete', function(e) {
         e.preventDefault();
         var action = $(this).attr('href');

         app.modaler({
            html: 'Apakah Anda yakin ingin menghapus data ini?',
            title: 'Hapus Konsep Surat Keluar',
            cancelVisible: true,
            confirmText: 'YA',
            cancelText: 'Tidak',
            cancelClass: 'btn btn-w-sm btn-secondary',
            confirmClass: 'btn btn-w-sm btn-danger',
            onConfirm: function() {
               $.get(action, function(result) {
                  var status = (result.status == true) ? 'success' : 'danger';
                  modalAlert({
                     type: status,
                     message: result.msg
                  });
                  dt.ajax.reload(null, false);
               }, "json");
            }
         });
      });

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

      // ----------------- Detail -----------------//
      var modalDetail = function(opt) {
         opt = opt || {};
         app.modaler({
            title: 'Detail Konsep Surat Keluar',
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
      });


      // ----------------- Add -----------------//
      var modalAdd = function(opt) {
         opt = opt || {};
         app.modaler({
            title: 'Tambah Konsep Surat Keluar',
            url: opt.url,
            footerVisible: false,
            size: "lg",
            bodyExtraClass: "form-type-round",
            onConfirm: opt.callback,
            backdrop: false,
         });
      }

      $('#add-btn').on('click', function() {
         var module = "<?php echo site_url($module . '/add/') ?>";
         modalAdd({
            url: module,
         });
      });


      // ----------------- Verifikasi -----------------//
      var modaledit = function(opt) {
         opt = opt || {};
         app.modaler({
            title: 'Ubah Konsep Surat Keluar',
            url: opt.url,
            footerVisible: false,
            size: "lg",
            bodyExtraClass: "form-type-round",
            onConfirm: opt.callback,
            backdrop: false,
         });
      }

      $('#datatables_ajax').on('click', '#ubah-btn', function(e) {
         // e.preventDefault();
         var id = $(this).attr('data-id'),
            module = "<?php echo site_url($module . '/update/') ?>" + id;
         modaledit({
            url: module,
            // callback: function(modal) {
            //    $frm = modal.find('form');
            //    $($frm[0]).on('submit', function(e) {
            //       e.preventDefault();
            //       var formData = new FormData(this);
            //       $.ajax({
            //          url: $(this).attr('action'),
            //          type: 'POST',
            //          data: formData,
            //          contentType: false,
            //          processData: false,

            //          dataType: 'json',
            //          success: function(result) {
            //             if (result.error == 'null') {
            //                $('#' + modal[0].id).modal('hide');
            //                Swal.fire({
            //                   title: "Informasi",
            //                   text: result.text,
            //                   icon: result.type
            //                });
            //                dt.ajax.reload(null, false);
            //             } else {
            //                $.each(result.error, function(i, log) {
            //                   if (log != '') {
            //                      $('[name="' + i + '"]').addClass('is-invalid');
            //                   }

            //                   $('.error_' + i).text(log);

            //                });

            //                Swal.fire({
            //                   title: "Informasi",
            //                   text: "Periksa Semua Form Inputan",
            //                   icon: "warning"
            //                });

            //             }
            //          },
            //          error: function(jqXHR, textStatus, errorThrown) {
            //             console.log(textStatus + errorThrown);
            //          }
            //       });
            //    });
            // }
         });
      });
   });
</script>