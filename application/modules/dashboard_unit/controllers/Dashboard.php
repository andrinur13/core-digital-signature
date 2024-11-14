<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends Unit_Kerja_Controller
{

	private $module = 'dashboard_unit';


	function __construct()
	{
		parent::__construct();

		$this->load->model($this->module . '/M_dashboard');
	}

	public function index()
	{
		protect_acct();


		$tpl['module'] = $this->module;
		$tpl['total_surat_masuk'] = $this->M_dashboard->getCountSuratMasuk();
		$tpl['total_surat_masuk_belum_proses'] = $this->M_dashboard->getCountSuratMasukBelumProses();
		$tpl['total_surat_keluar'] = $this->M_dashboard->getCountSuratKeluar();

		$this->template->title('Selamat Datang');
		$this->template->set_breadcrumb('Beranda', site_url('dashboard'), 'ace-icon fa fa-home home-icon blue');
		$this->template->build($this->module . '/v_dashboard_index', $tpl);
	}
}
