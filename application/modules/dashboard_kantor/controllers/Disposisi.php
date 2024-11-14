<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Disposisi extends Dashboard_Controller {

	private $module = 'dashboard';

	function __construct() 
	{
		parent::__construct();

		$this->load->model($this->module.'/M_dashboard');
		//Do your magic here
	}

	public function index()
	{
		protect_acct();
		
		
		$this->template->inject_partial('modules_css', multi_asset( array(
			'vendor/datatables/css/dataTables.bootstrap4.min.css' => '_theme_', 
		), 'css' ) );

		$this->template->inject_partial('modules_js', multi_asset( array(
		'vendor/datatables/js/jquery.dataTables.min.js' => '_theme_',
		'vendor/datatables/js/dataTables.bootstrap4.min.js' => '_theme_',
		'vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js' => '_theme_',
		), 'js' ) );


		$tpl['module'] = $this->module.'/Disposisi';
		$this->template->title( 'Disposisi Surat' );
		$this->template->set_breadcrumb( 'Disposisi' , site_url('dashboard'), 'ace-icon fa fa-home home-icon blue' );
		$this->template->build($this->module. '/v_disposisi_index', $tpl);
	}
}


/* End of file Disposisi.php */
/* Location: D:\laragon\www\uad\arsip-muhammadiyah\application\modules\dashboard\controllers\Disposisi.php */