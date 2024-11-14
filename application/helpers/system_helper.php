<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	if ( ! function_exists('get_user_id'))
	{
		function get_user_id()
		{
			$ci=& get_instance();
			return $ci->authentication->get_user_id();
		} 
	}
	
	if ( ! function_exists('get_user_name'))
	{
		function get_user_name()
		{
			$ci=& get_instance();
			if(!is_null( $ci->authentication->get_username() )){
				return $ci->authentication->get_username();
			} else {
				return NULL;
			}
		} 
	}
	
	if ( ! function_exists('get_user_real_name'))
	{
		function get_user_real_name()
		{
			$ci=& get_instance();
			if(!is_null( $ci->authentication->get_user_real_name() )){
				return $ci->authentication->get_user_real_name();
			} else {
				return NULL;
			}
		} 
	}
	
	if ( ! function_exists('get_user_email'))
	{
		function get_user_email()
		{
			$ci=& get_instance();
			if(!is_null( $ci->authentication->get_user_email() )){
				return $ci->authentication->get_user_email();
			} else {
				return NULL;
			}
		} 
	}


	if ( ! function_exists('get_unit_kerja_access'))
	{
		function get_unit_kerja_access()
		{
			$ci=& get_instance();
			if($ci->authentication->get_user_group() == $ci->config->item('group_id_unit_kerja')){
				return true;
			} else {
				return false;
			}
		} 
	}

	if ( ! function_exists('get_kantor_uad_access'))
	{
		function get_kantor_uad_access()
		{
			$ci=& get_instance();
			if($ci->authentication->get_user_group() == $ci->config->item('group_id_kantor')){
				return true;
			} else {
				return false;
			}
		} 
	}
	
	if ( ! function_exists('get_user_group'))
	{
		function get_user_group()
		{
			$ci=& get_instance();
			if(!is_null( $ci->authentication->get_user_group() )){
				return $ci->authentication->get_user_group();
			} else {
				return NULL;
			}
		} 
	}

	if ( ! function_exists('get_user_group_name'))
	{
		function get_user_group_name()
		{
			$ci=& get_instance();
			if(!is_null( $ci->authentication->get_user_group_name() )){
				return $ci->authentication->get_user_group_name();
			} else {
				return NULL;
			}
		} 
	}
	
	if ( ! function_exists('get_user_role_id'))
	{
		function get_user_role_id()
		{
			$ci=& get_instance();
			if(!is_null( $ci->authentication->get_user_role_id() )){
				return $ci->authentication->get_user_role_id();
			} else {
				return NULL;
			}
		} 
	}
	
	if ( ! function_exists('get_user_unit_kode'))
	{
		function get_user_unit_kode()
		{
			$ci=& get_instance();
			if(!is_null( $ci->authentication->get_user_unit_kode() )){
				return $ci->authentication->get_user_unit_kode();
			} else {
				return NULL;
			}
		} 
	}
	if ( ! function_exists('get_user_unit_name'))
	{
		function get_user_unit_name()
		{
			$ci=& get_instance();
			if(!is_null( $ci->authentication->get_user_unit_name() )){
				return $ci->authentication->get_user_unit_name();
			} else {
				return NULL;
			}
		} 
	}
	
	if ( ! function_exists('get_user_unit_id'))
	{
		function get_user_unit_id()
		{
			$ci=& get_instance();
			if(!is_null( $ci->authentication->get_user_unit_id() )){
				return $ci->authentication->get_user_unit_id();
			} else {
				return NULL;
			}
		} 
	}

	if ( ! function_exists('get_backend_user'))
    {
        function get_backend_user()
        {
            $ci=& get_instance();
            if(!is_null( $ci->authentication->get_backend_user() )){
                return $ci->authentication->get_backend_user();
            } else {
                return NULL;
            }
        } 
    }



	if ( ! function_exists('get_user_app'))
    {
        function get_user_app()
        {
            $ci=& get_instance();
            if(!is_null( $ci->authentication->get_user_app() )){
                return $ci->authentication->get_user_app();
            } else {
                return NULL;
            }
        } 
    }
	
	if ( ! function_exists('restrict'))
	{
		function restrict($module_link = NULL, $return = FALSE)
		{
			$ci=& get_instance();
			
			if($ci->authentication->restrict($module_link) == FALSE){
				if($ci->input->is_ajax_request()){
					if($return == TRUE){
						return FALSE;
					} else {
						exit('No script allowed.');
					}
				} else {
					if($return == TRUE){
						return FALSE;
					} else {
						show_error("Error! Don't have permission to access on this uri.");
					}
				}
			} else {
				if($return == TRUE){
					return TRUE;
				}
			}
		} 
	}
	
	if ( ! function_exists('protect_acct'))
	{
		function protect_acct()
		{
			$ci=& get_instance();
			return $ci->authentication->protect_acct();
		} 
	}
	
	if ( ! function_exists('localized_lang'))
	{
		function localized_lang( $uri )
		{
			$ci=& get_instance();
			return $ci->lang->localized($uri);
		} 
	}

