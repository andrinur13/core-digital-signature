<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
|--------------------------------------------------------------------
| !SITE
|--------------------------------------------------------------------
*/
$config['app_name'] = '<span class="text-success">UAD</span><span class="text-warning">Sign</span>';
$config['app_title'] = 'Sistem Informasi Tanda Tangan Digital Universitas Ahmad Dahlan';
$config['app_status'] = TRUE;		// 0 = offline, 1 = online
$config['app_set_cipher'] = 'MCRYPT_GOST';
$config['app_set_mode'] = 'MCRYPT_MODE_CFB';
$config['app_encrypt_mode'] = TRUE;		// 0 = false, 1 = true

/* debug query db */
$config['app_debug'] = false;	// 0 = false, 1 = true
$config['app_default_controller'] = 'auth/login';
$config['app_default_backend_controller'] = 'dashboard';

/*
|--------------------------------------------------------------------------
| SSO Portal
| Konfigurasi Untuk Login With Portal
|--------------------------------------------------------------------------
*/

$config['service_portal']   = 'http://172.10.27.44/index.php?d=dokma&c=dokma&m=login_portal';
$config['key_portal']       = '1e767abedb24fa0721bb584f81b697e7c82dae56'; // portal = 952b70ffd38d5973b53306fc96c1212a6c8617cf
/*
|--------------------------------------------------------------------------
| SSO API LIBRARY
| Konfigurasi Untuk Login With Portal
|--------------------------------------------------------------------------
*/

// Konfigurasi Group ID Kantor Universitas dan Unit Kerja -- Untuk Validasi Controller dan Menu yang Dijalankan
$config['group_id_kantor'] = '1';
$config['group_id_unit_kerja'] = '2';



/*
|--------------------------------------------------------------------------
| Database settings
| property prefix for system only on database table 
|  
|--------------------------------------------------------------------------
*/
$config['app_db_table_prefix'] = 'sys_';

/*
|--------------------------------------------------------------------------
| Security settings
|
| The library uses PasswordHash library for operating with hashed passwords.
| 'phpass_hash_portable' = Can passwords be dumped and exported to another server. If set to FALSE then you won't be able to use this database on another server.
| 'phpass_hash_strength' = Password hash strength.
|--------------------------------------------------------------------------
*/
$config['auth_phpass_hash_portable'] = FALSE;
$config['auth_phpass_hash_strength'] = 8;

/*
|--------------------------------------------------------------------------
| Login settings
| 'login_record_ip' = Save in database user IP address on user login.
| 'login_record_time' = Save in database current time on user login.
|
| 'login_count_attempts' = Count failed login attempts.
| 'login_max_attempts' = Number of failed login attempts before CAPTCHA will be shown.
| 'login_attempt_expire' = Time to live for every attempt to login. Default is 24 hours (60*60*24).
| 'username_min_length' = Min length of user's username.
| 'username_max_length' = Max length of user's username.
| 'password_min_length' = Min length of user's password.
| 'password_max_length' = Max length of user's password.
|--------------------------------------------------------------------------
*/
$config['auth_login_by_username'] = TRUE;
$config['auth_login_by_email'] = TRUE;
$config['auth_login_record_ip'] = TRUE;
$config['auth_login_record_time'] = TRUE;
$config['auth_login_count_attempts'] = TRUE;
$config['auth_login_max_attempts'] = 5;
$config['auth_login_attempt_expire'] = 60 * 60 * 24;

$config['auth_username_min_length'] = 6;
$config['auth_username_max_length'] = 20;
$config['auth_password_min_length'] = 6;
$config['auth_password_max_length'] = 20;

/*
|--------------------------------------------------------------------------
| Auto login settings
|
| 'autologin_cookie_name' = Auto login cookie name.
| 'autologin_cookie_life' = Auto login cookie life before expired. Default is 2 months (60*60*24*31*2).
|--------------------------------------------------------------------------
*/
$config['auth_autologin_cookie_name'] = 'autologin';
$config['auth_autologin_cookie_life'] = 60 * 60 * 24 * 31 * 2;

/*
|--------------------------------------------------------------------------
| Captcha
|
| You can set captcha that created by Auth library in here.
| 'captcha_path' = Directory where the catpcha will be created.
| 'captcha_fonts_path' = Font in this directory will be used when creating captcha.
| 'captcha_font_size' = Font size when writing text to captcha. Leave blank for random font size.
| 'captcha_grid' = Show grid in created captcha.
| 'captcha_expire' = Life time of created captcha before expired, default is 3 minutes (180 seconds).
| 'captcha_case_sensitive' = Captcha case sensitive or not.
|--------------------------------------------------------------------------
*/
$config['auth_captcha_path'] = 'assets/captcha/';
$config['auth_captcha_fonts_path'] = 'assets/captcha/fonts/2.ttf';
$config['auth_captcha_width'] = 200;
$config['auth_captcha_height'] = 50;
$config['auth_captcha_font_size'] = 14;
$config['auth_captcha_grid'] = FALSE;
$config['auth_captcha_expire'] = 180;
$config['auth_captcha_case_sensitive'] = FALSE;

