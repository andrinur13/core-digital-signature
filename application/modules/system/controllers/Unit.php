<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Unit extends Admin_Controller {

	function __construct() {
      parent::__construct();
		
      restrict();
		// loadmodel
      $this->load->model('system/m_unit');
    }
	
	public function index()
	{
		$this->load->helper('system/unit_kerja');
		$tpl['module'] = 'system/Unit';
		$tpl['dt_unit'] = $this->m_unit->get_unit(NULL, NULL, NULL, array('UnitParent' => 'ASC'));
		
		$this->template->inject_partial('modules_js', multi_asset( array(
																		'plugin/treetable/jquery.treetable.js' => '_theme_',
																		'js/bootbox.min.js' => '_theme_',
																		), 'js' ) );	
		$this->template->inject_partial('modules_css', multi_asset( array(
																		'plugin/treetable/jquery.treetable.css' => '_theme_',
																		'plugin/treetable/jquery.treetable.theme.default.css' => '_theme_',
																		), 'css' ) );
		
		
		$this->template->title( 'Unit' );
		$this->template->set_breadcrumb( config_item('app_name') , '' );
		$this->template->set_breadcrumb( 'System' , '' );
		$this->template->set_breadcrumb( 'Unit' , 'system/Unit/index');
		
		$this->template->build('system/v_unit_index', $tpl);
	}
	
	public function add( )
	{
		$tpl['module'] = 'system/Unit';
		
		$this->form_validation->set_rules('unitNama', 'Nama Unit', 'required');
		$this->form_validation->set_rules('unitKode', 'Kode Unit', '');
		$this->form_validation->set_rules('unitParent', 'Parent', '');
		$this->form_validation->set_error_delimiters('<div class="help-block col-xs-12 col-sm-reset inline">', '</div>');
		if ($this->form_validation->run()) {
			$data  = array(	
							'UnitParent' => $this->input->post('unitParent'),
							'UnitKode' 	=> $this->input->post('unitKode'),
							'UnitName'	=> $this->input->post('unitNama'),
							'UnitAddUser'	=> get_user_name(),
							'UnitAddTime'	=> date('Y-m-d H:i:s'),
						);
		
			if( $this->m_unit->input_unit($data) ) {
				$this->session->set_flashdata('msg', array('status' => 'success', 'title' => 'Informasi', 'message' => 'Data berhasil disimpan.'));
			} else {
				$this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Data gagal disimpan.'));
			}
			redirect('system/Unit/add');
		} else {
			$tpl['dt_parent_unit'] 	= $this->m_unit->get_unit();
			
			$this->template->inject_partial('modules_css', multi_asset( array(
																	'css/select2.css' => '_theme_',
																	), 'css' ) );
																			
			$this->template->inject_partial('modules_js', multi_asset( array(
																	'js/select2.min.js' => '_theme_',
																), 'js' ) );
			
			$this->template->title( 'Tambah Unit' );
			
			$this->template->set_breadcrumb( config_item('app_name') , '' );
			$this->template->set_breadcrumb( 'System' , '' );
			$this->template->set_breadcrumb( 'Unit' , 'system/Unit/index');
			$this->template->set_breadcrumb( 'Tambah Data' , 'system/Unit/add/');
			
			$this->template->build('system/v_unit_add', $tpl);
		}
	}
	
	public function update( $unit_id = NULL )
	{
		if(is_null( $unit_id )) show_404();
		if(is_null( $data_unit = $this->m_unit->get_unit(array('UnitId' => ' = '. $unit_id )) )) show_404();
		
		$tpl['module'] = 'system/Unit';
		
		$this->form_validation->set_rules('unitNama', 'Nama Unit', 'required');
		$this->form_validation->set_rules('unitKode', 'Kode Unit', '');
		$this->form_validation->set_rules('unitParent', 'Parent', '');
		$this->form_validation->set_error_delimiters('<div class="help-block col-xs-12 col-sm-reset inline">', '</div>');
		if ($this->form_validation->run()) {
			$data  = array(	
							'UnitParent' => $this->input->post('unitParent'),
							'UnitKode' 	=> $this->input->post('unitKode'),
							'UnitName'	=> $this->input->post('unitNama'),
							'UnitUpdateUser' => get_user_name(),
							'UnitUpdateTime' => date('Y-m-d H:i:s'),
						);
		
			if( $this->m_unit->update_unit($data, array('UnitId' => $unit_id)) ) {
				$this->session->set_flashdata('msg', array('status' => 'success', 'title' => 'Informasi', 'message' => 'Data berhasil disimpan.'));
			} else {
				$this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Data gagal disimpan.'));
			}
			redirect('system/Unit/');
		} else {
			$tpl['dt_parent_unit'] 	= $this->m_unit->get_unit();
			$tpl['data_unit'] 	= $data_unit->row();
			
			$this->template->inject_partial('modules_css', multi_asset( array(
																	'css/select2.css' => '_theme_',
																	), 'css' ) );
																			
			$this->template->inject_partial('modules_js', multi_asset( array(
																	'js/select2.min.js' => '_theme_',
																), 'js' ) );
			
			$this->template->title( 'Edit Unit' );
			
			$this->template->set_breadcrumb( config_item('app_name') , '' );
			$this->template->set_breadcrumb( 'System' , '' );
			$this->template->set_breadcrumb( 'User' , 'system/Unit/index');
			$this->template->set_breadcrumb( 'Edit Data' , 'system/Unit/add/');
			
			$this->template->build('system/v_unit_update', $tpl);
		}
	}
	
	function delete( $unit_id = NULL )
	{
		if(is_null( $unit_id )) show_404();
		if(is_null( $data_unit = $this->m_unit->get_unit(array('UnitId' => ' = '. $unit_id )) )) show_404();
		
		if( $this->m_unit->delete_unit(array( 'UnitId' => $unit_id ))) {
			$this->session->set_flashdata('msg', array('status' => 'success', 'title' => 'Informasi', 'message' => 'Data unit berhasil untuk dihapus.'));
		} else {
			$this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan' , 'message' => 'Data unit gagal untuk dihapus.'));
		}
		
		redirect('system/Unit');
	}
}