// -------------------------- Asset Helpers --------------------------------------

	if ( ! function_exists('css'))
	{
		function css($asset_name, $module_name = NULL, $attributes = array())
		{
			$ci =& get_instance();
			$ci->load->library('asset');
			return $ci->asset->css($asset_name, $module_name, $attributes);
		}
	}

	if ( ! function_exists('multi_css'))
	{
		function multi_css($asset_name = array())
		{
			$ci =& get_instance();
			$ci->load->library('asset');
			return $ci->asset->multi_css($asset_name );
		}
	}


	if ( ! function_exists('theme_css'))
	{
		function theme_css($asset, $attributes = array())
		{
			return css($asset, '_theme_', $attributes);
		}
	}
	
	if ( ! function_exists('css_url'))
	{
		function css_url($asset_name = array())
		{
			$ci =& get_instance();
			$ci->load->library('asset');
			return $ci->asset->css_url($asset_name, $module_name);
		}
	}

	if ( ! function_exists('css_path'))
	{
		function css_path($asset_name, $module_name = NULL)
		{
			$ci =& get_instance();
			$ci->load->library('asset');
			return $ci->asset->css_path($asset_name, $module_name);
		}
	}

	if ( ! function_exists('image'))
	{
		function image($asset_name, $module_name = NULL, $attributes = array())
		{
			$ci =& get_instance();
			$ci->load->library('asset');
			return $ci->asset->image($asset_name, $module_name, $attributes);
		}
	}

	if ( ! function_exists('theme_image'))
	{
		function theme_image($asset, $attributes = array())
		{
			return image($asset, '_theme_', $attributes);
		}
	}

	if ( ! function_exists('image_url'))
	{
		function image_url($asset_name, $module_name = NULL)
		{
			$ci =& get_instance();
			$ci->load->library('asset');
			return $ci->asset->image_url($asset_name, $module_name);
		}
	}

	if ( ! function_exists('image_path'))
	{
		function image_path($asset_name, $module_name = NULL)
		{
			$ci =& get_instance();
			$ci->load->library('asset');
			return $ci->asset->image_path($asset_name, $module_name);
		}
	}

	if ( ! function_exists('js'))
	{
		function js($asset_name, $module_name = NULL)
		{
			$ci =& get_instance();
			$ci->load->library('asset');
			return $ci->asset->js($asset_name, $module_name);
		}
	}
	
	if ( ! function_exists('multi_js'))
	{
		function multi_js($asset_name = array())
		{
			$ci =& get_instance();
			$ci->load->library('asset');
			return $ci->asset->multi_js($asset_name);
		}
	}

	if ( ! function_exists('theme_js'))
	{
		function theme_js($asset, $attributes = array())
		{
			return js($asset, '_theme_', $attributes);
		}
	}

	if ( ! function_exists('js_url'))
	{
		function js_url($asset_name, $module_name = NULL)
		{
			$ci =& get_instance();
			$ci->load->library('asset');
			return $ci->asset->js_url($asset_name, $module_name);
		}
	}

	if ( ! function_exists('js_path'))
	{
		function js_path($asset_name, $module_name = NULL)
		{
			$ci =& get_instance();
			$ci->load->library('asset');
			return $ci->asset->js_path($asset_name, $module_name);
		}
	}
	
	if ( ! function_exists('asset_path'))
	{
		function asset_path($asset_name, $module_name = NULL, $asset_type = NULL)
		{
			$ci =& get_instance();
			$ci->load->library('asset');
			return $ci->asset->asset_path($asset_name, $module_name, $asset_type);
		}
	}
	
	if ( ! function_exists('asset_url'))
	{
		function asset_url($asset_name, $module_name = NULL, $asset_type = NULL)
		{
			$ci =& get_instance();
			$ci->load->library('asset');
			return $ci->asset->asset_url($asset_name, $module_name,$asset_type);
		}
	}
	
	if ( ! function_exists('multi_asset'))
	{
		function multi_asset($asset_name = array(), $asset_type)
		{
			$ci =& get_instance();
			$ci->load->library('asset');
			return $ci->asset->multi_asset($asset_name, $asset_type);
		}
	}
	
