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
      <h4 class="card-title"><strong>Data Disposisi Keluar</strong></h4>
      <div class="btn-toolbar">
      </div>
   </div>

   <div class="card-body" id="tbl-container">
      <form class="" method="POST" action="" id="frmFilter">
      </form>

      <table class="table table-separated table-striped tab" cellspacing="0" id="datatables_ajax">
         <thead class="bg-color-primary1">
            <tr>
               <th class="font-weight-bold">No.</th>
               <th class="font-weight-bold" width="15%">Tanggal Disposisi</th>
               <th class="font-weight-bold" width="15%">No. Surat</th>
               <th class="font-weight-bold" width="15%">Sifat Urgensi</th>
               <th class="font-weight-bold">Perihal</th>
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
               "targets": [5]
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
            title: 'Detail Disposisi Keluar',
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
   });
</script>