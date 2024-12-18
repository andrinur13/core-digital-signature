<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript" class="init">
	jQuery(document).ready(function() { 
		$(document.body).on("click", "#add-btn",function(event){
			window.location.replace("<?php echo site_url($module . '/add');?>");
		});
		
		$('[data-rel=tooltip]').tooltip({container:'body'});
		
		$('#t-unit-kerja').treetable({
			expandable: true,
			initialState: 'expanded'
		});
		$(document.body).on("click", "#delete-btn",function(event){ 
			var title = $(this).attr("data-original-title");
			var link = $(this).attr("href");
			bootbox.confirm({ 
					message: "Apakah anda ingin men-" + title + " ?",
					backdrop:true,
					callback: function(result){
						if(result === true) {
								window.location.replace(link);
							}
					}
			});
			return false;
		});
	} );
</script>

<?php if ($this->session->flashdata('message_form')) {
	$msg = $this->session->flashdata('message_form');
?>
<div class="callout callout-<?php echo $msg['status'];?>" role="alert">
  <button type="button" class="close" data-dismiss="callout" aria-label="Close">
    <span>×</span>
  </button>
  <h5><?php echo $msg['title'];?></h5>
  <p><?php echo $msg['message'];?></p>
</div>

<?php } ?>

<div class="card card-bordered">
	<div class="card-header">
		<h4 class="card-title"><strong>Data Unit</strong></h4>
      	<div class="btn-toolbar">
			<a id="add-btn" class="btn btn-custom" data-original-title="Tambah data unit kerja." data-rel="tooltip" data-placement="bottom" href="#">
				Tambah Data +
			</a>
        </div>
	</div>
	<div class="card-body">
		<table id="t-unit-kerja" class="table table-separated table-striped tab">
			<thead class="bg-color-primary1">
				<tr>
					<th>Kode Unit</th>
					<th>Nama Unit</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
				<!-- <tr>
					<td colspan="4" class="dataTables_empty">Loading data from server</td>
				</tr> -->
				<?php 
					$no = 1; 
					foreach ( $dt_unit->result_array() as $dt ){
						$unit[$dt['UnitParent']][] = $dt;
					}
					echo createParentTree($unit, 0, 0, $module);
				?>
			</tbody>
		</table>
	</div>
</div>