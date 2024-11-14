<div class="card box card-round card-shadowed px-50 py-30 w-400px mb-0" style="max-width: 100%">
      <h5 class="text-uppercase text-center"><?php echo config_item('app_title') ?></h5>
      <br>
      <?php echo form_open(site_url('auth/login'), ' class="form-type-material" id="form" role="form"'); ?>
      <div class="input-group">
         <span class="input-group-addon" id="basic-addon1"><i class="ti-user"></i></span>
         <div class="input-group-input">
            <input type="text" class="form-control" id="username" value="<?php echo set_value('login');?>" autocomplete="off" accesskey="n" name="login">
            <label>Username</label>
         </div>
      </div>
      <div class="input-group">
         <span class="input-group-addon" id="basic-addon1"><i class="ti-key"></i></span>
         <div class="input-group-input">
            <input type="password" class="form-control" id="password" name="password" value="" autocomplete="off">
            <label>Password</label>
         </div>
      </div>
      <?php 
         if(isset($show_captcha)) {
            if($show_captcha) {
               if ($use_recaptcha) {
      ?>
      <div id="recaptcha_image"> </div>
      <label class="block">
         <?php echo form_error('_check_recaptcha'); ?>
         <?php echo $recaptcha_html; ?>
      </label>
      <?php } else { ?>
      <p class="text-center mt-10"><?php echo $captcha_html; ?></p>
      <div class="form-group">
         <input type="text" class="form-control text-center <?php echo (form_error('captcha') OR isset($errors['captcha'])) ? 'is-invalid' :''; ?>" name="captcha" autocomplete="off">
         <label class="text-center">Kode Keamanan</label>
         <small class="form-text">Silahkan masukkan kode keamanan yang terlihat pada gambar.</small>
         <div class="invalid-feedback"><?php echo form_error('captcha'); ?></div>
      </div>
      <?php 
            }
         }
      }
      ?>

        <div class="form-group flexbox">
          <label class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input">
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">Remember me</span>
          </label>

          <a class="text-muted hover-primary fs-13" href="#">Forgot password?</a>
        </div>

        <div class="form-group">
          <button class="btn btn-bold btn-block btn-primary" type="submit">Login</button>
        </div>
      </form>

      <p class="text-center text-muted fs-13 mt-20">Belum Memiliki Akun? <a class="text-primary fw-500" href="#">Hubungi Administrator</a></p>
</div>