<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<?php if ($this->session->flashdata('msg_register')) {
	$msg = $this->session->flashdata('msg_register');
?>
<div class="callout callout-<?php echo $msg['type'];?>" role="alert">
  <button type="button" class="close" data-dismiss="callout" aria-label="Close">
    <span>×</span>
  </button>
  <h5><?php echo $msg['title'];?></h5>
  <p><?php echo $msg['text'];?></p>
</div>

<?php } ?>
<div class="card card-outline-primary">
	<div class="card-header">
		<h4 class="card-title"><strong>Data Pengguna</strong></h4>
      <div class="btn-toolbar">
         <a id="add-btn" class="btn btn-round btn-label btn-bold btn-primary" href="<?php echo site_url($module.'/add')?>" title="Tambah Data User" data-provide="tooltip">
            Tambah User
            <label><i class="ti-plus"></i></label>
         </a>
          
        </div>
		</a>
	</div>

 	<div class="card-body" id="tbl-container">
 		<table class="table table-separated table-striped tab" cellspacing="0" id="datatables_ajax">
       <thead class="bg-color-primary1">
          	<tr>
               <th>No.</th>
               <th>ID</th>
            	<th>Username</th>
            	<th>Unit Kerja</th>
            	<th>Name</th>
            	<th>Group</th>
            	<th>Role</th>
               <th>Is Aktif?</th>
            	<th>Pilihan</th>
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
      <?php $attributes = array('class' => 'form-horizontal','id' => 'form_dell');
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
      o = '<div id="' + e + '" class="custom-alerts alert alert-dismissible alert-' + t.type + ' fade show">' + (t.close ? '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' : '') + '<i class="fa fa-'+ t.icon +'"></i> ' + t.message + '</div>';
      return $(t.container).prepend(o), $(t.container).focus(); 
   }
   var dt = $('#datatables_ajax').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax" : {
         "url" : "<?php echo site_url($module . '/datatables_user/') ?>",
         "type" : "POST"
      },
      'drawCallback': function( settings ) {
         $('[data-provide="tooltip"]').tooltip();
      },
      //"dom": "<'row'<'col-md-4 col-sm-12'l<'table-group-actions pull-right'>>r><'table-scrollable't><'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'p>>",
      'language' : {
         'search': 'Cari',
         'searchPlaceholder':'Username / Name',
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
      'order': [[ 1, 'asc' ]],
      'columnDefs': [
         {"visible": false, "targets":[1]},
         {"orderable": false, "searchable": false, "targets": [0]},
         {"orderable": false, "searchable": false, "className":"table-actions", "targets": [7]}
      ]
   });

   dt.on( 'order.dt search.dt', function () {
        dt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        });
   }).draw();

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
            $.get(action, function (result) {
               var status = (result.status == true) ? 'success' : 'danger';
               modalAlert({type:status, message:result.msg});
               dt.ajax.reload();
            }, "json");
         }
      });
   });

});
</script>