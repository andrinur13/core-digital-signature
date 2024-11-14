<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript">
$(function() {
	$(document.body).on("click", "#add-btn",function(event){
		window.location.replace("<?php echo site_url($module . '/add');?>");
	});
})
</script>

<?php if ($this->session->flashdata('message_form')) {
	$msg = $this->session->flashdata('message_form');
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
		<h4 class="card-title">Data Grup Pengguna</h4>
		<a id="add-btn" class="btn btn-round btn-label btn-bold btn-primary" href="#">
			Tambah
			<label><i class="ti-plus"></i></label>
		</a>
	</div>

 	<div class="card-body">
 		<table class="table table-separated table-striped tab" cellspacing="0"  id="datatables_ajax">
         <thead class="bg-color-primary1">
          	<tr>
               <th>ID</th>
            	<th width="25%;">Nama Group</th>
					<th width="">Deskripsi</th>
            	<th width="10%;">Action</th>
          	</tr>
        	</thead>
      </table>
	</div>
</div>

<script type="text/javascript">
$(function() {
   var dt = $('#datatables_ajax').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax" : {
         "url" : "<?php echo site_url($module . '/datatables_group/') ?>",
         "type" : "POST"
      },
      'drawCallback': function( settings ) {
         $('[data-provide="tooltip"]').tooltip();
      },
      //"dom": "<'row'<'col-md-4 col-sm-12'l<'table-group-actions pull-right'>>r><'table-scrollable't><'row'<'col-md-8 col-sm-12'i><'col-md-4 col-sm-12'p>>",
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
         {"visible": false, "searchable": false, "targets":[0]},
         {"orderable": false, "searchable": false, "className":"table-actions", "targets": [3]}
      ]
   });

   /*dt.on( 'order.dt search.dt', function () {
        dt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        });
   }).draw();*/
});
</script>