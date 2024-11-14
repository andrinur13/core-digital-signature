<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<style>
.autocomplete {
}

#searchInput {

}

#searchInput:hover {
    /* border-color: #3498db; */
}

.ui-autocomplete {
    position: absolute;
    width: 80%; /* Mengatur lebar kotak daftar menjadi 80% */
    max-height: 200px;
    overflow-y: auto;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    z-index: 999;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    left: 0;
    margin-top: 5px; /* Jarak antara input dan daftar autocomplete */
    padding: 0;
}

.ui-autocomplete li {
    list-style-type: none;
    padding: 10px;
    cursor: pointer;
    transition: background-color 0.3s;
    text-align: left;
}

.ui-autocomplete li:hover {
    background-color: #f9f9f9;
}

.highlight {
    background-color: yellow; /* Warna latar belakang teks yang di-highlight */
}
    </style>
<div class="card card-outline-primary">
	<div class="card-header">
		<h4 class="card-title"><?= $template['title']; ?></h4>
		<div class="btn-toolbar"></div>
	</div>

	<?php echo form_open($this->uri->uri_string(),  'class="form-horizontal " id="message-form" role="form"'); ?>
		<div class="card-body form-type-round"">
            <div class="form-row">
				<div class="form-group col-md-12">
					<label for="pjbNipm" class="col-form-label require">NIPM</label>
					<input name="pjbNipm" id="form-field-1-1 inputWarning" required class="form-control <?php echo (form_error('pjbNipm')) ? 'is-invalid"' :''; ?>" type="text" value="<?php echo set_value('pjbNipm');?>" autocomplete="off">
					<?php echo (form_error('pjbNipm')) ? '<div class="invalid-feedback">'.form_error('pjbNipm').'</div>' :''; ?>
				</div>  
		    </div>
			<div class="form-row">
				<div class="form-group col-md-6">
					<label for="pjbtKode" class="col-form-label require">Kode</label>
					<input name="pjbtKode" id="form-field-1-1 inputWarning" class="form-control <?php echo (form_error('pjbtKode')) ? 'is-invalid"' :''; ?>" type="text" value="<?php echo set_value('pjbtKode');?>" autocomplete="off" required>
					<?php echo (form_error('pjbtKode')) ? '<div class="invalid-feedback">'.form_error('pjbtKode').'</div>' :''; ?>
				</div>  
				<div class="form-group col-md-6">
					<label for="pjbtNama" class="col-form-label require">Pejabat</label>
					<input name="pjbtNama" id="form-field-1-1 inputWarning" class="form-control <?php echo (form_error('pjbtNama')) ? 'is-invalid"' :''; ?>" type="text" value="<?php echo set_value('pjbtNama');?>" autocomplete="off" required>
					<?php echo (form_error('pjbtNama')) ? '<div class="invalid-feedback">'.form_error('pjbtNama').'</div>' :''; ?>
				</div>  
			</div>

			<div class="form-row">
				<div class="form-group col-md-12">
					<label for="pjbtJabatan" class="col-form-label">Jabatan</label>
					<input name="pjbtJabatan" id="form-field-1-1 inputWarning" class="form-control <?php echo (form_error('pjbtJabatan')) ? 'is-invalid"' :''; ?>" type="text" value="<?php echo set_value('pjbtJabatan');?>" autocomplete="off">
					<?php echo (form_error('pjbtJabatan')) ? '<div class="invalid-feedback">'.form_error('pjbtJabatan').'</div>' :''; ?>
				</div>  
		    </div>


            <div id="viewContainer">
                <div class="divider color-primary">Informasi Akun Pejabat &nbsp; | &nbsp;<a href="#" onclick="loadButton('cari-form')" class="text-info"> Cari Akun</a></small></div>
                <div id="loadingIndicator" class="text-center" style="display: none;">
                    Loading...
                </div>
            </div>
        
		<div class="card-footer text-right">
			<button class="btn btn-label btn-bold btn-success" type="submit">Simpan <label><i class="ti-save"></i></label></button>
		</div>
	<?php echo form_close();?>

</div>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>

        function loadButton(text) {
                $("#loadingIndicator").show();
                $.ajax({
                    url: '<?php echo site_url($module.'/ajax/load_view'); ?>',
                    type: 'POST',
                    data: {
                        id: text,
                    },
                    success: function(response){
                        $('#viewContainer').html(response);
                        $("#loadingIndicator").hide();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
        }

        $(document).ready(function(){
            $("#loadingIndicator").show();
            $.ajax({
                url: '<?php echo site_url($module.'/ajax/load_view'); ?>',
                type: 'POST',
                data: { id: 'buat-form' },
                success: function(response){
                    $('#viewContainer').html(response);
                    $("#loadingIndicator").hide();
                },
                error: function(xhr, status, error){
                    $("#loadingIndicator").show();
                    console.log('Error:', error);
                }
            });
        });
    </script>


						