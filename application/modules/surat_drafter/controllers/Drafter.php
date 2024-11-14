<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

defined('BASEPATH') or exit('No direct script access allowed');

class Drafter extends Dashboard_Controller
{

	private $module = 'surat_drafter';


	function __construct()
	{
		parent::__construct();

		$this->load->model($this->module . '/M_drafter');
		$this->load->model('surat_masuk/M_drafter'); #surat masuk model
		restrict();
	}

	public function index()
	{

		$tpl['module'] = $this->module . '/Drafter';
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
		$this->template->title('Drafter Surat');
		$this->template->set_breadcrumb('Dashboard', 'dashboard/Dashboard/index');
		$this->template->set_breadcrumb('drafter', '');
		$this->template->build($this->module . '/v_index', $tpl);
	}

	public function datatables()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$unitId = get_user_unit_id();

		$columns = array(
			0 => 'srtId',
			1 => 'srtTglDraft',
			2 => 'dispSifatDisposisiId',
			3 => 'srtNomorSurat',
			4 => 'srtAsalSurat',
			5 => 'srtPerihal',
		);

		$isbaca = $this->input->post('isbaca', TRUE);
		$tanggal = $this->input->post('tanggal', TRUE);
		$kategori = $this->input->post('kategori', TRUE);
		$params = array(
			'isbaca' => $isbaca,
			'tanggal' => ($tanggal == '') ? '' : date('Y-m-d', strtotime($tanggal)),
			'kategori' => $kategori
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

		$object['srtUnitTujuanUtama'] = '=' . $unitId;

		if ($this->input->post('sifat', TRUE) != '') {
			$object['srtSifatSurat'] = '=' . $this->input->post('sifat', TRUE);
		}

		if ($this->input->post('asal', TRUE) != '') {
			$object['srtUnitAsalId'] = '=' . $this->input->post('asal', TRUE);
		}

		$length = ($this->input->post('length') == -1) ? NULL : $this->input->post('length');

		$qry = $this->M_drafter->get_list($params, $object, $length, $this->input->post('start'), $order, NULL);
		$iTotalRecords = (!is_null($qry)) ? intval($this->M_drafter->get_list($params, $object, NULL, NULL, NULL, 'counter')) : 0;
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

				$detail = '<a href="' . site_url($this->module . '/Drafter/detail/' . urlencode(encode($row['id']))) . '" class="text-warning">' . (($row['baca'] == '') ? '<span class="font-weight-bold">' . IndonesianDate($row['tanggal']) . '</span>' : IndonesianDate($row['tanggal'])) . '</a>';

				$records["data"][] = array(
					$detail,
					($row['baca'] == '') ? '<span class="font-weight-bold">' . $row['sifat'] . '</span>' : $row['sifat'],
					($row['baca'] == '') ? '<span class="font-weight-bold">' . $row['nomor'] . '</span>' : $row['nomor'],
					($row['baca'] == '') ? '<span class="font-weight-bold">' . $kategori . ' ' . $row['asal'] . '</span>' : $kategori . ' ' . $row['asal'],
					($row['baca'] == '') ? '<span class="font-weight-bold">' . $row['perihal'] . '</span>' : $row['perihal'],
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
		$id = decode(urldecode($id_enc));
		$detail = $this->M_drafter->detail($id);
		$tpl['module'] = $this->module . '/Drafter';
		$tpl['detail'] = $detail;
		$tpl['status'] = $this->M_drafter->status_tindakan($id);
		$tpl['path'] = $this->config->item('surat_masuk_path');
		$tpl['balasan'] = $this->M_drafter->surat_balasan($id);

		$tpl['is_admin'] = FALSE;
		if (get_user_group() == 1 || get_user_group() == 3) {
			$tpl['is_admin'] = TRUE;
		}

		#surat dibaca
		if ($detail['baca'] == '') {
			$data = array(
				'id' => $id,
				'data' => array(
					'srtUserBaca' => get_user_name(),
					'srtTglBaca' => date('Y-m-d H:i:s')
				)
			);
			$this->M_surat->baca_surat($data);
		}

		$this->template->inject_partial('modules_css', multi_asset(array(
			'vendor/datatables/css/dataTables.bootstrap4.min.css' => '_theme_',
			'vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css' => '_theme_',
		), 'css'));

		$this->template->inject_partial('modules_js', multi_asset(array(
			'vendor/datatables/js/jquery.dataTables.min.js' => '_theme_',
			'vendor/datatables/js/dataTables.bootstrap4.min.js' => '_theme_',
			'vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js' => '_theme_',
		), 'js'));

		$this->template->title('Detail Drafter Surat');
		$this->template->set_breadcrumb('Dashboard', 'dashboard/Dashboard/index');
		$this->template->set_breadcrumb('Detail', '');
		$this->template->build($this->module . '/v_detail', $tpl);
	}
}