// ------- AUTH -------
	if (!function_exists('hashSSHA')) {
	   /**
		 * Encrypting password
		 * @param password
		 * returns salt and encrypted password
		 */
		function hashSSHA($password) {

			$salt = sha1(rand());
			$salt = substr($salt, 0, 10);
			$encrypted = base64_encode(sha1($password . $salt, true) . $salt);
			$hash = array("salt" => $salt, "encrypted" => $encrypted);
			return $hash;
		}
	}

	if (!function_exists('checkhashSSHA')) {
		/**
		 * Decrypting password
		 * @param salt, password
		 * returns hash string
		 */
		function checkhashSSHA($salt, $password) {

			$hash = base64_encode(sha1($password . $salt, true) . $salt);

			return $hash;
		}
	}
	
	if (!function_exists('generateKey')) {
		/**
		 * API key
		 * @param salt, password
		 * returns hash string
		 */
		function generateKey($salt, $password) {
			do
			{
				// Generate a random salt
				$salt = base_convert(bin2hex($this->security->get_random_bytes(64)), 16, 36);

				// If an error occurred, then fall back to the previous method
				if ($salt === FALSE)
				{
					$salt = hash('sha256', time() . mt_rand());
				}

				$new_key = substr($salt, 0, config_item('rest_key_length'));
			}
			while ($this->_key_exists($new_key));

			return $new_key;
		}
	}
	
