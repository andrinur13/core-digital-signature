<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Base_Controller extends MX_Controller
{	
	function __construct(){
		parent::__construct();
		
		//The Public_Controller and Admin_Controller will inherit this logic
		$this->load->library(array('form_validation'));
		//$this->load->model('m_system');

		$this->lang->load('auth/auth');
		$this->template->title($this->config->item('site.title'));
		$this->form_validation->CI =& $this;		
	}
	
	/**
	 * Create CAPTCHA image to verify user as a human
	 *
	 * @return	string
	 */
	public function _create_captcha()
	{
		$this->load->helper('captcha');
		$this->load->model('auth/login_attempts');
		$cap = create_captcha(array(
			'word'          => randomString(8),
			'img_path'		=> './'.$this->config->item('auth_captcha_path'),
			'img_url'		=> base_url().$this->config->item('auth_captcha_path'),
			'font_path'		=> './'.$this->config->item('auth_captcha_fonts_path'),
			'img_width'		=> $this->config->item('auth_captcha_width'),
			'img_height'	=> $this->config->item('auth_captcha_height'),
			'show_grid'		=> $this->config->item('auth_captcha_grid'),
			'expiration'	=> $this->config->item('auth_captcha_expire'),
			'word_length'   => 8,
			'font_size'		=> $this->config->item('auth_captcha_font_size'),
			'img_id'        => 'captchaId',
			/* 'pool'          => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', */
			'pool'          => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
			'colors'        => array(
					'background' => array(255, 255, 255),
					'border' => array(255, 255, 255),
					'text' => array(0, 0, 0),
					'grid' => array(255, 40, 40),
			),
		));

		// Save captcha params in session
		/* 
		$this->session->set_flashdata(array(
				'captcha_word' => $cap['word'],
				'captcha_time' => $cap['time'],
		)); 
		*/
		
		#$this->login_attempts->delete_captcha(array('ip_address' => $this->input->ip_address()));
		
		$data = array(
					'captcha_time'  => $cap['time'],
					'ip_address'    => $this->input->ip_address(),
					'word'          => $cap['word']
				);
				
		$this->login_attempts->insert_captcha($data);
		
		return $cap['image'];
	}

	/**
	 * Callback function. Check if CAPTCHA test is passed.
	 *
	 * @param	string
	 * @return	bool
	 */
	public function _check_captcha($code)
	{
		$this->load->model('auth/login_attempts');
		
		list($usec, $sec) = explode(" ", microtime());
		$now = ((float)$usec + (float)$sec);
		$expiration = time() - 7200;
		$rest = $this->login_attempts->count_available_captcha(array($code, $this->input->ip_address(), $expiration));
		
		if( $rest->count == 0 ){
			$this->form_validation->set_message('_check_captcha', $this->lang->line('auth_incorrect_captcha'));
			return FALSE;
		} else {
			$this->login_attempts->delete_captcha(array('ip_address' => $this->input->ip_address()));
		}
		
		return TRUE;
	}

	/**
	 * Create reCAPTCHA JS and non-JS HTML to verify user as a human
	 *
	 * @return	string
	 */
	public function _create_recaptcha()
	{
		$this->load->helper('recaptcha');

		// Add custom theme so we can get only image
		$options = "<script>var RecaptchaOptions = {theme: 'custom', custom_theme_widget: 'recaptcha_widget'};</script>\n";

		// Get reCAPTCHA JS and non-JS HTML
		$html = recaptcha_get_html($this->config->item('auth_recaptcha_public_key'));

		return $options.$html;
	}

	/**
	 * Callback function. Check if reCAPTCHA test is passed.
	 *
	 * @return	bool
	 */
	function _check_recaptcha()
	{
		$this->load->helper('recaptcha');

		$resp = recaptcha_check_answer($this->config->item('auth_recaptcha_private_key'), $_SERVER['REMOTE_ADDR'], $this->input->post('recaptcha_challenge_field'),  $this->input->post('recaptcha_response_field'));
		if (!$resp->is_valid) {
			$this->form_validation->set_message('_check_recaptcha', $this->lang->line('auth_incorrect_captcha'));
			return FALSE;
		}
		return TRUE;
	}
	
}

class Auth_Controller extends Base_Controller
{
	public function __construct()
	{
		parent::__construct();

      $this->load->library(array('authentication'));
		$this->config->set_item('app_debug', 0);
		$this->template->set_layout('layout_auth')
							->set_partial('modules_js','modules_js')
							->set_partial('modules_css','modules_css');
		$this->asset->set_theme($this->config->item('theme'));
	}
	
}


class Unit_Kerja_Controller extends Base_Controller
{
   public function __construct()
   {
      parent::__construct();

      $this->load->library(array('authentication'));
      
      $this->load->helper('system_helper');
      
		$this->template->set_layout('layout_unit_kantor')
		->set_partial('modules_js','modules_js')
		->set_partial('modules_css','modules_css');
		$this->asset->set_theme($this->config->item('theme'));
	  
      
   }
}


class Kantor_Univ_Controller extends Base_Controller
{
   public function __construct()
   {
      parent::__construct();

      $this->load->library(array('authentication'));
      
      $this->load->helper('system_helper');
      
		$this->template->set_layout('layout_kantor_univ')
		->set_partial('modules_js','modules_js')
		->set_partial('modules_css','modules_css');
		$this->asset->set_theme($this->config->item('theme'));
	  
      
   }
}



class Admin_Controller extends Base_Controller
{
   public function __construct()
   {
      parent::__construct();

      $this->load->library(array('authentication'));
      
      $this->template->set_layout('layout_admin')
                     ->set_partial('modules_js','modules_js')
                     ->set_partial('modules_css','modules_css');
      $this->asset->set_theme($this->config->item('theme'));
   }
   
}


class Dashboard_Controller extends Base_Controller
{
   public function __construct()
   {
      parent::__construct();

      $this->load->library(array('authentication'));
      
      $this->template->set_layout('layout_admin')
                     ->set_partial('modules_js','modules_js')
                     ->set_partial('modules_css','modules_css');
      $this->asset->set_theme($this->config->item('theme'));
   }
   
}

class Public_Controller extends Base_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->template->set_layout('layout_default')
							->set_partial('modules_js','modules_js')
							->set_partial('modules_css','modules_css');
		$this->asset->set_theme($this->config->item('theme'));
	}
	
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */