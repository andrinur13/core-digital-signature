<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="col-lg-5 d-flex align-items-center justify-content-lg-end justify-content-center">
    <div class="animated fadeInLeft" id="animationSandbox">
        <div class="row justify-content-center">
            <div class="col-lg-12">
            <div class="justify-content-center align-items-center">
                <img class="mb-4 w-50 d-block mx-auto" src="<?= asset_path('/img/logo-eoffice.svg', '_theme_') ?>">
            </div>
            <p class="text-login d-flex justify-content-center"><?php echo config_item('app_name') ?></p>
            <p class="text-center"><?php echo config_item('app_title') ?></p>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-6 d-flex align-items-center justify-content-lg-start justify-content-center">
              <div class="animated fadeInUp" id="animationSandbox">
                  <div class="card card-login card-shadowed px-30 py-30 ">
                      <p class="text2 py-4 text-center">Sign in</p>
                        
                        <?php echo form_open(site_url('auth/login'), ' class="form-type-round" id="form" role="form"'); ?>
                          <div class="form-group">
                            <input type="text" class="form-control" id="username" value="<?php echo set_value('login');?>" autocomplete="off" accesskey="n" name="login">
                            <?php echo (form_error('login') OR isset($errors['login'])) ? '<i class="ace-icon fa fa-times-circle"></i>' :''; ?>
                            <?php echo form_error('login'); ?>
                            <?php echo isset($errors['login'])? '<div class="help-block col-xs-12 col-sm-reset inline">' . $errors['login'] . '</div>':''; ?>
                          </div>
                          <div class="form-group">
                                <input type="password" class="form-control" id="password" name="password" value="" autocomplete="off">
                                <?php echo (form_error('password') OR isset($errors['password'])) ? '<i class="ace-icon fa fa-times-circle"></i>' :''; ?>
                                <?php echo form_error('password'); ?>
                                <?php echo isset($errors['password'])? '<div class="help-block col-xs-12 col-sm-reset inline">' . $errors['password'] . '</div>':''; ?>
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
                                    <input type="checkbox" class="custom-control-input" name="remember" >
                                    <span class="custom-control-indicator"></span>
                                    <span class="custom-control-description">Ingatkan Saya ?</span>
                                </label>

                                <?php 
                                    if ($this->config->item('auth.allow_forgot_password')){
                                ?>
                                    <a class="text-muted hover-primary fs-13" href="<?php echo site_url('auth/forgot_password/');?>">Lupa Kata Sandi?</a>
                                <?php
                                    } 
                                ?>
                          <hr class="w-30px">
                          <div class="form-group">
                            <button class="btn btn-round btn-block btn-custom" type="submit">Login</button>
                          </div>
                        <?php echo form_close();?>
                  </div>
              </div>
          </div>