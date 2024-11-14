<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<?php if ($this->session->flashdata('msg_method')) {
   $msg = $this->session->flashdata('msg_method');
?>
<div class="alert alert-<?php echo $msg['type'];?> alert-dismissible fade show" role="alert">
   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
     <span aria-hidden="true">&times;</span>
   </button>
   <i class="fa fa-<?php echo $msg['status'] ? 'check' : 'warning'; ?>"></i> <?php echo $msg['text'];?>
 </div>
<?php } ?>

<div class="card card-outline-primary">
   <div class="card-header text-right">
      <h4 class="card-title"><strong>Data Menu Module</strong></h4>
      <div class="btn-toolbar">
         <a id="add-btn" class="btn btn-round btn-label btn-bold btn-primary" href="<?php echo site_url($module . '/add_method/'.$menu_id) ?>">
            Tambah Method
            <label><i class="ti-plus"></i></label>
         </a>
        </div>
   </div>

   <div class="card-body">
      <table class="table table-separated table-striped tab" cellspacing="0" id="datatables_ajax">
         <thead class="bg-color-primary1">
            <tr>
               <th>Name</th>
               <th>Method</th>
               <th>Action Segmen</th>
               <th width="10%"></th>
            </tr>
         </thead>
      </table>
   </div>
</div>

<script type="text/javascript">
$(function() {
   var dt = $('#datatables_ajax').DataTable({
      "processing": false,
      "serverSide": true,
      "ajax" : {
         "url" : "<?php echo site_url($module . '/datatables_method_list/'.$menu_id) ?>",
         "type" : "POST",
         "data" : function(d) {
            //d.order_asesiNama = "sutri";
            //$.each(ajaxParams, function(key, value) {
               //d[key] = value;
            //});
         }
      },
      'drawCallback': function( settings ) {
         $('[data-provide="tooltip"]').tooltip();
      },
      "dom": "<'row'<'col-md-4 col-sm-12'l<'table-group-actions pull-right'>>r><'table-scrollable't><'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'p>>",
      'language' : {
         'search': 'Cari',
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
      'order': [[ 0, 'asc' ]],
      'columnDefs': [
         {"orderable": false, "searchable": false, "className":"text-right table-actions", "targets": [3]}
      ]
  });

   $('#add-btn').on('click', function(e) {
      e.preventDefault();
      var action = $(this).attr('href');
   
      app.modaler({
         title: 'Add Method',
         url: action,
         footerVisible: false
      });
   });

   $('#datatables_ajax').on('click', '#update', function(e) {
      e.preventDefault();
      var action = $(this).attr('href');
   
      app.modaler({
         title: 'Update Method',
         url: action,
         footerVisible: false
      });
   });

   $('#datatables_ajax').on('click', '#delete', function(e) {
      e.preventDefault();
      var action = $(this).attr('href');
   
      app.modaler({
         html: 'Apakah Anda yakin ingin menghapus data ini?',
         title: 'Delete Method',
         cancelVisible: true,
         confirmText: 'YA',
         cancelText:'Tidak',
         cancelClass: 'btn btn-w-sm btn-secondary',
         confirmClass: 'btn btn-w-sm btn-danger',
         onConfirm: function() {
            window.location.href = action;
         }
      });
   });

});
</script>

