<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends Admin_Controller
{

	private $module = 'dashboard';


	function __construct()
	{
		parent::__construct();

		$this->load->model($this->module . '/M_dashboard');
	}

	public function index()
	{
		protect_acct();

		if(get_unit_kerja_access()){
			redirect(site_url('/dashboard_unit/Dashboard'));
		}elseif(get_kantor_uad_access()){
			redirect(site_url('/dashboard_kantor/Dashboard'));
		}else{
			$tpl['module'] = $this->module;
			$tpl['jumlah_unit'] = $this->M_dashboard->get_jumlah_unit();
			$tpl['jumlah_user'] = $this->M_dashboard->get_jumlah_user();
	
			$this->template->title('Home');
			$this->template->set_breadcrumb('Beranda', site_url('dashboard'), 'ace-icon fa fa-home home-icon blue');
			$this->template->build($this->module . '/v_dashboard_index', $tpl);
		}

		
	}
}
