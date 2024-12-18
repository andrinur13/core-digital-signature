<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script>
	jQuery(function($) {
		$("#unitParent").select2();
	});
</script>


<div class="card card-bordered">
	<div class="card-header">
		<h4 class="card-title"><strong>Ubah Unit</strong></h4>
      	<div class="btn-toolbar">
			<!-- <a id="add-btn" class="btn btn-round btn-label btn-bold btn-primary" data-original-title="Tambah data unit kerja." data-rel="tooltip" data-placement="bottom" href="#">
				Tambah Data
				<label><i class="ti-plus"></i></label>
			</a> -->
        </div>
	</div>
	<?php echo form_open($this->uri->uri_string(), ' class="form-horizontal form-bordered" id="form" role="form"'); ?>
	<div class="card-body">
		<div class="form-row">
			<div class="form-group col-md-12">
				<label for="unitParent" class="col-form-label require">Unit Parent</label><br>
				<select class="form-control" data-provide="selectpicker" data-live-search="true"  data-width="100%" name="unitParent" id="unitParent" class="<?php echo (form_error('unitParent')) ? 'is-invalid' :''; ?>">
					<option value="0">Unit Teratas</option>
					<?php foreach($dt_parent_unit->result() as $dt)
					{
						$selected = '';
						if(isset($_POST['unitParent'])){
							if($_POST['unitParent'] == $dt->UnitId){
								$selected = 'selected';
							}
						} else if($data_unit->UnitParent == $dt->UnitId){
							$selected = 'selected';
						}
					?>
						<option value="<?php echo $dt->UnitId ?>" <?php echo $selected;?> ><?= $dt->UnitName ?></option> 
					<?php 
					} 
					
					?>			
				</select>
				<!-- <span class="help-block">Merupakan bentuk hirearki susunan organisasi, silahkan pilih unit tertentu jika unit yang anda masukkan mempunyai sub diatasnya.</span> -->
				<?php echo form_error('unitParent'); ?>
			</div>
		</div>
		<div class="form-row">
				<div class="form-group col-md-6">
					<label for="unitKode" class="col-form-label">Kode Unit</label>
					<input type="text" class="form-control <?php echo (form_error('unitKode')) ? 'is-invalid' :''; ?>" id="unitKode" name="unitKode" value="<?php echo (isset($_POST['unitKode'])) ? set_value('unitKode') : $data_unit->UnitKode;?>">
					<?php echo form_error('unitKode'); ?>
				</div>
				<div class="form-group col-md-6">
					<label for="unitNama" class="col-form-label">Nama Unit</label>
					<input type="text" class="form-control <?php echo (form_error('unitNama')) ? 'is-invalid' :''; ?>" id="unitNama" name="unitNama" value="<?php echo (isset($_POST['unitNama'])) ? set_value('unitNama') : $data_unit->UnitName;?>">
					<?php echo form_error('unitNama'); ?>
				</div>
		</div>
	</div>
    <div class="card-footer">
		<div class="text-right">
				<button type="submit" class="btn btn-label btn-bold btn-primary">Simpan<label><i class="fa fa-send"></i></label></button>
		</div>
   </div>

	<?=form_close();?>
</div>