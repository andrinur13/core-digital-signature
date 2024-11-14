<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Signature extends Dashboard_Controller {

	private $path = 'refrensi';
	private $master = 'master';
    private $mod = 'Signature';

	function __construct() 
	{
		parent::__construct();
		
		
		$this->load->model($this->path.'/m_signature');
        // Models For Basic Form
        $this->load->model($this->master.'/m_form');
        $this->load->model('system/m_user');
        $this->load->model('system/m_unit');
        $this->load->model('auth/Users');
		$this->load->helper($this->master.'/general');


		$this->general = new GeneralHelper();
        $this->module = $this->general->module($this->path, $this->mod);
        
        restrict();
	}

	public function index() {
        $this->template->inject_partial('modules_css', multi_asset(array(
			'vendor/datatables/css/dataTables.bootstrap4.min.css' => '_theme_',
		), 'css'));

		$this->template->inject_partial('modules_js', multi_asset(array(
			'vendor/datatables/js/jquery.dataTables.min.js' => '_theme_',
			'vendor/datatables/js/dataTables.bootstrap4.min.js' => '_theme_',
		), 'js'));

        $data['roleAdd'] = restrict($this->module.'/add', true);
        $data['module']= $this->module;
        $data['moduleAdd']= $this->module.'/add';

        $this->template->title('Data Pejabat');
		$this->template->set_breadcrumb('Pajabat', $this->module);
		$this->template->build(strtolower($this->module) . '/v_index', $data);
    }

	public function ajax($action = 'datatables') { 
        if(is_null( $action ))  exit('Null action');
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
        if($action == 'datatables'){
           
            if ($this->input->post('customActionType') == "group_action") {
                if($this->input->post('customActionName') == 'Delete'){
                    $this->delete($this->input->post('data'));
                }
            }else{
                $this->datatables();
            }
        }elseif($action == 'search_user'){
			$this->search_ajax_user($this->input->post('query'));
		}elseif($action == 'load_view'){
            $id = $this->input->post('id');
            $user_id = $this->input->post('user_id');
			$this->load_view($id, $user_id);
		}
    }


	private function load_view($view = 'buat-form', $user_id = ''){

		$data['module'] = $this->module;
        $data['group'] = $this->m_user->get_ref_group();
        $data_unit = $this->m_unit->get_unit()->result_array();
        $data['unit_kerja'] = $data_unit;
        

        if(!empty($user_id)){
            $data['user'] = $this->m_pejabat->cekUserByUserId(decode($user_id));
        }


		if($view == 'buat-form'){
			$this->load->view(strtolower($this->module).'/v_add_pejabat_user', $data);
		}elseif($view == 'cari-form'){
			$this->load->view(strtolower($this->module).'/v_search_user', $data);
		}
	}

	private function search_ajax_user($param){
		$filter['filter'] = $param;
		$data = $this->m_pejabat->get_sys_user($filter);
		$json = array();
		foreach($data as $key => $val){
			$json[] = array(
                'id' => $val['UserId'], 
                'username' => $val['UserName'],
                'real_name' => $val['UserRealName'],
            );
		}
		
		$this->output->set_header('Content-Type: application/json');
        echo json_encode($json);
	}

	private function datatables(){
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
        $columns = array(
            1 => 'pjbKode',
            2 => 'pjbNama',
            3 => 'pjbJabatan',
            4 => 'UserName',
        );
        
        $object = array();

        $filter_key = $this->input->post('search');
        if ($filter_key['value'] != '') {
            $object['filter_key'] = $filter_key['value'];
        }

        $order = array();
        if($this->input->post('order')){
            foreach( $this->input->post('order') as $row => $val){
                $order[$columns[$val['column']]] = $val['dir'];
            }
        }

        $length = ($this->input->post('length') == -1) ? NULL : $this->input->post('length');
        $qry = $this->m_pejabat->get_data($object, $length, $this->input->post('start'), $order, NULL);
        $iTotalRecords = (!is_null($qry)) ? intval($this->m_pejabat->get_data($object, NULL, NULL, array(), 'counter')) : 0;
        $iDisplayStart = intval($this->input->post('start'));
        $sEcho = intval($this->input->post('draw'));
        $records = array();
        $records["data"] = array(); 
        if(!is_null($qry)){
            foreach($qry->result_array() as $row){

				$roleKolom = restrict($this->module.'/set_kolom', true);
				$btn_edit = '<a data-provide="tooltip" data-original-title="Edit"  id="edit-btns" data-id="'.encode($row['pjbId']).'" href="'. site_url( $this->module.'/update/'. encode($row['pjbId']) ) .'" class="btn btn-square btn-round btn-warning" ><i class="fa fa-pencil"></i></a>';
				$btn_set_kolom = ($roleKolom) ? '<a data-provide="tooltip" data-original-title="Seting Kolom Jenis Surat" href="#" id="set-btn" data-id="'.encode($row['pjbId']).'" data-href="'. site_url( $this->module.'/update/'. encode($row['pjbId']) ) .'" class="btn btn-square btn-round btn-primary" ><i class="fa fa-external-link"></i></a>' : '';
                $records["data"][] = array(
                    '<input type="checkbox" class="data-id" name="data_id[]" value="'. encode($row['pjbId']) .'">',
                    $row['pjbKode'],
                    $row['pjbNipm'],
                    $row['pjbNama'],
                    $row['pjbJabatan'],
                    $row['UserName'],
                    $btn_edit.'&nbsp;'.$btn_set_kolom,
                );
            }
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        echo json_encode($records);
    }

	public function add() {
        $data['module'] = $this->module;

        $this->template->inject_partial('modules_css', multi_asset(array(
			'vendor/datatables/css/dataTables.bootstrap4.min.css' => '_theme_',
		), 'css'));

		$this->template->inject_partial('modules_js', multi_asset(array(
			'vendor/datatables/js/jquery.dataTables.min.js' => '_theme_',
			'vendor/datatables/js/dataTables.bootstrap4.min.js' => '_theme_',
		), 'js'));
        // Form Validation
      	$this->form_validation->set_rules('pjbtKode', 'Kode Pejabat', 'required');
      	$this->form_validation->set_rules('pjbtNama', 'Nama Pejabat', 'required');
      	$this->form_validation->set_rules('pjbtJabatan', 'Jabatan', 'required');
      	$this->form_validation->set_rules('pjbNipm', 'NIPM', 'required');
      	$this->form_validation->set_rules('user_pejabat', 'Form Jenis', 'required');
        $this->form_validation->set_error_delimiters('<div class="help-block col-xs-12 col-sm-reset inline">', '</div>');
        if ($this->form_validation->run()) {

            $jenis_form = $this->input->post('user_pejabat');
            // dd($this->input->post());

            if($jenis_form == 'add_user_pejabat'){
                $username = $this->input->post('username');
                $email = $this->input->post('email');
                $password_input = $this->input->post('password');
                $password_input_re= $this->input->post('password_input_re');
   
                if ($password_input==$password_input_re) {
                    if ($this->Users->is_username_available($username)) {
                       // if ($this->Users->is_email_available($email)) {
                           
                           if ($this->input->post('password_uname')=='on') {
                               $password_hash = $this->authentication->password_hasher($username);
                           } else {
                               $password_hash = $this->authentication->password_hasher($password_input);
                           }
   
                           // dd($password_hash);
                           $group = array(4);
                           $is_default = 4;
                           $user_data = array(
                               'nama' => $this->input->post('pjbtNama'),
                               'username' => $this->input->post('username'),
                               'email' => $this->input->post('email'),
                               'password'  => $password_hash['encrypted'],
                               'salt'      => $password_hash['salt'],
                               'is_active' => 1,
                               'group'     => $group,
                               'unit_id' => $this->input->post('unit_id', TRUE),
                               'time'      => date("Y-m-d H:i:s"),
                               'is_default'=> $is_default
                           );
   
                           $params['data']  = array(	
                               'pjbtKode'	=> $this->input->post('pjbtKode'),
                               'pjbNipm'	=> $this->input->post('pjbNipm'),
                               'pjbtNama'	=> $this->input->post('pjbtNama'),
                               'pjbtJabatan'	=> $this->input->post('pjbtJabatan'),
                               'pjbUserCreate' => get_user_name(),
                               'pjbTglCreate' => date('Y-m-d H:i:s'),
                               'data_user' => $user_data,
                               'jenis_form' =>$jenis_form
                           );
   
                           $params['tables'] = 'pejabat_ref';
   
                           // dd($params);
                           $response = $this->m_pejabat->insert_form($params);

                           if ($response) {
                               $this->session->set_flashdata('msg', array('status' => 'success', 'title' => 'Informasi', 'message' => 'Data berhasil disimpan.'));
                            }else{
                               $this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Data gagal disimpan.'));
                            }
   
                       // }else {
                       //     $this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Maaf, Email sudah terdaftar.'));
                       // }
                   }else {
                       $this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Maaf, Username sudah terdaftar.'));
                   }
               }else {
                   $this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Maaf, Password dan Re-Password tidak sesuai.'));
               }
            }elseif($jenis_form == 'update_user_pejabat'){
                            
                $cekUser = $this->m_pejabat->cekUserByUserName($this->input->post('userName'));
                $user_data = array(
                    'userId' => $cekUser['UserId'],
                );

                $params['data']  = array(	
                    'pjbtKode'	=> $this->input->post('pjbtKode'),
                    'pjbtNama'	=> $this->input->post('pjbtNama'),
                    'pjbtJabatan'	=> $this->input->post('pjbtJabatan'),
                    'pjbUserCreate' => get_user_name(),
                    'pjbTglCreate' => date('Y-m-d H:i:s'),
                    'data_user' => $user_data,
                    'jenis_form' =>$jenis_form
                );

                $params['tables'] = 'pejabat_ref';
                $response = $this->m_pejabat->insert_form($params);
                // dd($response);
                if ($response) {
                    $this->session->set_flashdata('msg', array('status' => 'success', 'title' => 'Informasi', 'message' => 'Data berhasil disimpan.'));
                }else{
                    $this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Data gagal disimpan.'));
                }
            }
            
            
            redirect($this->module.'/add');

        }else{
            $this->template->title( 'Tambah Data Pejabat' );
			$this->template->set_breadcrumb( config_item('app_name') , '' );
			$this->template->set_breadcrumb( 'Referensi' , '' );
			$this->template->set_breadcrumb( 'Pejabat' , $this->module.'/index');
			$this->template->set_breadcrumb( 'Tambah Data' , '');


            

            $this->template->build(strtolower($this->module).'/v_add', $data);
        }


    }
	
    public function update($pjbId = NULL) {
        if(is_null( $pjbId )) show_404();
        if(is_null( $get_data = $this->m_pejabat->get_data(array('pjbId' => ' = '. decode($pjbId) ), NULL, NULL, NULL, NULL) )) show_404();

        

        $data['module']     = $this->module;
        $data['data']       = $get_data->row();
        // dd($data);
        // Form Validation
        $this->form_validation->set_rules('pjbtKode', 'Kode Pejabat', 'required');
        $this->form_validation->set_rules('pjbtNama', 'Nama Pejabat', 'required');
        $this->form_validation->set_rules('pjbtJabatan', 'Jabatan', 'required');
        $this->form_validation->set_rules('pjbNipm', 'NIPM', 'required');
      	$this->form_validation->set_rules('user_pejabat', 'Form Jenis', 'required');
        $this->form_validation->set_error_delimiters('<div class="help-block col-xs-12 col-sm-reset inline">', '</div>');

        if ($this->form_validation->run()) {

            // dd($this->input->post());

            $jenis_form = $this->input->post('user_pejabat');
            if($jenis_form == 'add_user_pejabat'){
                $username = $this->input->post('username');
                $email = $this->input->post('email');
                $password_input = $this->input->post('password');
                $password_input_re= $this->input->post('password_input_re');

                if ($password_input==$password_input_re) {
                    if ($this->Users->is_username_available($username)) {
                       // if ($this->Users->is_email_available($email)) {
                           
                           if ($this->input->post('password_uname')=='on') {
                               $password_hash = $this->authentication->password_hasher($username);
                           } else {
                               $password_hash = $this->authentication->password_hasher($password_input);
                           }
   
                           // dd($password_hash);
                           $group = array(4);
                           $is_default = 4;
                           $user_data = array(
                               'nama' => $this->input->post('pjbtNama'),
                               'username' => $this->input->post('username'),
                               'email' => $this->input->post('email'),
                               'password'  => $password_hash['encrypted'],
                               'salt'      => $password_hash['salt'],
                               'is_active' => 1,
                               'group'     => $group,
                               'unit_id' => $this->input->post('unit_id', TRUE),
                               'time'      => date("Y-m-d H:i:s"),
                               'is_default'=> $is_default
                           );
   
                           $params['data']  = array(	
                               'pjbtKode'	=> $this->input->post('pjbtKode'),
                               'pjbtNama'	=> $this->input->post('pjbtNama'),
                               'pjbNipm'	=> $this->input->post('pjbNipm'),
                               'pjbtJabatan'	=> $this->input->post('pjbtJabatan'),
                               'pjbUserUpdate' => get_user_name(),
                               'pjbTglUpdate' => date('Y-m-d H:i:s'),
                               'data_user' => $user_data,
                               'jenis_form' =>$jenis_form
                           );
   
                           $params['tables'] = 'pejabat_ref';

                           $filter = array('pjbId' => decode($pjbId));
   
                           // dd($params);
                           $response = $this->m_pejabat->update_form($params, $filter);

                           if ($response) {
                               $this->session->set_flashdata('msg', array('status' => 'success', 'title' => 'Informasi', 'message' => 'Data berhasil disimpan.'));
                            }else{
                               $this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Data gagal disimpan.'));
                            }
   
                       // }else {
                       //     $this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Maaf, Email sudah terdaftar.'));
                       // }
                   }else {
                       $this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Maaf, Username sudah terdaftar.'));
                   }
               }else {
                   $this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Maaf, Password dan Re-Password tidak sesuai.'));
               }


            }elseif($jenis_form == 'update_user_pejabat'){
                $cekUser = $this->m_pejabat->cekUserByUserName($this->input->post('userName'));
                $user_data = array(
                    'userId' => $cekUser['UserId'],
                );

                $params['data']  = array(	
                    'pjbtKode'	=> $this->input->post('pjbtKode'),
                    'pjbtNama'	=> $this->input->post('pjbtNama'),
                    'pjbNipm'	=> $this->input->post('pjbNipm'),
                    'pjbtJabatan'	=> $this->input->post('pjbtJabatan'),
                    'pjbUserUpdate' => get_user_name(),
                    'pjbTglUpdate' => date('Y-m-d H:i:s'),
                    'data_user' => $user_data,
                    'jenis_form' =>$jenis_form
                );

                $params['tables'] = 'pejabat_ref';
                $filter = array('pjbId' => decode($pjbId));
   
                // dd($params);
                $response = $this->m_pejabat->update_form($params, $filter);
                // dd($response);
                if ($response) {
                    $this->session->set_flashdata('msg', array('status' => 'success', 'title' => 'Informasi', 'message' => 'Data berhasil disimpan.'));
                }else{
                    $this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Data gagal disimpan.'));
                }
            }
            


           redirect($this->module);

        }else{
            $this->template->title( 'Ubah Data Pejabat' );
			$this->template->set_breadcrumb( config_item('app_name') , '' );
			$this->template->set_breadcrumb( 'Referensi' , '' );
			$this->template->set_breadcrumb( 'Pejabat' , $this->module);
			$this->template->set_breadcrumb( 'Ubah Data' , '');


            $this->template->build(strtolower($this->module).'/v_update', $data);
        }
    }


    public function delete($data) {
		$restrict = restrict( $this->module.'/delete', TRUE);
        $action = TRUE;

        $params['tables'] = 'pejabat_ref';

        
        if($restrict == TRUE){
            foreach($data as $val){
                $params['data'] = array('pjbId' => decode($val)) ;
                if($this->m_form->delete_form($params)){
                    
                } else {
                    $action = FALSE;
                    break;
                }
            }
            if($action == TRUE){
                $records["customActionStatus"] = "success"; 
                $records["customActionMessage"] = "Data yang anda pilih berhasil dihapus!";
            } else {
                $records["customActionStatus"] = "warning"; 
                $records["customActionMessage"] = "Data yang anda pilih gagal untuk dihapus!, silahkan cek kembali apakah data yang anda pilih sudah digunakan pada data yang lain seperti pada data mahasiswa.";
            } 
        }else {
            $records["customActionStatus"] = "warning";
            $records["customActionMessage"] = "Maaf, anda tidak memperoleh akses untuk aksi ini!";
        }

        echo json_encode($records);
	}
}


/* End of file Pejabat.php */
/* Location: D:\laragon\www\bsi-devel\uad\e-office-uad\application\modules\master\controllers\Pejabat.php */