// ------------------------------------------------------------------------

	function randomString($length = 10){
		$alphnum = '0123456789';
		$alphnum_length = strlen($alphnum) - 1;
		$random_string = '';

		for ($i=0; $i < $length; $i++) { 
			$random_string.= $alphnum[rand(0, $alphnum_length)];
		}
		return $random_string;

	}

	if ( ! function_exists('get_name_pesan'))
	{
		function get_name_pesan($id)
		{
			$ci =& get_instance();
			$ci->load->model('m_inbox');
			$user = $ci->m_inbox->get_realname_pesan($id);
			return $user->name;
		}
	}

	// function IndonesianDate($strDate)
	// {
		// if (is_null($strDate)) return;
		// $date = explode("-", nice_date($strDate, 'd-n-Y'));
		// $bln = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus',
     				// 'September','Oktober','November','Desember');

		// return $date[0].' '.$bln[$date[1]-1].' '.$date[2];
	// }

	if ( ! function_exists('date_pesan'))
	{
		function date_pesan($date)
		{
			$ci =& get_instance();
			$bln=array('Jan','Feb','Maret','April','Mei','Juni','Juli','Agustus',
     					'Sept','Okt','Nov','Des');
			$now = new datetime();
			$diff = date_diff($now, new datetime($date));
			/*if ($diff->format('%a') == 0) {
				$time = date('H:i', strtotime($date));
			} else {
				//$time = date('M', strtotime('2014-08-20'));
				$time = date('d', strtotime($date)).' '.$bln[date('n', strtotime($date))-1];
			}*/

			if ($diff->format('%y') > 0) {
				$time = date('d', strtotime($date)).' '.$bln[date('n', strtotime($date))-1].' '.date('Y', strtotime($date));
			} else if ($diff->format('%a') > 0){
				$time = date('d', strtotime($date)).' '.$bln[date('n', strtotime($date))-1];
			} else {
				$time = date('H:i', strtotime($date));
			}

			return $time;
		}
	}
	
	if ( ! function_exists('HitungUmur'))
	{
		function HitungUmur($strDate1 = NULL, $strDate2 = NULL)
		{
			$hrBln = array(31, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
			
			if (is_null($strDate1)) return NULL;
			if (is_null($strDate2)){
				$date2 = explode("-", date("d-n-Y"));
			} else {
				$date2 = explode("-", nice_date($strDate2, 'd-n-Y'));
			}
			$old_date = date($strDate1);              // returns Saturday, January 30 10 02:06:34
			$old_date_timestamp = strtotime($old_date);
			$new_date = date('d-n-Y', $old_date_timestamp);   
			$date1 = explode("-", $new_date);
			
			if (isKabisat($date2[2])) {
				// jika tahun kabisat
				$hrBln[2] = 29;
			}
			else {
				// jika bukan tahun kabisat
				$hrBln[2] = 28;
			}
			
			$jmlTahun = $date2[2] - $date1[2];
			$jmlBulan = $date2[1] - $date1[1];
			$jmlHari = $date2[0] - $date1[0];

			// jika $jmlBulan negatif
			if ($jmlBulan < 0) {
				$jmlTahun--;
				$jmlBulan = 12 + $jmlBulan; // 12 + (-$jmlBulan)
			}

			// jika $jmlHari negatif
			if ($jmlHari < 0 ) {
				// hitung jumlah bulan
				if ($jmlBulan > 0) {
					$jmlBulan--;
				}
				if ($jmlBulan == 0) {
					$jmlBulan = 11;
					$jmlTahun--;
				}

				// hitung jumlah hari
				// sisa hari bulan sebelumnya = jumlah hari bulan sebelumnya - hari bulan sebelumnya
				$sisaHrBlnSebelumnya = $hrBln[$date2[1] - 1] - $date1[0];

				// jika sisanya negatif, maka tidak ada sisa hari
				if ($sisaHrBlnSebelumnya < 0) {
					$sisaHrBlnSebelumnya = 0;
				}

				// jumlah hari = sisa hari bulan sebelumnya + hari pada $date_2
				$jmlHari = $sisaHrBlnSebelumnya + $date2[0];
			}

			// mengembalikan selisih waktu dalam bentuk array(tahun, bulan, waktu)
			return array("tahun" => $jmlTahun, "bulan" => $jmlBulan, "hari" => $jmlHari);
		}
	}
	
	if ( ! function_exists('isKabisat'))
	{
		function isKabisat($thn) {
		 // jika tahun habis dibagi 4, maka tahun kabisat
			if (($thn % 4) != 0) {
				return false;
			} // jika tidak habis dibagi 4, maka jika habis dibagi 100 dan 400 maka tahun kabisat
			else if ((($thn % 100) == 0) && (($thn % 400) != 0)) {
				return false;
			}
			else {
				return true;
			}
		}
	}
	
	if ( ! function_exists('curl_api'))
	{
		function curl_api( $data = array() )
		{	
			$content = NULL;
			
			if( function_exists('curl_version') )
			{
				//Server url
				$url = $data['url']; // http://172.10.27.4/rest/index.php?d=api&c=sdm&m=dosen
				$headers = array(
					'U4D-API-KEY: '. $data['key']
				);
				
				try {
					$ch = curl_init();

					if (FALSE === $ch)
						throw new Exception('failed to initialize');

					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					// curl_setopt($curl_handle, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					if(isset($data['post'])) 
					{
						curl_setopt($ch, CURLOPT_POSTFIELDS, $data['post'] );
					}

					$content = curl_exec($ch);

					if (FALSE === $content)
						throw new Exception(curl_error($ch), curl_errno($ch));
					// ...process $content now
				} catch(Exception $e) {

					trigger_error(sprintf(
						'Curl failed with error #%d: %s',
						$e->getCode(), $e->getMessage()),
						E_USER_ERROR);

				}
			}
			
			return $content;
		}
	}
	
	if ( ! function_exists('WriteController'))
   {
      function WriteController($module_path, $controller) 
      {   
         $file_controller = $module_path . DIRECTORY_SEPARATOR .'controllers'. DIRECTORY_SEPARATOR . $controller.'.php';

         $str = "<?php " .  PHP_EOL . "defined('BASEPATH') OR exit('No direct script access allowed');". PHP_EOL. PHP_EOL;
         $str .= "class {$controller} extends Dashboard_Controller {" . PHP_EOL . PHP_EOL;
         $str .= "\tfunction __construct() ". PHP_EOL . "\t{" . PHP_EOL;
         $str .= "\t\tparent::__construct();" . PHP_EOL . "\t\t//Do your magic here". PHP_EOL . "\t}" . PHP_EOL;
         $str .= "}" . PHP_EOL . PHP_EOL .PHP_EOL;
         $str .= "/* End of file {$controller}.php */" . PHP_EOL;
         $str .= "/* Location: {$file_controller} */";
         
         write_file($file_controller, $str);
      }
   }

   if ( ! function_exists('GenerateModule'))
   {
      function GenerateModule($module, $controller) {
         $ci=& get_instance();
         $ci->load->helper('file');
         
         $module_path = APPPATH.'modules'.DIRECTORY_SEPARATOR.$module;
         if (file_exists($module_path) && is_dir($module_path)) { 
            WriteController($module_path, $controller);
         } else {
            $content_html = "<!DOCTYPE html>\n<html>\n<head><title>403 Forbidden</title></head>\n<body><p>Directory access is forbidden.</p></body>\n</html>";

            if (!mkdir($module_path, 0755, true)) {
               return FALSE;
            } else {
               $file_html = $module_path . DIRECTORY_SEPARATOR.'index.html';
               write_file($file_html, $content_html);

               mkdir($module_path . DIRECTORY_SEPARATOR .'controllers', 0755, true);
               copy($file_html, $module_path . DIRECTORY_SEPARATOR .'controllers'.DIRECTORY_SEPARATOR.'index.html');        
               mkdir($module_path . DIRECTORY_SEPARATOR .'models', 0755, true);
               copy($file_html, $module_path . DIRECTORY_SEPARATOR .'models'.DIRECTORY_SEPARATOR.'index.html');
               mkdir($module_path . DIRECTORY_SEPARATOR .'views', 0755, true);
               copy($file_html, $module_path . DIRECTORY_SEPARATOR .'views'.DIRECTORY_SEPARATOR.'index.html');

               WriteController($module_path, $controller);

               return TRUE;
            }
         }
      }
   }

   if ( ! function_exists('dd'))
   {
	   function dd($string)
	   {
		   echo "<pre>";
		   print_r($string);
		 	echo  "</pre>";
			die;
	   } 
   }
   