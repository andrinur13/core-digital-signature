<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class DisposisiKeluar extends Dashboard_Controller
{

	private $module = 'surat_keluar';

	function __construct()
	{
		parent::__construct();
		// loadmodel
		$this->load->model($this->module . '/M_disposisi_keluar');
		protect_acct();
	}

	function index()
	{
		restrict($this->module . '/DisposisiKeluar/index');
		$tpl['module'] = $this->module . '/DisposisiKeluar';

		$this->template->inject_partial('modules_css', multi_asset(array(
			'vendor/datatables/css/dataTables.bootstrap4.min.css' => '_theme_',
		), 'css'));

		$this->template->inject_partial('modules_js', multi_asset(array(
			'vendor/datatables/js/jquery.dataTables.min.js' => '_theme_',
			'vendor/datatables/js/dataTables.bootstrap4.min.js' => '_theme_',
		), 'js'));

		$this->template->title('Disposisi Keluar');
		// $this->template->set_breadcrumb('Dashboard', 'system/dashboard/index');
		$this->template->set_breadcrumb('Disposisi Keluar', '');

		$this->template->build($this->module . '/v_disposisi_keluar_index', $tpl);
	}

	function datatables_data()
	{
		restrict($this->module . '/DisposisiKeluar/datatables_data');
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

		$columns = array(
			0 => 'srtId',
			1 => 'dispTglCreate',
			2 => 'srtNomorSurat',
			3 => 'srtSifatSurat',
			4 => 'srtPerihal',
		);

		$object = array();
		//$object['UserName'] = '!='.get_user_name();
		$search = $this->input->post('search');
		if ($search['value'] != '') {
			$object['filter_key'] = $search['value'];
		}

		$order = array();
		if ($this->input->post('order')) {
			foreach ($this->input->post('order') as $row => $val) {
				$order[$columns[$val['column']]] = $val['dir'];
			}
		}

		$unit_id = NULL;
		$user_group = $this->session->userdata('user_group');
		if ($user_group != '0') {
			$unit_id = $this->session->userdata('user_unit_id');
		}

		$length = ($this->input->post('length') == -1) ? NULL : $this->input->post('length');
		$qry = $this->M_disposisi_keluar->get_daftar_surat($unit_id, $object, $length, $this->input->post('start'), $order);
		$iTotalRecords = (!is_null($qry)) ? intval($this->M_disposisi_keluar->get_daftar_surat($unit_id, $object, NULL, NULL, NULL, 'counter')) : 0;
		$iDisplayStart = intval($this->input->post('start'));
		$sEcho = intval($this->input->post('draw'));

		$records = array();
		$records["data"] = array();
		if (!is_null($qry)) {
			$no = $iDisplayStart + 1;
			foreach ($qry->result_array() as $row) {

				$btn_detail = '<a href="#" title="Detail" id="detail-btn" data-id="' . encode($row['srtId']) . '" class="btn btn-square btn-round detail-btn btn-info" ><i class="fa fa-eye"></i></a> ';
				$records["data"][] = array(
					$no++,
					IndonesianDate($row['dispTglCreate']),
					$row['srtNomorSurat'],
					$row['sifdisNama'],
					$row['srtPerihal'],
					$btn_detail
				);
			}
		}

		$records["draw"] = $sEcho;
		$records["recordsTotal"] = $iTotalRecords;
		$records["recordsFiltered"] = $iTotalRecords;

		echo json_encode($records);
	}

	function detail($encId = NULL)
	{
		restrict($this->module . '/DisposisiKeluar/detail');
		if (is_null($encId)) redirect($this->module);
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$id = decode($encId);
		$disposisi = $this->M_disposisi_keluar->get_disposisi_surat($id);
		$disposisi_unit = $this->M_disposisi_keluar->get_disposisi_unit_surat($disposisi['dispId']);

		$tpl['module'] = $this->module . '/DisposisiKeluar/';
		$tpl['disposisi'] = $disposisi;
		$tpl['disposisi_unit'] = $disposisi_unit;
		$tpl['path'] = $this->config->item('upload_path');

		$this->load->view($this->module . '/v_disposisi_keluar_detail', $tpl);
	}
}
