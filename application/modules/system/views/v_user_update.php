<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="card card-bordered">
	<div class="card-header">
        <h4 class="card-title">Tambah Data Pengguna</h4>
        <div class="btn-toolbar"></div>
    </div>

	<?php echo form_open_multipart($this->uri->uri_string());?>
	<div class="card-body">
		<div class="form-row">
            <div class="form-group col-md-4">
                <label for="username" class="col-form-label require">Username</label>
                <input name="username" id="form-field-1-1 inputWarning" class="form-control <?php echo (form_error('username')) ? 'is-invalid"' :''; ?>" type="text" value="<?php echo (isset($_POST['username'])) ? set_value('username') : $data_user[0]['UserName'];?>" autocomplete="off" required>
                <?php echo validation_errors(); ?>
            </div>
            <div class="form-group col-md-4">
                <label for="nama" class="col-form-label require">Nama Lengkap</label>
                <input name="nama" id="form-field-1-1 inputWarning" class="form-control <?php echo (form_error('nama')) ? 'is-invalid"' :''; ?>" type="text" value="<?php echo (isset($_POST['nama'])) ? set_value('nama') : $data_user[0]['UserRealName'];?>" autocomplete="off" required>
                <?php echo validation_errors(); ?>
            </div>
            <div class="form-group col-md-4">
                <label for="email" class="col-form-label">Email</label>
                <input name="email" id="form-field-1-1 inputWarning" class="form-control <?php echo (form_error('email')) ? 'is-invalid"' :''; ?>" type="email" value="<?php echo (isset($_POST['email'])) ? set_value('email') : $data_user[0]['UserEmail'];?>" autocomplete="off">
                <?php echo validation_errors(); ?>
            </div>
        </div>
		
		<label for="group" class="col-form-label require">Group Pengguna ?</label>
		<div class="form-row">
			<?php foreach ($group->result() as $grp) {?>
			<div class="form-group col-md-2">
				<div class="custom-controls-stacked">
					<label class="custom-control custom-checkbox">
						<input value="<?=$grp->GroupId;?>" name="group[]" 
                  <?php foreach ($user_group as $ug) {
                        if ($grp->GroupId==$ug->GroupId) {
                           echo 'checked="checked"';
                        }
                     }?>
                  type="checkbox" class="custom-control-input">
						<span class="custom-control-indicator"></span>
						<span class="custom-control-description"><?=$grp->GroupName;?></span>
					</label>

					<label class="custom-control custom-radio">
						<input type="radio" value="<?=$grp->GroupId;?>" 
                  <?php foreach ($user_group as $ug) {
                        if ($grp->GroupId==$ug->GroupId && $ug->UserGroupIsDefault=='Ya') {
                           echo 'checked="checked"';
                        }
                     }?>
                  class="custom-control-input" name="isdefault">
						<span class="custom-control-indicator"></span>
						<span class="custom-control-description">isDefault?</span>
					</label>
				</div>
				<br>
			</div>
			<?php } ?>
		</div>
        <label for="group" class="col-form-label require">Role Pengguna ?</label>
		<div class="form-row">
			<?php foreach ($role as $rl) {
               
                ?>
			<div class="form-group col-md-2">
				<div class="custom-controls-stacked">

					<label class="custom-control custom-radio">
						<input type="radio" value="<?=$rl->roleId;?>" 
                        <?php
                         if($data_user[0]['UserRoleId'] == $rl->roleId){
                            echo 'checked="checked"';
                        }
                        ?>
                        class="custom-control-input" name="isRoleUser">
						<span class="custom-control-indicator"></span>
						<span class="custom-control-description"><?=$rl->roleNama;?></span>
					</label>
				</div>
				<br>
			</div>
			<?php } ?>
		</div>

		<div class="form-row">
            <div class="form-group col-md-6">
                <label for="password_input" class="col-form-label">Password</label>
                <input name="password_input" id="form-field-1-1 inputWarning" class="form-control <?php echo (form_error('password_input')) ? 'is-invalid"' :''; ?>" type="password" value="<?php echo set_value('password_input');?>" autocomplete="off">
                <p><small>Kosongkan jika tidak ingin memperbarui Password.</small></p>
                <?php echo validation_errors(); ?>
            </div>
            <div class="form-group col-md-6">
                <label for="password_input_re" class="col-form-label">Re-Password</label>
                <input name="password_input_re" id="form-field-1-1 inputWarning" class="form-control <?php echo (form_error('password_input_re')) ? 'is-invalid"' :''; ?>" type="password" value="<?php echo set_value('password_input_re');?>" autocomplete="off">
                <?php echo validation_errors(); ?>
            </div>
        </div>
		<div class="divider">Unit Kerja</div>
        <table class="table table-bordered" width="100%">
		<thead>
            <tr>
                <th class="text-center" width="2%">Pilih</th>
                <th class="text-center">Kode Unit Kerja</th>
                <th class="text-center">Unit Kerja</th>
            </tr>
        </thead>
        <tbody>
			<?php
				foreach($unit_kerja as $un){
			?>
				<tr>
					
					<td class="text-center">
						<center>
							<div class="custom-controls-stacked">
							<label class="custom-control custom-radio">
								<input type="radio" value="<?=$un['UnitId'];?>" <?= ($un['UnitId'] == $data_user[0]['UserUnitId']) ? 'checked' : ''; ?> class="custom-control-input" name="unit_id">
								<span class="custom-control-indicator"></span>
								<span class="custom-control-description"></span>
							</label>
							</div>
						</center>
					</td>
					<td class="text-center"><?=$un['UnitKode'];?></td>
					<td><?=$un['UnitName'];?></td>
				</tr>
			<?php } ?>
		</tbody>
		</table>
		
		<div class="form-row">
            <div class="form-group col-md-6">
                <label>User isActive?</label>
				<div class="custom-controls-stacked">
					<label class="custom-control custom-checkbox">
					<input name="isactive" type="checkbox" <?php echo ($data_user[0]['UserIsActive'] == '1') ? 'checked="checked"' : '';?>  class="custom-control-input">
					<span class="custom-control-indicator"></span>
					<span class="custom-control-description">Active</span>
					</label>
				</div>
            </div>
           
        </div>
   </div>
   <div class="card-footer text-right">
        <button class="btn btn-label btn-bold btn-success" type="submit">Simpan <label><i class="ti-save"></i></label></button>
    </div>
   <?= form_close(); ?>
</div>
