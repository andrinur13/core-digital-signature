<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if ($this->session->flashdata('msg_form')) {
   $msg = $this->session->flashdata('msg_form');
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
      <h4 class="card-title"><strong>Daftar Surat Keluar</strong></h4>
      <div class="btn-toolbar">
      </div>
   </div>

   <div class="card-body" id="tbl-container">
      <form class="" method="POST" action="" id="frmFilter">
         <div class="" role="alert">
            <!-- <h5 class="alert-heading">Pencarian : </h5> -->
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
               <th class="font-weight-bold">Status Surat</th>
               <th class="font-weight-bold" width="5%">Tanda tangan</th>
               <th class="font-weight-bold" width="5%">Aksi</th>
            </tr>
         </thead>
      </table>
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
            [1, 'desc']
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
               "targets": [3, 7, 8]
            },
            {
               "className": "text-center",
               "targets": [7]
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
            title: 'Detail Tanda Tangan Surat Keluar',
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

      // ----------------- Signature -----------------//
      var modalSignature = function(opt) {
         opt = opt || {};
         app.modaler({
            title: 'Generate Nomor dan Tanda Tangan Surat Keluar',
            url: opt.url,
            footerVisible: false,
            size: "lg",
            bodyExtraClass: "form-type-round",
            onConfirm: opt.callback,
            backdrop: false,
         });
      }

      var requestSent = false;

      $('#datatables_ajax').on('click', '#signature-btn', function(e) {
         // e.preventDefault();
         var id = $(this).attr('data-id'),
            module = "<?php echo site_url($module . '/signature/') ?>" + id;
         modalSignature({
            url: module,
            callback: function(modal) {
               $frm = modal.find('form');
               $($frm[0]).on('submit', function(e) {
                  e.preventDefault();
                  var formData = new FormData(this);
                  $.ajax({
                     url: $(this).attr('action'),
                     type: 'POST',
                     data: formData,
                     contentType: false,
                     processData: false,

                     dataType: 'json',
                     success: function(result) {

                        if (result.error == 'null') {
                           $('#' + modal[0].id).modal('hide');
                           Swal.fire({
                              title: "Informasi",
                              text: result.text,
                              icon: result.type
                           });
                           dt.ajax.reload(null, false);
                        } else {
                           $.each(result.error, function(i, log) {
                              if (log != '') {
                                 $('[name="' + i + '"]').addClass('is-invalid');
                              }

                              $('.error_' + i).text(log);

                           });

                           Swal.fire({
                              title: "Informasi",
                              text: "Periksa Semua Form Inputan",
                              icon: "warning"
                           });

                        }
                     },
                     error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + errorThrown);
                     }
                  });
               });
            }
         });
      });
   });
</script>