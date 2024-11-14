<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Config extends Admin_Controller {

	function __construct() {
        parent::__construct();
		// loadmodel
		restrict();
    }
	
	public function index()
	{			
		$tpl['module'] = 'system/Config';
		
		$this->template->inject_partial('modules_css', multi_asset(array(
			'vendor/datatables/css/dataTables.bootstrap4.min.css' => '_theme_',
		), 'css'));

		$this->template->inject_partial('modules_js', multi_asset(array(
			'vendor/datatables/js/jquery.dataTables.min.js' => '_theme_',
			'vendor/datatables/js/dataTables.bootstrap4.min.js' => '_theme_',
		), 'js'));

		$this->template->title( 'Pengaturan Data Umum' );
		$this->template->set_breadcrumb( 'Dashboard' , 'dashboard' );
		$this->template->set_breadcrumb( 'Pengaturan' , 'dashboard' );
		$this->template->set_breadcrumb( 'Data Umum' , 'system/Config/index');
		
		$this->template->build('system/v_config_index', $tpl);
	}
	
	function ajax($action = NULL)
    {
		$this->load->model('system/m_config');
		
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		
		if($action == 'datatables'){
			$columns = array(
				0 => 'ConfigCode',
				1 => 'ConfigType',
				2 => 'ConfigValue',
				
			);
			
			$object = array();
			
			
			$order = array();
			if($this->input->post('order')){
				foreach( $this->input->post('order') as $row => $val){
					$order[$columns[$val['column']]] = $val['dir'];
				}
			}
			$length = ($this->input->post('length') == -1) ? NULL : $this->input->post('length');
			
			$qry = $this->m_config->get_config($object, $length, $this->input->post('start'), $order, NULL);
			$iTotalRecords = (!is_null($qry)) ? intval($this->m_config->get_config($object, NULL, NULL, array(), 'counter')) : 0;
			$iDisplayStart = intval($this->input->post('start'));
			$sEcho = intval($this->input->post('draw'));
			
			
			$records = array();
			$records["data"] = array(); 
			if(!is_null($qry)){
				foreach($qry->result_array() as $row){
					$records["data"][] = array(
						/* '<input type="checkbox" name="id[]" value="'. $row['ConfigId'] .'">', */
						$row['ConfigName'],
						$row['ConfigType'],
						$row['ConfigValue'],
						'<a href="'. site_url( 'system/Config/update/'. $row['ConfigId'] ) .'" class="btn btn-square btn-round btn-warning" data-provide="tooltip" title="Ubah"><i class="fa fa-pencil"></i></a>',
					);
				}
			}
			
			$records["draw"] = $sEcho;
			$records["recordsTotal"] = $iTotalRecords;
			$records["recordsFiltered"] = $iTotalRecords;

			echo json_encode($records);
		}
	}
	
	public function update( $config_id = NULL )
	{
		$this->load->model('system/m_config');	
		if(is_null( $config_id )) show_404();
		if(is_null( $data_config = $this->m_config->get_config(array('ConfigId' => ' = '. $config_id ), NULL, NULL, NULL, NULL) )) show_404();
									
		$tpl['module'] = 'system/Config';
		$tpl['data'] = $data_config->row();
		
		$this->form_validation->set_rules('ConfigType', 'Text SMS', 'required');
		$this->form_validation->set_error_delimiters('<div class="help-block col-xs-12 col-sm-reset inline">', '</div>');
		if ($this->form_validation->run()) {
			$jenis = $this->input->post('ConfigType');

			
			if($jenis == 'file'){
				$config = array(
					'upload_path'   => $this->config->item('upload_path').'pengaturan/',
					'allowed_types' => $this->config->item('file_allowed_types'),
					'max_size'	=> $this->config->item('file_max_size'),
					'overwrite'     => TRUE,
				);
				
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('ConfigValueFile')) {
					$this->session->set_flashdata('msg', array('status' => 'error', 'title' => 'Peringatan', 'message' => 'File Gagal Di Upload'));
					redirect('system/Config/update/'. $config_id);
				}else{
					$files = $this->upload->data();
					$data  = array(	
						'ConfigValue'	=> $files['file_name'],
					);
					
					$status = $this->m_config->update_config($data, array('ConfigId' =>  $config_id)) ;
					$this->session->set_flashdata('msg', array('status' => 'success', 'title' => 'Peringatan', 'message' => 'File Berhasil Di Upload'));
				}
			}else{
				$data  = array(	
					'ConfigValue'	=> $this->input->post('ConfigValueText'),
				);
				$status = $this->m_config->update_config($data, array('ConfigId' =>  $config_id)) ;
			}
			
			if($status) {
				$this->session->set_flashdata('msg', array('status' => 'success', 'title' => 'Informasi', 'message' => 'Data berhasil disimpan.'));
			} else {
				$this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Data gagal disimpan.'));
			}
			redirect('system/Config/update/'. $config_id);
		} else {
			$this->template->title( 'Update Pengaturan Umum' );
			$this->template->set_breadcrumb( 'Dashboard' , 'dashboard' );
			$this->template->set_breadcrumb( 'Pengaturan Data' , 'system/Config/index');
			$this->template->set_breadcrumb( 'Update Data' , 'system/Config/update/'. $config_id);
			$this->template->build('system/v_config_update', $tpl);
		}
	}
}