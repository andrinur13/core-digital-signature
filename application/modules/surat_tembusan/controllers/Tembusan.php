<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

defined('BASEPATH') or exit('No direct script access allowed');

class Tembusan extends Dashboard_Controller
{

	private $module = 'surat_tembusan';


	function __construct()
	{
		parent::__construct();

		$this->load->model($this->module . '/M_tembusan');
		restrict();
	}

	public function index()
	{

		$tpl['module'] = $this->module . '/Tembusan';
		$tpl['path'] = $this->config->item('surat_masuk_path');

		$this->template->inject_partial('modules_css', multi_asset(array(
			'vendor/datatables/css/dataTables.bootstrap4.min.css' => '_theme_',
			'vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css' => '_theme_',
		), 'css'));

		$this->template->inject_partial('modules_js', multi_asset(array(
			'vendor/datatables/js/jquery.dataTables.min.js' => '_theme_',
			'vendor/datatables/js/dataTables.bootstrap4.min.js' => '_theme_',
			'vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js' => '_theme_',
		), 'js'));

		// $this->template->set_layout('layout_kantor_univ.php');
		$this->template->title('Tembusan');
		$this->template->set_breadcrumb('Dashboard', 'dashboard/Dashboard/index');
		$this->template->set_breadcrumb('Tembusan', '');
		$this->template->build($this->module . '/v_index', $tpl);
	}

	public function datatables()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$unitId = get_user_unit_id();

		$columns = array(
			0 => 'srtId',
			1 => '',
			2 => '',
			3 => '',
			4 => '',
			5 => '',
		);

		$isbaca = $this->input->post('isbaca', TRUE);
		$tanggal = $this->input->post('tanggal', TRUE);
		$kategori = $this->input->post('kategori', TRUE);
		$params = array(
			'isbaca' => $isbaca,
			'tanggal' => ($tanggal == '') ? '' : date('Y-m-d', strtotime($tanggal)),
			'kategori' => $kategori,
			'unit' => $unitId
		);

		$object = array();
		$filter_key = $this->input->post('search');
		if ($filter_key['value'] != '') {
			$object['filter_key'] = $filter_key['value'];
		}

		$order = array();
		if ($this->input->post('order')) {
			foreach ($this->input->post('order') as $row => $val) {
				$order[$columns[$val['column']]] = $val['dir'];
			}
		}

		if ($this->input->post('sifat', TRUE) != '') {
			$object['srtSifatSurat'] = '=' . $this->input->post('sifat', TRUE);
		}

		if ($this->input->post('asal', TRUE) != '') {
			$object['srtUnitAsalId'] = '=' . $this->input->post('asal', TRUE);
		}

		$length = ($this->input->post('length') == -1) ? NULL : $this->input->post('length');

		$qry = $this->M_tembusan->get_list($params, $object, $length, $this->input->post('start'), $order, NULL);
		$iTotalRecords = (!is_null($qry)) ? intval($this->M_tembusan->get_list($params, $object, NULL, NULL, NULL, 'counter')) : 0;
		$iDisplayStart = intval($this->input->post('start'));
		$isEcho = intval($this->input->post('draw'));

		$records = array();
		$records["data"] = array();
		if (!is_null($qry)) {
			$no = $iDisplayStart + 1;
			foreach ($qry->result_array() as $row) {
				$file = '';
				if ($row['file'] != '') {
					$file = '<a href="#" type="button"  class="btn btn-square btn-round btn-warning" data-toggle="tooltip" title="buka file surat"><i class="fa fa-file" onclick="file(' . "'" . $row['file'] . "'" . ')" style="cursor:pointer"></i></a>';
				}

				$detail = '<a href="' . site_url($this->module . '/Tembusan/detail/' . urlencode(encode($row['id']))) . '" class="text-warning">' . IndonesianDate($row['tanggal']) . '
				</a>';

				$records["data"][] = array(
					$detail,
					$row['sifat'],
					$row['nomor'],
					$row['asal'],
					$row['perihal'],
					$file,
				);
			}
		}
		$records["draw"] = $isEcho;
		$records["recordsTotal"] = $iTotalRecords;
		$records["recordsFiltered"] = $iTotalRecords;

		echo json_encode($records);
	}

	public function detail($id_enc)
	{
		$id = urldecode(decode($id_enc));
		$unitId = get_user_unit_id();

		#background proses arsip
		background_arsip($id_enc);

		$tpl['module'] = $this->module . '/Tembusan';
		$tpl['detail'] = $this->M_tembusan->detail($id, $unitId);
		$tpl['path'] = $this->config->item('surat_masuk_path');

		$this->template->inject_partial('modules_css', multi_asset(array(
			'vendor/datatables/css/dataTables.bootstrap4.min.css' => '_theme_',
			'vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css' => '_theme_',
		), 'css'));

		$this->template->inject_partial('modules_js', multi_asset(array(
			'vendor/datatables/js/jquery.dataTables.min.js' => '_theme_',
			'vendor/datatables/js/dataTables.bootstrap4.min.js' => '_theme_',
			'vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js' => '_theme_',
		), 'js'));

		$this->template->title('Detail Tembusan');
		$this->template->set_breadcrumb('Dashboard', 'dashboard/Dashboard/index');
		$this->template->set_breadcrumb('Detail', '');
		$this->template->build($this->module . '/v_detail', $tpl);
	}
}
