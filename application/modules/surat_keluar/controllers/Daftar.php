<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Daftar extends Dashboard_Controller
{

	private $module = 'surat_keluar';

	function __construct()
	{
		parent::__construct();
		// loadmodel
		$this->load->model($this->module . '/M_daftar');
		protect_acct();
	}

	function index()
	{
		restrict($this->module . '/Daftar/index');
		$tpl['module'] = $this->module . '/Daftar';
		$tpl['ref_status_baca'] = array(0 => array('id' => 'sudah', 'name' => 'Sudah'), 1 => array('id' => 'belum', 'name' => 'Belum'));
		$tpl['ref_sifat'] = $this->M_daftar->get_ref_sifat();
		$tpl['ref_tujuan'] = $this->M_daftar->get_ref_tujuan();

		// set tanggal 2 pekan
		$tpl['tgl_awal'] = date('d/m/Y', strtotime("-7 days", strtotime(date('Y-m-d'))));
		$tpl['tgl_akhir'] = date('d/m/Y', strtotime("+7 days", strtotime(date('Y-m-d'))));

		$this->template->inject_partial('modules_css', multi_asset(array(
			'vendor/datatables/css/dataTables.bootstrap4.min.css' => '_theme_',
		), 'css'));

		$this->template->inject_partial('modules_js', multi_asset(array(
			'vendor/datatables/js/jquery.dataTables.min.js' => '_theme_',
			'vendor/datatables/js/dataTables.bootstrap4.min.js' => '_theme_',
		), 'js'));

		$this->template->title('Daftar Surat Keluar');
		// $this->template->set_breadcrumb('Dashboard', 'system/dashboard/index');
		$this->template->set_breadcrumb('Daftar Surat Keluar', '');

		$this->template->build($this->module . '/v_daftar_index', $tpl);
	}

	function datatables_data()
	{
		restrict($this->module . '/Daftar/datatables_data');
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

		$columns = array(
			0 => 'srtId',
			1 => 'srtTglDraft',
			2 => 'srtSifatSurat',
			3 => 'srtNomorSurat',
			4 => 'srtPerihal',
			5 => 'jnsrtNama',
			6 => 'tujuan',
			7 => 'srtIsSigned'
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

		if ($this->input->post('filter_sifat') != '') {
			$object['srtSifatSurat'] = '=' . $this->input->post('filter_sifat');
		}

		$kategori = $this->input->post('filter_kategori');

		if ($this->input->post('filter_status') != '') {
			$object['srtStatusId'] = '=' . $this->input->post('filter_status');
		}

		$tujuan = $this->input->post('filter_tujuan');

		$status_baca = $this->input->post('filter_status_baca');

		$tgl_awal = ($this->input->post('filter_tanggal_awal') != '') ? date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('filter_tanggal_awal')))) : date('d/m/Y', strtotime("-7 days", strtotime(date('Y-m-d'))));
		$tgl_akhir = ($this->input->post('filter_tanggal_akhir') != '') ? date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('filter_tanggal_akhir')))) : date('d/m/Y', strtotime("+7 days", strtotime(date('Y-m-d'))));

		$unit_id = NULL;
		$user_group = $this->session->userdata('user_group');
		if ($user_group != '0') {
			$unit_id = $this->session->userdata('user_unit_id');
		}

		$length = ($this->input->post('length') == -1) ? NULL : $this->input->post('length');
		$qry = $this->M_daftar->get_daftar_surat($tujuan, $status_baca, $tgl_awal, $tgl_akhir, $unit_id, $object, $length, $this->input->post('start'), $order);
		$iTotalRecords = (!is_null($qry)) ? intval($this->M_daftar->get_daftar_surat($tujuan, $status_baca, $tgl_awal, $tgl_akhir, $unit_id, $object, NULL, NULL, NULL, 'counter')) : 0;
		$iDisplayStart = intval($this->input->post('start'));
		$sEcho = intval($this->input->post('draw'));

		$records = array();
		$records["data"] = array();
		if (!is_null($qry)) {
			$no = $iDisplayStart + 1;
			foreach ($qry->result_array() as $row) {
				$clr_is_baca = 'btn-secondary';
				if ($row['srtTglBaca'] != '') {
					$clr_is_baca = 'btn-info';
				}
				$btn_detail = '<a href="#" title="Detail" id="detail-btn" data-id="' . encode($row['srtId']) . '" class="btn btn-square btn-round detail-btn ' . $clr_is_baca . '" ><i class="fa fa-eye"></i></a> ';

				$kategori = ($row['kategori'] == 'Internal') ? '<span class="badge badge-xs badge-success">Internal</span>' : '<span class="badge badge-xs badge-danger">Eksternal</span>';

				$btn_arsip = "";
				if ($row['srtNomorSurat'] != '' && $row['srtPejabatPtdNama'] != '' && $row['srtIsSigned'] == '1') {
					$btn_arsip = '<a title="Arsipkan" id="arsipkan-btn" data-id="' . encode($row['srtId']) . '" class="btn btn-square btn-round arsipkan-btn btn-secondary"><i class="fa fa-file-text-o"></i></a> ';
				}
				if ($row['arsId'] != '') {
					$btn_arsip = '<a href="#" title="Sudah Arsipkan" class="btn btn-square btn-round btn-success" ><i class="fa fa-file-text-o"></i></a>';
				}

				$records["data"][] = array(
					$no++,
					IndonesianDate($row['srtTglDraft']),
					$row['sifdisNama'],
					$row['srtNomorSurat'],
					$row['srtPerihal'],
					$row['jnsrtNama'],
					$kategori . '<br>' . $row['tujuan'],
					($row['srtIsSigned'] == '1') ? '<i class="fa fa-check-circle fa-lg text-success"></i>' : '',
					$btn_detail . $btn_arsip
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
		if (is_null($encId)) redirect($this->module);
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$id = decode($encId);
		$username = $this->session->userdata('user_name');
		$user_id = $this->session->userdata('user_id');

		$detail = $this->M_daftar->get_detail_surat($id);
		// do update status baca (once)
		if ($detail['srtTglBaca'] == '') {
			$update_status_baca = $this->M_daftar->do_update_status_baca(array('srtUserBaca' => $username, 'srtTglBaca' => date('Y-m-d H:i:s')), $id);
		}
		$dt_referensi_surat = $this->M_daftar->get_data_referensi_surat($id);

		$tpl['module'] = $this->module . '/Daftar/';
		$tpl['data'] = $detail;
		$tpl['dt_referensi_surat'] = $dt_referensi_surat;
		$tpl['path'] = $this->config->item('upload_path');

		$this->load->view($this->module . '/v_daftar_detail', $tpl);
	}

	function view_by_file($file_name)
	{
		if (is_null($file_name))  show_404();

		$path = $this->config->item('upload_path');

		$data['path'] = $path;
		$data['dokumen'] = $file_name;
		$this->load->view($this->module . '/v_lihat_file', $data);
	}

	function arsipkan($idsurat_enc)
	{
		$proses = background_arsip($idsurat_enc);
		if ($proses) {
			$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Surat berhasil diarsipkan.');
		} else {
			$result = array('error' => 'null', 'status' => false, 'type' => 'error', 'text' => 'Surat gagal diarsipkan.');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($result));
	}
}
