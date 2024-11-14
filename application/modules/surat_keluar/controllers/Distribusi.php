<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Distribusi extends Dashboard_Controller
{

	private $module = 'surat_keluar';

	function __construct()
	{
		parent::__construct();
		// loadmodel
		$this->load->model($this->module . '/M_distribusi');
		protect_acct();
	}

	function index()
	{
		restrict($this->module . '/Distribusi/index');
		$tpl['module'] = $this->module . '/Distribusi';

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

		$this->template->build($this->module . '/v_distribusi_index', $tpl);
	}

	function datatables_data()
	{
		restrict($this->module . '/Distribusi/datatables_data');
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

		$columns = array(
			0 => 'srtId',
			1 => 'srtTglDraft',
			2 => 'srtNomorSurat',
			3 => 'srtPerihal',
			4 => 'jnsrtNama',
			5 => 'srtStatusId',
			6 => 'distId',
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
		$qry = $this->M_distribusi->get_daftar_surat($unit_id, $object, $length, $this->input->post('start'), $order);
		$iTotalRecords = (!is_null($qry)) ? intval($this->M_distribusi->get_daftar_surat($unit_id, $object, NULL, NULL, NULL, 'counter')) : 0;
		$iDisplayStart = intval($this->input->post('start'));
		$sEcho = intval($this->input->post('draw'));

		$records = array();
		$records["data"] = array();
		if (!is_null($qry)) {
			$no = $iDisplayStart + 1;
			foreach ($qry->result_array() as $row) {

				$btn_detail = '<a href="#" title="Detail" id="detail-btn" data-id="' . encode($row['srtId']) . '" class="btn btn-square btn-round detail-btn btn-info" ><i class="fa fa-eye"></i></a> ';

				$btn_distribusi = '<a href="#" title="Distribusi" id="distribusi-btn" data-id="' . encode($row['srtId']) . '" class="btn btn-square btn-round detail-btn btn-dark" ><i class="fa fa-paper-plane"></i></a> ';

				$stat_surat = '<div class="btn btn-sm btn-bold btn-round btn-flat w-100px btn-' . $row['stColor'] . '">' . $row['stNama'] . '</div>';

				$stat_distribusi = '<div class="btn btn-sm btn-bold btn-round btn-flat w-100px btn-secondary">BELUM</div>';
				if ($row['distribusi_id'] != '') {
					$stat_distribusi = '<div class="btn btn-sm btn-bold btn-round btn-flat w-100px btn-success">SUDAH</div>';
				}
				$records["data"][] = array(
					$no++,
					IndonesianDate($row['srtTglDraft']),
					$row['srtNomorSurat'],
					$row['srtPerihal'],
					$row['jnsrtNama'],
					$stat_surat,
					$stat_distribusi,
					$btn_detail . $btn_distribusi
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
		restrict($this->module . '/Distribusi/detail');
		if (is_null($encId)) redirect($this->module);
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$id = decode($encId);
		$data = $this->M_distribusi->get_detail_surat($id);
		$distribusi = $this->M_distribusi->get_distribusi_surat($id);

		$tpl['module'] = $this->module . '/Distribusi/';
		$tpl['data'] = $data;
		$tpl['distribusi'] = $distribusi;
		$tpl['path'] = $this->config->item('upload_path');

		$this->load->view($this->module . '/v_distribusi_detail', $tpl);
	}

	function distribusi($id_enc)
	{
		$id = decode($id_enc);
		if ($this->input->post('action') == 'submit') {
			$this->form_validation->set_rules('penerima[]', 'Nama Penerima', 'required');
			// $this->form_validation->set_rules('email[]', 'Email', 'required');
			// $this->form_validation->set_rules('no_wa[]', 'Nomor WhatsApp', 'required');

			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run() == true) {
				$params = array(
					'penerima' => $this->input->post('penerima'),
					'email' => $this->input->post('email'),
					'no_wa' => $this->input->post('no_wa'),
					'user' => $this->session->userdata('user_name'),
					'datetime' => date("Y-m-d H:i:s"),
					'id' => $id
				);
				// print_r($params);
				// die;
				$proses = $this->M_distribusi->do_distribusi($params);

				if ($proses) {
					$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Distribusi Surat Keluar berhasil.');
				} else {
					$result = array('error' => 'null', 'status' => false, 'type' => 'error', 'text' => 'Distribusi Surat Keluar gagal.');
				}
				$this->output->set_content_type('application/json')->set_output(json_encode($result));
			} else {
				$error = array(
					'penerima' => form_error('penerima[]'),
					// 'email' => form_error('email[]'),
					// 'no_wa' => form_error('no_wa'),
				);
				$result = array('error' => $error);
				echo json_encode($result);
			}
		} else {
			$tpl['module'] = $this->module . '/Distribusi/';
			$tpl['surat_id'] = encode($id);

			$this->load->view($this->module . '/v_distribusi_update', $tpl);
		}
	}
}
