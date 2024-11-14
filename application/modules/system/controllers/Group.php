<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Group extends Admin_Controller {

	private $module = 'system';

	function __construct() {
        parent::__construct();
		// loadmodel
		$this->load->model($this->module.'/m_group');
		//restrict();
   }

	public function index()
	{
		protect_acct();
		$tpl['module'] = 'system/Group';

		$this->template->inject_partial('modules_css', multi_asset( array(
                                                      //'css/components-md.css' => '_theme_',
                                                         'vendor/datatables/css/dataTables.bootstrap4.min.css' => '_theme_',
                                                      ), 'css' ) );

      $this->template->inject_partial('modules_js', multi_asset( array(
                                                'vendor/datatables/js/jquery.dataTables.min.js' => '_theme_',
                                                'vendor/datatables/js/dataTables.bootstrap4.min.js' => '_theme_',
                                             ), 'js' ) );
		$this->template->title( 'Group' );
		$this->template->set_breadcrumb( 'Dashboard' , 'system/dashboard/index' );
		$this->template->set_breadcrumb( 'Group' , '');

		$this->template->build($this->module.'/v_group_index', $tpl);
	}

	function datatables_group()
    {
		protect_acct();
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$columns = array(
         0 => 'GroupId',
			1 => 'GroupName',
			2 => 'GroupDescription'
		);

		$object = array();
      $search = $this->input->post('search');
      if($search['value'] != '') {
         $object['GroupName'] = $search['value'];
      }

		$order = array();
		if($this->input->post('order')){
			foreach( $this->input->post('order') as $row => $val){
				$order[$columns[$val['column']]] = $val['dir'];
			}
		}
		$length = ($this->input->post('length') == -1) ? NULL : $this->input->post('length');

		$qry = $this->m_group->get_group($object, $length, $this->input->post('start'), $order);
		$iTotalRecords = (!is_null($qry)) ? intval($this->m_group->get_group($object, NULL, NULL, NULL, 'counter')) : 0;
		$iDisplayStart = intval($this->input->post('start'));
		$sEcho = intval($this->input->post('draw'));


		$records = array();
		$records["data"] = array();
		if(!is_null($qry)){

			foreach($qry->result_array() as $row){
				$records["data"][] = array(
               $row['GroupId'],
					$row['GroupName'],
					$row['GroupDescription'],
					'<a href="'. site_url( 'system/group/update/'. $row['GroupId'] ) .'" class="btn btn-square btn-round btn-warning" data-provide="tooltip" title="Edit Group"><i class="fa fa-pencil"></i></a>',
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
		protect_acct();
		$this->load->helper('system/group');

		$this->form_validation->set_rules('GroupName', 'Nama Group', 'required');
		$this->form_validation->set_rules('GroupDescription', 'Deskripsi Group', 'required');
		$this->form_validation->set_error_delimiters('<div class="help-block col-xs-12 col-sm-reset inline">', '</div>');
		if ($this->form_validation->run()) {
			$save  = array(
							'GroupName'			=> $this->input->post('GroupName'),
							'GroupDescription'	=> $this->input->post('GroupDescription'),
							'menu' 				=>  (isset($_POST['menu'])) ? $this->input->post('menu') : NULL,
							'datetime' 				=>  date("Y-m-d H:i:s"),
							'userId' 				=>  get_user_name()
						);
			// print_r($save); exit;
			if( $this->m_group->input_data_group($save) ) {
				$this->session->set_flashdata('message_form', array('status' => FALSE, 'type' => 'success' , 'title' => 'Informasi!' , 'text' => 'Data Group berhasil ditambahkan.'));
			} else {
				$this->session->set_flashdata('message_form', array('status' => FALSE, 'type' => 'danger' , 'title' => 'Peringatan!' , 'text' => 'Data Group gagal ditambahkan.'));
			}
			redirect('system/Group');
		}

		$this->template->inject_partial('modules_css', multi_asset( array(
																	#'css/JChecktree/jquery-checktree.css' => NULL,
																	), 'css' ) );

		$this->template->inject_partial('modules_js', multi_asset( array(
																#'js/JChecktree/jquery-checktree.js' => NULL,
															), 'js' ) );

		$tpl['module'] = 'system/group/add';
		$tpl['group_menu'] = $this->m_group->get_menu(NULL, array('MenuParentId' => 'ASC', 'MenuOrder' => 'ASC', 'MenuName' => 'ASC'));
		#$tpl['group_unit'] = $this->m_group->get_unit(NULL);

		$this->template->title( 'Group' );
		//$this->template->set_breadcrumb( config_item('app_name') , '' );
		$this->template->set_breadcrumb( 'Dashboard' , 'admin/dashboard/index' );
		$this->template->set_breadcrumb( 'Group' , 'admin/group/index');
		$this->template->set_breadcrumb( 'Tambah' , '');

		$this->template->build($this->module.'/v_group_add', $tpl);

	}

	public function update( $group_id =  NULL )
	{
		protect_acct();
		if ( is_null($group_id) ) show_404();
		if(is_null( $data_group = $this->m_group->get_group(array( 'GroupId' => $group_id )))) show_404();
		$this->load->helper('system/group');


		$tpl['group_data'] = $data_group->row(); ;
		$tpl['module'] = 'system/group/update';
		$tpl['group_menu'] = $this->m_group->get_menu(NULL, array('MenuParentId' => 'ASC', 'MenuOrder' => 'ASC', 'MenuName' => 'ASC'));
		$tpl['group_menu_update'] = $this->m_group->get_group_menu(array('GroupDetailGroupId' =>  $group_id ));
		#$tpl['group_unit'] = $this->m_group->get_unit(NULL);

		$this->form_validation->set_rules('GroupName', 'Nama Group', 'required');
		$this->form_validation->set_rules('GroupDescription', 'Deskripsi Group', 'required');
		$this->form_validation->set_error_delimiters('<div class="help-block col-xs-12 col-sm-reset inline">', '</div>');
		if ($this->form_validation->run()) {								// validation ok
			$update  = array(
							'GroupName'			=> $this->input->post('GroupName'),
							'GroupDescription'	=> $this->input->post('GroupDescription'),
							'menu' 				=>  (isset($_POST['menu'])) ? $this->input->post('menu') : NULL,
							'datetime' 				=>  date("Y-m-d H:i:s"),
							'userId' 				=>  get_user_name(),
							'GroupId' 				=>  $group_id,
						);
			if( $this->m_group->update_data_group($update) ) {
				$this->session->set_flashdata('message_form', array('status' => FALSE, 'type' => 'success' , 'title' => 'Informasi!' , 'text' => 'Data Group berhasil diubah.'));
			} else {
				$this->session->set_flashdata('message_form', array('status' => FALSE, 'type' => 'danger' , 'title' => 'Peringatan!' , 'text' => 'Data Group gagal diubah.'));
			}
			redirect('system/Group');
		}

		$this->template->title( 'Group' );
		$this->template->set_breadcrumb( 'Dashboard' , 'admin/dashboard/index' );
		$this->template->set_breadcrumb( 'Group' , 'admin/group/index');
		$this->template->set_breadcrumb( 'Ubah' , '');

		$this->template->build($this->module.'/v_group_update', $tpl);
	}
}