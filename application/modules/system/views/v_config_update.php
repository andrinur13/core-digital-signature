<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="card card-bordered">
	<div class="card-header">
		<h4 class="card-title">Ubah Pengaturan "<?php echo $data->ConfigName;?>"</h4>
		<div class="btn-toolbar"></div>
	</div>


	<?php echo form_open($this->uri->uri_string(),  'class="form-horizontal" enctype="multipart/form-data" id="message-form" role="form"'); ?>
	<div class="card-body">
		<div class="form-row">
            <div class="form-group col-md-12">
                <label for="ConfigCode" class="col-form-label require">Config Code</label>
                <input name="ConfigCode" id="form-field-1-1 inputWarning readonly" readonly class="form-control <?php echo (form_error('ConfigCode')) ? 'is-invalid"' :''; ?>" type="text" value="<?php echo (isset($_POST['ConfigCode'])) ? set_value('ConfigCode') : $data->ConfigCode;?>" autocomplete="off" required>
                <?php echo (form_error('ConfigCode')) ? '<div class="invalid-feedback">'.form_error('ConfigCode').'</div>' :''; ?>
            </div>
        </div>
		<div class="form-row">
            <div class="form-group col-md-6">
                <label for="ConfigType" class="col-form-label require">Config Type</label>
                <select name="ConfigType" id="jenis" data-provide="selectpicker" class="form-control <?php echo (form_error('ConfigType')) ? 'is-invalid' :''; ?>" id="jenis-penilian" required>
				    <option value="">Pilih...</option>
					<?php
						$type = array('text' => 'Text','file' => 'File');
                        // dd($type);
						foreach($type as $i => $row){
							$selected = '';
							if($i == $data->ConfigType){
                                $selected = 'selected';
                            }elseif(isset($_POST['ConfigType'])){
								if($_POST['ConfigType'] == $i){
									$selected = 'selected';
								}
							}
							echo '<option value="'. $i .'" '. $selected .'>'. $row .'</option>';
						}
					?>
				</select>
                <?php echo (form_error('ConfigCode')) ? '<div class="invalid-feedback">'.form_error('ConfigCode').'</div>' :''; ?>
            </div>
			<div class="form-group col-md-6">
                <label for="ConfigValue" class="col-form-label require">Config Value</label>
				<div id="file-form" style="display:none;">
					<div class="input-group  file-group ">
						<input type="text" class="form-control file-value" value="<?php echo (isset($_POST['ConfigValue'])) ? set_value('ConfigValue') : $data->ConfigValue;?>" placeholder="Choose file...">
						<input type="file" name="ConfigValueFile">
						<span class="input-group-btn">
							<button class="btn btn-light file-browser" type="button"><i class="fa fa-upload"></i></button>
						</span> 
					</div>
					<?php
						$path = $this->config->item('upload_path').'pengaturan/';
						if(is_file($path.$data->ConfigValue)){
						?>
						<button type="button" class="btn btn-xs btn-info" onclick="show_file('<?php echo $data->ConfigValue ?>')"> <i class="fa fa-search "></i> Lihat File </button>
						<?php
						}else{
							echo '-- File Tidak Ditemukan --';
						}
					?>
					
				</div>
				<div id="text-form" style="display:none;">
					<input name="ConfigValueText"   id="inputWarning" class="form-control <?php echo (form_error('ConfigValue')) ? 'is-invalid"' :''; ?>" type="text" value="<?php echo (isset($_POST['ConfigValue'])) ? set_value('ConfigValue') : $data->ConfigValue;?>" autocomplete="off">
				</div>
                <?php echo (form_error('ConfigValue')) ? '<div class="invalid-feedback">'.form_error('ConfigValue').'</div>' :''; ?>
            </div>
        </div>

	</div>
	<div class="card-footer text-right">
        <button class="btn btn-label btn-bold btn-success" type="submit">Simpan <label><i class="ti-save"></i></label></button>
    </div>
	<?php echo form_close();?>
</div>




<!-- modal dokumen -->
<div class="modal modal-center fade" id="modalDokumen" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Lihat Dokumen</h5>
            <button type="button" class="close" data-dismiss="modal">
               <span aria-hidden="true">×</span>
            </button>
         </div>
         <div class="modal-body" id="modalWindowsBody">
            <div id="docList"></div>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-bold btn-secondary" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>

<script>

function show_file(filename) {
      var html = '';
      var filename = filename;
      var ext = filename.split('.');
      var file_type = ext[1];
      if (file_type == "pdf") {
         html += ' <embed src="<?php echo base_url($path); ?>' + filename + '" width="100%" height="500px" type="application/pdf">';

      } else {
         html += ' <img src="<?php echo base_url($path); ?>' + filename + '" width="100%">';
      }
      document.getElementById("docList").innerHTML = html;
      $('#modalDokumen').modal('show');
      // $('#modalDokumenTitle').text('Lihat Dokumen');
   };
   
    $(function() {
        

        var jenis = $('select[name=ConfigType] option').filter(':selected').val();

        if(jenis == 'text'){
            $("#file-form").hide();
            $("#text-form").show();
        }else if(jenis == 'file'){
            $("#file-form").show();
            $("#text-form").hide();
        }


		$("#jenis").change(function(){
			var value = $(this).val();
			if(value == 'text'){
				$("#file-form").hide();
				$("#text-form").show();
			}else if(value == 'file'){
				$("#file-form").show();
				$("#text-form").hide();
			}else{
				$("#file-form").hide();
				$("#text-form").hide();
			}
		});

    });
</script>