<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends Dashboard_Controller {

	private $module = 'system';

	function __construct() 
	{
		parent::__construct();
		$this->load->model('auth/users');
		$this->load->model('system/m_user');
		$this->load->model('system/m_profile');
		restrict();
	}

	public function update($idx =  NULL)
	{
		$tpl['module'] = 'system/Profile/update';
		$user_id = decode($idx);
		// print_r($user_id);die;
		if ($user_id !=NULL) {
				$object = array();
				$object['UserId'] = '='.$user_id;
				$order = array();
				if($this->input->post('order')){
					foreach( $this->input->post('order') as $row => $val){
						$order[$columns[$val['column']]] = $val['dir'];
					}
				}
				$length = ($this->input->post('length') == -1) ? NULL : $this->input->post('length');

				$user_by_id = $this->m_user->get_user($object, $length, $this->input->post('start'), $order)->result_array();
			} else { 
			// redirect('dashboard');
		}

		$this->form_validation->set_rules('password_input', 'Password', 'required');
		if ($this->form_validation->run()==true) {
			$password_input     = $this->input->post('password_input', TRUE);
			$password_input_re  = $this->input->post('password_input_re', TRUE);
			if ($password_input == $password_input_re) {

				$password_hash = $this->authentication->password_hasher($password_input);

				$params = array(
                        'password'  => ($password_input != "") ? $password_hash['encrypted'] : '',
                        'salt'      => ($password_input != "") ? $password_hash['salt'] : '',
                        'time_update'  => date("Y-m-d H:i:s"),
                        'user_update'  => get_user_name(),
            	);
				$rs = $this->m_profile->update_user($params, $user_id); 
				
				if ($rs) {
					$this->session->set_flashdata('msg_register', array('status' => FALSE, 'type' => 'success' , 'title' => 'Informasi!' , 'text' => 'Data pengguna berhasil diperbarui.'));
				}else{
					$this->session->set_flashdata('msg_register', array('status' => FALSE, 'type' => 'warning', 'title' => 'Peringatan!' , 'text' => 'Maaf, data pengguna gagal diperbarui.'));
				}
				redirect($tpl['module'].'/'.$idx);
			}else{
				$this->session->set_flashdata('msg_register', array('status' => FALSE, 'type' => 'warning', 'title' => 'Peringatan!' , 'text' => 'Maaf, Re-Password yang Anda masukkan tidak sama dengan Password.'));
			}
		}

		$tpl['data_user'] = $user_by_id;

		// print_r($user_by_id);die;

		$this->template->title( 'Profile' );

		$this->template->set_breadcrumb( 'Dashboard' , 'system/dashboard/index' );
		$this->template->set_breadcrumb( 'User' , '');
		$this->template->set_breadcrumb( 'Profile' , '');
		
		$this->template->build($this->module.'/v_profile_update', $tpl);

		// var_dump($id);die;
	}
}


/* End of file profile.php */
/* Location: H:\xampp\htdocs\apps\uad\amk\application\modules\system\controllers\profile.php */