/*
|--------------------------------------------------------------------------
| reCAPTCHA
|
| 'use_recaptcha' = Use reCAPTCHA instead of common captcha
| You can get reCAPTCHA keys by registering at http://recaptcha.net
|--------------------------------------------------------------------------
*/
$config['auth_use_recaptcha'] = FALSE;
$config['auth_recaptcha_public_key'] = '';
$config['auth_recaptcha_private_key'] = '';


/*
|--------------------------------------------------------------------------
| Cache application
|--------------------------------------------------------------------------
*/
$config['sys_cache_dir'] = APPPATH . 'cache/';
$config['sys_cache_default_expires'] = 0;


/*
|--------------------------------------------------------------------------
| Template Parser Enabled
|--------------------------------------------------------------------------
|
| Should the Parser library be used for the entire page?
|
| Can be overridden with $this->template->enable_parser(TRUE/FALSE);
|
|   Default: TRUE
|
*/

$config['parser_enabled'] = FALSE;

/*
|--------------------------------------------------------------------------
| Template Parser Enabled for Body
|--------------------------------------------------------------------------
|
| If the parser is enabled, do you want it to parse the body or not?
|
| Can be overridden with $this->template->enable_parser(TRUE/FALSE);
|
|   Default: FALSE
|
*/

$config['parser_body_enabled'] = FALSE;

/*
|--------------------------------------------------------------------------
| Template Title Separator
|--------------------------------------------------------------------------
|
| What string should be used to separate title segments sent via $this->template->title('Foo', 'Bar');
|
|   Default: ' | '
|
*/

$config['title_separator'] = ' : ';

/*
|--------------------------------------------------------------------------
| Template Theme
|--------------------------------------------------------------------------
|
| Which theme to use by default?
|
| Can be overriden with $this->template->set_theme('foo');
|
|   Default: ''
|
*/

$config['theme'] = 'theadmin';

/*
|--------------------------------------------------------------------------
| Theme
|--------------------------------------------------------------------------
|
| Where should we expect to see themes?
|
|	Default: array(APPPATH.'themes/' => '../themes/')
|
*/

$config['theme_locations'] = array(
	FCPATH . 'themes/'
);

/*
|--------------------------------------------------------------------------
| Asset Directory
|--------------------------------------------------------------------------
|
| Absolute path from the webroot to your CodeIgniter root. Typically this will be your APPPATH,
| WITH a trailing slash:
|
|	/assets/
|
*/
// print_r(APPPATH_URI);
$config['asset_dir'] = config_item('base_url') . 'assets/';

/*
|--------------------------------------------------------------------------
| Asset URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|	/assets/
|
*/

$config['asset_url'] = config_item('base_url') . 'assets/';

/*
|--------------------------------------------------------------------------
| Theme Asset Directory
|--------------------------------------------------------------------------
|
*/

/* $config['theme_asset_dir'] = APPPATH_URI . 'themes/'; ORIGINAL */
$config['theme_asset_dir'] = 'themes/';

/*
|--------------------------------------------------------------------------
| Theme Asset URL
|--------------------------------------------------------------------------
|
*/

$config['theme_asset_url'] = config_item('base_url') . 'themes/';

/*
|--------------------------------------------------------------------------
| Asset Sub-folders
|--------------------------------------------------------------------------
|
| Names for the img, js and css folders. Can be renamed to anything
|
|	/assets/
|
*/
$config['asset_img_dir'] = 'img';
$config['asset_js_dir'] = 'js';
$config['asset_css_dir'] = 'css';

$config['cas_login_enable'] = FALSE;
$config['cas_server_url'] = 'https://sso.uad.ac.id/cas/';
$config['cas_phplib_path'] = 'phpCAS';
$config['cas_ca_cert_file'] = '';
$config['cas_disable_server_validation'] = TRUE;
$config['cas_debug'] = FALSE;
$config['cas_application_code'] = 'simkat';

$config['cas_user'] = NULL;
$config['cas_password'] = NULL;

/*
|--------------------------------------------------------------------------
| Session Variables Extends
|--------------------------------------------------------------------------
|
| 'sess_cookie_name'
|
|  The session cookie name, must contain only [0-9a-z_-] characters
|
*/
$config['sess_cookie_name'] = str_replace(' ', '_', preg_replace('/[^A-Za-z0-9\s]/', '', strtolower($config['app_name']))) . '_session';

/*
|--------------------------------------------------------------------------
| Setting for Upload File
|--------------------------------------------------------------------------
*/
$config['upload_path'] = "uploads/"; //10MB
$config['file_max_size'] = 15360; //10MB
$config['file_allowed_types'] = "gif|jpg|png|pdf|doc";

/*
|--------------------------------------------------------------------------
| Setting for path foto di portal
|--------------------------------------------------------------------------
*/
$config['foto_path'] = "https://portal.uad.ac.id/upload/avatar/";
