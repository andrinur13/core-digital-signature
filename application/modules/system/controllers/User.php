<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends Admin_Controller {

	private $module = 'system';

	function __construct() {
        parent::__construct();
		// loadmodel
		$this->load->model('auth/users');
		$this->load->model('system/m_user');
      $this->load->model('system/m_unit');
		restrict();
    }

	public function index()
 	{

 		$tpl['module'] = 'system/User';

 		$this->template->inject_partial('modules_css', multi_asset( array(
                                                      //'css/components-md.css' => '_theme_',
                                                         'vendor/datatables/css/dataTables.bootstrap4.min.css' => '_theme_',
                                                      ), 'css' ) );

      $this->template->inject_partial('modules_js', multi_asset( array(
                                                'vendor/datatables/js/jquery.dataTables.min.js' => '_theme_',
                                                'vendor/datatables/js/dataTables.bootstrap4.min.js' => '_theme_',
                                             ), 'js' ) );

 		$this->template->title( 'User' );
 		$this->template->set_breadcrumb( 'Dashboard' , 'system/dashboard/index' );
 		$this->template->set_breadcrumb( 'User' , '');

 		$this->template->build($this->module.'/v_user_index', $tpl);
 	}

   function datatables_user()
   {
      if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
      $this->module = 'system/User/';

      $columns = array(
         1 => 'UserId',
         2 => 'UserName',
         3 => 'UserEmail',
         4 => 'UserRealName',
         5 => 'NamaGroup',
         6 => 'UserIsActive'
      );

      $object = array();
      //$object['UserName'] = '!='.get_user_name();
		$search = $this->input->post('search');
		if($search['value'] != '') {
			$object['UserName'] = $search['value'];
			$object['UserEmail'] = $search['value'];
		}

    $order = array();
	if($this->input->post('order')){
		foreach( $this->input->post('order') as $row => $val){
		$order[$columns[$val['column']]] = $val['dir'];
		}
	}

		$length = ($this->input->post('length') == -1) ? NULL : $this->input->post('length');
		$qry = $this->m_user->get_user($object, $length, $this->input->post('start'), $order);
		$iTotalRecords = (!is_null($qry)) ? intval($this->m_user->get_user($object, NULL, NULL, NULL, 'counter')) : 0;
		$iDisplayStart = intval($this->input->post('start'));
		$sEcho = intval($this->input->post('draw'));

      $records = array();
      $records["data"] = array();
    if(!is_null($qry)){
        $no = $iDisplayStart + 1;
		foreach($qry->result_array() as $row) {
		$is_active = $row['UserIsActive'] == 1 ? '<span class="badge badge-lg badge-info">AKTIF</span>' : '<span class="badge badge-lg badge-default">TIDAK</span>';
		$btn_delete = ($row['UserName'] != get_user_name()) ? '<a href="'. site_url($this->module.'delete/'. $row['UserId'] ) .'" id="delete" class="btn btn-square btn-round btn-danger" data-provide="tooltip" title="Delete User"><i class="ti-trash"></i></a>' : '';
		$records["data"][] = array(
			$no++,
			$row['UserId'],
			$row['UserName'],
			$row['UnitName'],
			$row['UserRealName'],
			$row['NamaGroup'],
			$row['roleNama'],
			$is_active,
			'<a href="'. site_url($this->module.'detail/'. $row['UserId'] ) .'" class="btn btn-square btn-round btn-info" data-provide="tooltip" title="Detail User"><i class="fa fa-eye"></i></a>
			<a href="'. site_url($this->module.'update/'. $row['UserId'] ) .'" class="btn btn-square btn-round btn-warning" data-provide="tooltip" title="Edit User"><i class="ti-pencil"></i></a> ' . $btn_delete
		);
		}
    }

      $records["draw"] = $sEcho;
      $records["recordsTotal"] = $iTotalRecords;
      $records["recordsFiltered"] = $iTotalRecords;

      echo json_encode($records);
   }

 	public function add( )
 	{
 		// $this->load->helper('group/group');

 		$tpl['module'] = 'system/User/add';
		


 		$this->form_validation->set_rules('nama', 'Nama Pengguna', 'required');
 		$this->form_validation->set_rules('username', 'Username', 'required');
 		$this->form_validation->set_error_delimiters('<div class="help-block col-xs-12 col-sm-reset inline">', '</div>');
 		if ($this->form_validation->run()==true) {
 			$this->load->model('auth/Users');

 			$username = $this->input->post('username');
 			$email = $this->input->post('email');
 			$password_input = $this->input->post('password');
 			$password_input_re= $this->input->post('password_input_re');
			$rumah_sakit = $this->input->post('rs_id');
			$roleUser = $this->input->post('isRoleUser');

			// dd($this->input->post());

    		if ($password_input==$password_input_re) {
    			if ($this->Users->is_username_available($username)) {
    				if ($this->Users->is_email_available($email)) {
    					#password
    					if ($this->input->post('password_uname')=='on') {
    						$password_hash = $this->authentication->password_hasher($username);
    					} else {
    						$password_hash = $this->authentication->password_hasher($password_input);
    					}

                  $params = array(
                     'nama'      => $this->input->post('nama', TRUE),
                     'username'  => $this->input->post('username', TRUE),
                     'email'     => $this->input->post('email', TRUE),
                     'password'  => $password_hash['encrypted'],
                     'salt'      => $password_hash['salt'],
                     'is_active' => ($this->input->post('isactive')=='on') ? '1' : '0',
                     'time'      => date("Y-m-d H:i:s"),
                     'group'     => $this->input->post('group'),
                     'role'     => $roleUser,
					 'unit_id' => $this->input->post('unit_id', TRUE),
                     'is_default'=> $this->input->post('isdefault')
                  );

                  $rs = $this->m_user->input_user($params);

                  if ($rs) {
                     $this->session->set_flashdata('msg_register', array('status' => FALSE, 'type' => 'success' , 'title' => 'Informasi!' , 'text' => 'Data pengguna berhasil ditambahkan.'));
                  }else{
                     $this->session->set_flashdata('msg_register', array('status' => FALSE, 'type' => 'warning', 'title' => 'Peringatan!' , 'text' => 'Maaf, data pengguna gagal ditambahkan.'));
                  }
    				} else {
                  $this->session->set_flashdata('msg_register', array('status' => FALSE, 'type' => 'warning', 'title' => 'Peringatan!' , 'text' => 'Maaf, Email sudah terdaftar.'));
               }
    			} else {
    				$this->session->set_flashdata('msg_register', array('status' => FALSE, 'type' => 'warning' , 'title' => 'Peringatan!' , 'text' => 'Maaf, Username sudah terdaftar.'));
    			}
    		} else {
    			$this->session->set_flashdata('msg_register', array('status' => FALSE, 'type' => 'warning' , 'title' => 'Peringatan!' , 'text' => 'Maaf, Password dan Re-Password tidak sesuai.'));
    		}
    		redirect('system/user');
 		} else {
 			$this->template->inject_partial('modules_css', multi_asset( array(
 																		#'css/JChecktree/jquery-checktree.css' => NULL,
 																		), 'css' ) );

 			$this->template->inject_partial('modules_js', multi_asset( array(
 																	#'js/JChecktree/jquery-checktree.js' => NULL,
 																), 'js' ) );

 			// references
			$tpl['group'] = $this->m_user->get_ref_group();
 			//$tpl['organisasi'] = $this->m_user->get_ref_organisasi();
			 $data_unit = $this->m_unit->get_unit()->result_array();
			 $data_role = $this->m_user->get_ref_role()->result();

			 //  dd($data_unit);
			 $tpl['unit_kerja'] = $data_unit;
			 $tpl['role'] = $data_role;


 			$this->template->title( 'User' );

 			//$this->template->set_breadcrumb( config_item('app_name') , '' );
 			$this->template->set_breadcrumb( 'Dashboard' , 'system/dashboard/index' );
 			$this->template->set_breadcrumb( 'User' , 'system/user/index');
 			$this->template->set_breadcrumb( 'Tambah' , '');

 			// print_r($tpl['group']->result());
 			$this->template->build($this->module.'/v_user_add', $tpl);
 		}
 	}

 	public function update( $user_id =  NULL )
 	{

 		$tpl['module'] = 'system/User/update';
		 $data_unit = $this->m_unit->get_unit()->result_array();

		 //  dd($data_unit);
		 $tpl['unit_kerja'] = $data_unit;
		


		// dd($tpl);
 		$this->form_validation->set_rules('nama', 'Nama Pengguna', 'required');
 		$this->form_validation->set_rules('username', 'Username', 'required');
 		$this->form_validation->set_error_delimiters('<div class="help-block col-xs-12 col-sm-reset inline">', '</div>');

 		if ($user_id!=NULL) {
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
         redirect('system/User');
      }

 		if ($this->form_validation->run()==true) {

			// dd($this->input->post());

			$this->load->model('auth/Users');

			$username  = $this->input->post('username');
			$email     = $this->input->post('email');
			$password_input     = $this->input->post('password_input');
			$password_input_re  = $this->input->post('password_input_re');
			if ($password_input == $password_input_re) {	
				if ($this->input->post('password_uname')=='on') {
					$password_hash = $this->authentication->password_hasher($username);
				} else {
					$password_hash = $this->authentication->password_hasher($password_input);
				}
            #isActive
            $is_active = ($this->input->post('isactive')=='on') ? '1' : '0' ;

            $params = array(
                        'nama'      => $this->input->post('nama', TRUE),
                        'email'     => $this->input->post('email'),
                        'hp'     => $this->input->post('no_hp'),
                        'password'  => ($password_input != "") ? $password_hash['encrypted'] : '',
                        'salt'      => ($password_input != "") ? $password_hash['salt'] : '',
                        'is_active' => ($this->input->post('isactive')=='on') ? '1' : '0',
                        'time_update'  => date("Y-m-d H:i:s"),
                        'user_update'  => get_user_name(),
                        'group'     => $this->input->post('group'),
                        'role'     => $this->input->post('isRoleUser'),
						'unit_id' => $this->input->post('unit_id', TRUE),
                        'is_default'=> $this->input->post('isdefault')
            );
            $rs = $this->m_user->update_user($params, $user_id); 
	
				if ($rs) {
					$this->session->set_flashdata('msg_register', array('status' => FALSE, 'type' => 'success' , 'title' => 'Informasi!' , 'text' => 'Data pengguna berhasil diperbarui.'));
				}else{
					$this->session->set_flashdata('msg_register', array('status' => FALSE, 'type' => 'warning', 'title' => 'Peringatan!' , 'text' => 'Maaf, data pengguna gagal diperbarui.'));
				}
            redirect('system/user');
			} else {
				$this->session->set_flashdata('msg_register', array('status' => FALSE, 'type' => 'warning', 'title' => 'Peringatan!' , 'text' => 'Maaf, Re-Password yang Anda masukkan tidak sama dengan Password.'));
			}
 		} else {
         //print_r($this->template);

 			$this->template->inject_partial('modules_css', multi_asset( array(
 																		#'css/JChecktree/jquery-checktree.css' => NULL,
 																		), 'css' ) );

 			$this->template->inject_partial('modules_js', multi_asset( array(
 																	#'js/JChecktree/jquery-checktree.js' => NULL,
 																), 'js' ) );


			$tpl['group'] = $this->m_user->get_ref_group();
			
			// print_r($tpl['group']);die;
 			$tpl['unit'] = $this->m_unit->get_unit(NULL, NULL, NULL, NULL);
 			$tpl['data_user'] = $user_by_id;
			$tpl['user_group'] = $this->m_user->get_group_by_user_id($user_id)->result();
 			//$tpl['user_organisasi'] = $this->m_user->get_organisasi_by_user_id($user_id);
			 $data_role = $this->m_user->get_ref_role()->result();
			 $tpl['role'] = $data_role;

 			$this->template->title( 'User' );

 			$this->template->set_breadcrumb( 'Dashboard' , 'system/dashboard/index' );
 			$this->template->set_breadcrumb( 'User' , 'system/user/index');
 			$this->template->set_breadcrumb( 'Update' , '');
 			
         $this->template->build($this->module.'/v_user_update', $tpl);
 		}
 	}

 	public function detail($user_id){
 		if ( is_null($user_id) ) show_404();
 		$tpl['module'] = 'system/user/';
 			$this->template->inject_partial('modules_css', multi_asset( array(
 																		#'css/JChecktree/jquery-checktree.css' => NULL,
 																		), 'css' ) );

 			$this->template->inject_partial('modules_js', multi_asset( array(
 																	#'js/JChecktree/jquery-checktree.js' => NULL,
 																), 'js' ) );

 			// references
 			$tpl['group'] = $this->m_user->get_ref_group();
 			$object = array();
 			$object['UserId'] = $user_id;
 			$order = array();
 			if($this->input->post('order')){
 				foreach( $this->input->post('order') as $row => $val){
 					$order[$columns[$val['column']]] = $val['dir'];
 				}
 			}
 			$length = ($this->input->post('length') == -1) ? NULL : $this->input->post('length');

 			$tpl['data'] = $this->m_user->get_user($object, $length, $this->input->post('start'), $order)->result_array();
 			$tpl['data_group'] = $this->m_user->get_group_by_user_id($user_id)->result();


 			$this->template->title( 'User' );

 			$this->template->set_breadcrumb( 'Dashboard' , 'system/dashboard/index' );
 			$this->template->set_breadcrumb( 'User' , 'system/user/index');
 			$this->template->set_breadcrumb( 'Detail' , '');

 			$this->template->build($this->module.'/v_user_detail', $tpl);
 	}

   function delete($user_id) 
   {
      if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
      
      if ($user_id != get_user_id()) {     
         $qry = $this->m_user->delete_user($user_id);
         if ($qry == true) {
            echo json_encode(array("status" => TRUE, 'msg' => 'Data berhasil dihapus.'));
         }else{
            echo json_encode(array("status" => FALSE, 'msg' => 'Gagal memproses! Silahkan hubungi administrator'));
         }
      } else {
         redirect(site_url('system/User'));
      }
   } 
}