<div class="divider color-primary">Informasi Akun Pejabat &nbsp; | &nbsp;<a href="#" onclick="loadButton('cari-form')" class="text-info"> Cari Akun</a></small></div>
    <input type="hidden" name="user_pejabat" value="add_user_pejabat">        
    <div class="card card-bordered card-body">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="username" class="col-form-label require">Username</label>
                        <input name="username" id="form-field-1-1 inputWarning" class="form-control <?php echo (form_error('username')) ? 'is-invalid"' :''; ?>" type="text" value="<?php echo set_value('username');?>" autocomplete="off" required>
                        <?php echo validation_errors(); ?>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="email" class="col-form-label">Email</label>
                        <input name="email" id="form-field-1-1 inputWarning" class="form-control <?php echo (form_error('email')) ? 'is-invalid"' :''; ?>" type="email" value="<?php echo set_value('email');?>" autocomplete="off">
                        <?php echo validation_errors(); ?>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="password" class="col-form-label require">Password</label>
                        <input name="password" id="form-field-1-1 inputWarning" class="form-control <?php echo (form_error('password')) ? 'is-invalid"' :''; ?>" type="password" value="<?php echo set_value('password');?>" autocomplete="off" required>
                        <?php echo validation_errors(); ?>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="password_input_re" class="col-form-label require">Re-Password</label>
                        <input name="password_input_re" id="form-field-1-1 inputWarning" class="form-control <?php echo (form_error('password_input_re')) ? 'is-invalid"' :''; ?>" type="password" value="<?php echo set_value('password_input_re');?>" autocomplete="off" required>
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
                                        <input type="radio" value="<?=$un['UnitId'];?>" class="custom-control-input" name="unit_id">
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

                

                





                </div>
            </div>