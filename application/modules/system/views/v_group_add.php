<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		$(document).on('click', 'input[type=checkbox]', function () {
			/* $.uniform.restore("input[type=checkbox]"); */
			// children checkboxes depend on current checkbox
			$(this).next().find('input[type=checkbox]').prop('checked',this.checked);
			// go up the hierarchy - and check/uncheck depending on number of children checked/unchecked
			$(this).parents('ul').prev('input[type=checkbox]').prop('checked',function(){
				return $(this).next().find(':checked').length;
			});
			/* $("input[type=checkbox]").uniform(); */
		});
	});
</script>
<style>
	ul.checktree-root{
		list-style: none;
		padding: 5px;
	}

	ul#tree ul {
		list-style: none;
		padding: 0 25px;
	}

	ul.checktree-root li.action {
		display: inline;
		padding: 0px;
		margin-right: 10px;
	}
</style>
<div class="card card-outline-primary">
	<div class="card-header">
		<h4 class="card-title">
		<button class="btn btn-default btn-round" data-provide="tooltip" data-placement="top" title="" data-original-title="Kembali" onclick="history.back();"><i class="fa fa-arrow-left"></i></button>
		Tambah Data Group</h4>
	</div>
	<?php echo form_open_multipart($module);?>
  	<div class="card-body row">
  		<div class="col-md-8">
  			<div class="form-group">
	      	<label class="required">Nama Group</label>
	      	<input name="GroupName" id="GroupName" class="form-control" type="text" value="<?php echo set_value('GroupName');?>" autocomplete="off">
				<?php echo (form_error('GroupName')) ? '<i class="ace-icon fa fa-times-circle"></i>' :''; ?>
	    	</div>

	    	<div class="form-group">
	      	<label>Nama Group</label>
	      	<textarea  id="GroupDescription" name="GroupDescription" class="form-control"><?php echo set_value('GroupDescription');?></textarea>
				<?php echo (form_error('GroupDescription')) ? '<i class="ace-icon fa fa-times-circle"></i>' :''; ?>
	    	</div>
	    	<div class="form-group">
	      	<label>Menu Module</label>
	      	<div>
	      		<ul class="checktree-root" id="tree">
						<?php
						if(!is_null($group_menu)){
							$data_menu = NULL;
							$tempMenu = NULL;
							foreach($group_menu->result_array() as $row){
								if($row['MenuActionName'] == 'View'){
									if(is_null($row['MenuParentId']) OR $row['MenuParentId'] == 0){
										$data_menu[0][$row['MenuId']] = $row;
									} else {
										$data_menu[$row['MenuParentId']][$row['MenuId']] = $row;
									}
								}
							}

							if(!is_null($data_menu)){
								foreach($group_menu->result_array() as $row){
									if(is_null($row['MenuParentId']) OR $row['MenuParentId'] == 0){
										if(isset($data_menu[0][$row['MenuId']])){
											$data_menu[0][$row['MenuId']]['Action'][] = $row;
										}
									} else {
										if(isset($data_menu[$row['MenuParentId']][$row['MenuId']])){
											$data_menu[$row['MenuParentId']][$row['MenuId']]['Action'][] = $row;
										}
									}
								}
							}

							$post_data = NULL;
							if(isset($_POST['menu'])){
								$post_data = $_POST['menu'];
							}
							echo create_checkbox_menu($data_menu, 0, $post_data);
						}
						?>
					</ul>
	      	</div>
	      </div>
  		</div>

  	</div>

  	<footer class="card-footer">
   	<button type="submit" class="btn btn-round btn-info"><i class="fa fa-check"></i> Simpan </button>
		<a href="<?php echo site_url('system/group');?>" class="btn btn-round btn-secondary"><i class="fa fa-close"></i> Batal </a>
  	</footer>
</div>
</form>