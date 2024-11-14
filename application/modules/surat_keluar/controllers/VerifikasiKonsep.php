<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class VerifikasiKonsep extends Dashboard_Controller
{

	private $module = 'surat_keluar';

	function __construct()
	{
		parent::__construct();
		// loadmodel
		$this->load->model($this->module . '/M_verifikasi_konsep');
		protect_acct();
	}

	function index()
	{
		restrict($this->module . '/VerifikasiKonsep/index');
		$tpl['module'] = $this->module . '/VerifikasiKonsep';
		// print_r();
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

		$this->template->title('Verifikasi Konsep Surat Keluar');
		// $this->template->set_breadcrumb('Dashboard', 'system/dashboard/index');
		$this->template->set_breadcrumb('Verifikasi Konsep Surat Keluar', '');

		$this->template->build($this->module . '/v_verifikasi_konsep_index', $tpl);
	}

	function datatables_data()
	{
		restrict($this->module . '/VerifikasiKonsep/datatables_data');
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

		$columns = array(
			0 => 'srtId',
			1 => 'srtTglDraft',
			2 => 'srtSifatSurat',
			3 => 'srtNomorSurat',
			4 => 'srtPerihal',
			5 => 'jnsrtNama',
			6 => 'tujuan',
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

		// if ($this->input->post('filter_sifat') != '') {
		// 	$object['srtSifatSurat'] = '=' . $this->input->post('filter_sifat');
		// }

		// $kategori = $this->input->post('filter_kategori');

		// if ($this->input->post('filter_status') != '') {
		// 	$object['srtStatusId'] = '=' . $this->input->post('filter_status');
		// }

		// $tujuan = $this->input->post('filter_tujuan');

		// $status_baca = $this->input->post('filter_status_baca');

		$tgl_awal = ($this->input->post('filter_tanggal_awal') != '') ? date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('filter_tanggal_awal')))) : date('d/m/Y', strtotime("-7 days", strtotime(date('Y-m-d'))));
		$tgl_akhir = ($this->input->post('filter_tanggal_akhir') != '') ? date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('filter_tanggal_akhir')))) : date('d/m/Y', strtotime("+7 days", strtotime(date('Y-m-d'))));

		$unit_id = NULL;
		$user_group = $this->session->userdata('user_group');
		if ($user_group != '0') {
			$unit_id = $this->session->userdata('user_unit_id');
		}

		$length = ($this->input->post('length') == -1) ? NULL : $this->input->post('length');
		$qry = $this->M_verifikasi_konsep->get_daftar_surat($tgl_awal, $tgl_akhir, $unit_id, $object, $length, $this->input->post('start'), $order);
		$iTotalRecords = (!is_null($qry)) ? intval($this->M_verifikasi_konsep->get_daftar_surat($tgl_awal, $tgl_akhir, $unit_id, $object, NULL, NULL, NULL, 'counter')) : 0;
		$iDisplayStart = intval($this->input->post('start'));
		$sEcho = intval($this->input->post('draw'));

		$records = array();
		$records["data"] = array();
		if (!is_null($qry)) {
			$no = $iDisplayStart + 1;
			foreach ($qry->result_array() as $row) {
				$btn_verifikasi = '';
				if ($row['srtStatusId'] != '4') {
					$btn_verifikasi = '<a data-provide="tooltip" data-original-title="Verifikasi" href="#" id="verifikasi-btn" data-id="' . encode($row['srtId']) . '" data-href="' . site_url($this->module . '/verifikasi/' . encode($row['srtId'])) . '" class="btn btn-square btn-round btn-warning" ><i class="fa fa-pencil"></i></a> ';
				}

				$btn_detail = '<a href="#" title="Detail" id="detail-btn" data-id="' . encode($row['srtId']) . '" class="btn btn-square btn-round detail-btn btn-info" ><i class="fa fa-eye"></i></a> ';

				$catatan = ($row['logsCatatan'] != '') ? 'Catatan: ' . $row['logsCatatan'] : '';
				$records["data"][] = array(
					$no++,
					IndonesianDate($row['srtTglDraft']),
					$row['sifdisNama'],
					$row['srtNomorSurat'],
					$row['srtPerihal'],
					$row['jnsrtNama'],
					'<div data-provide="tooltip" data-placement="top" title="" data-original-title="' . $catatan . '" class="btn btn-sm btn-bold btn-round btn-flat w-100px btn-' . $row['stColor'] . '">' . $row['stNama'] . '</div>',
					$btn_verifikasi . $btn_detail
				);
			}
		}

		$records["draw"] = $sEcho;
		$records["recordsTotal"] = $iTotalRecords;
		$records["recordsFiltered"] = $iTotalRecords;

		echo json_encode($records);
	}

	function update($id_enc)
	{
		// $this->load->helper('group/group');
		$id = decode($id_enc);
		$username = $this->session->userdata('user_name');
		$user_id = $this->session->userdata('user_id');
		$unit_id = $this->session->userdata('user_unit_id');
		$role_id = $this->session->userdata('user_role_id');
		// print_r($this->session->userdata);

		$tpl['module'] = $this->module . '/Konsep/add';

		if ($this->input->post('action') == 'submit') {
			$this->form_validation->set_rules('catatan', 'Catatan', 'required');
			$this->form_validation->set_rules('status', 'Status Surat', 'required');

			// if ($this->input->post('status') == '4') {
			// 	$this->form_validation->set_rules('pejabat', 'Pejabat Penanda Tangan', 'required');
			// 	// $this->form_validation->set_rules('ttd', 'Tanda Tangan', 'required');
			// }

			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run() == true) {
				$detail = $this->M_verifikasi_konsep->get_detail_surat($id);
				// if ($_FILES['file_ttd']['name'] != '') {
				// 	$upload_ttd = $this->DoUploadDokumen('file_ttd', rand(0, 10000) . '-ttd-' . preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['file_ttd']['name']));
				// 	if ($upload_ttd != false) {
				// 		$ttdfile = $upload_ttd;
				// 		if (is_file($this->config->item('upload_path') . $this->input->post('file_uploaded'))) {
				// 			unlink($this->config->item('upload_path') . $this->input->post('file_uploaded'));
				// 		}
				// 	} else {
				// 		$this->session->set_flashdata('msg_form', array('status' => false, 'type' => 'danger', 'text' => 'Upload file Tanda Tangan gagal.'));
				// 	}
				// } else {
				// 	$ttdfile = $this->input->post('file_ttd_uploaded');
				// }

				$log = false;
				if ($detail['srtStatusId'] != $this->input->post('status')) {
					$log = true;
				}
				$params = array(
					'catatan' => $this->input->post('catatan'),
					'status' => $this->input->post('status'),
					'pejabat' => $this->input->post('pejabat'),
					// 'ttd' => $this->input->post('ttd'),
					// 'ttd_isi' => $this->input->post('ttd_isi'),
					// 'ttd_file' => $ttdfile,
					'user' => $username,
					'datetime' => date("Y-m-d H:i:s"),
					'id' => $id,
					'log' => $log
				);
				// print_r($params);
				// die;
				$proses = $this->M_verifikasi_konsep->do_verifikasi($params);

				if ($proses) {
					$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Konsep Surat Keluar berhasil diverifikasi.');
				} else {
					$result = array('error' => 'null', 'status' => false, 'type' => 'error', 'text' => 'Konsep Surat Keluar gagal diverifikasi.');
				}
				$this->output->set_content_type('application/json')->set_output(json_encode($result));
			} else {
				$error1 = array(
					'catatan' => form_error('catatan'),
					'status' => form_error('status'),
				);
				$error2 = array(
					'pejabat' => form_error('pejabat'),
					// 'ttd' => form_error('ttd'),
				);

				$all_error = $error1;
				if ($this->input->post('status') == '3') {
					$all_error = array_merge($error1, $error2);
				}
				$result = array('error' => $all_error);
				$this->output->set_content_type('application/json')->set_output(json_encode($result));
			}
		} else {
			if (is_null($rs_data = $this->M_verifikasi_konsep->get_detail_surat($id))) show_404();
			// print_r($rs_data);
			$tpl['module'] = $this->module . '/VerifikasiKonsep/';
			$tpl['data'] = $rs_data;
			$tpl['ref_status'] = $this->M_verifikasi_konsep->get_ref_status($role_id);
			$tpl['path_file'] = $this->config->item('upload_path');
			$tpl['ref_pejabat'] = $this->M_verifikasi_konsep->get_ref_pejabat();
			$tpl['ref_ttd'] = array(0 => array('id' => '0', 'name' => 'Digital'), 1 => array('id' => '1', 'name' => 'Basah'));

			$tpl['surat_id'] = encode($id);
			$tpl['dt_kolom_surat'] = $this->M_verifikasi_konsep->get_detail_kolom_surat($id);

			$this->template->inject_partial('modules_css', multi_asset(array(
				'vendor/datatables/css/dataTables.bootstrap4.min.css' => '_theme_',
			), 'css'));

			$this->template->inject_partial('modules_js', multi_asset(array(
				'vendor/datatables/js/jquery.dataTables.min.js' => '_theme_',
				'vendor/datatables/js/dataTables.bootstrap4.min.js' => '_theme_',
			), 'js'));

			$this->load->view($this->module . '/v_verifikasi_konsep_update', $tpl);
		}
	}

	function detail($encId = NULL)
	{
		if (is_null($encId)) redirect($this->module);
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$id = decode($encId);
		$detail = $this->M_verifikasi_konsep->get_detail_surat($id);

		$tpl['module'] = $this->module . '/VerifikasiKonsep/';
		$tpl['data'] = $detail;
		$tpl['path'] = $this->config->item('upload_path');
		$tpl['dt_referensi_surat'] = $this->M_verifikasi_konsep->get_data_referensi_surat($id);
		$tpl['dt_kolom_surat'] = $this->M_verifikasi_konsep->get_detail_kolom_surat($id);

		$this->load->view($this->module . '/v_verifikasi_konsep_detail', $tpl);
	}

	function view_by_file($file_name)
	{
		if (is_null($file_name))  show_404();

		$path = $this->config->item('upload_path');

		$data['path'] = $path;
		$data['dokumen'] = $file_name;
		$this->load->view($this->module . '/v_lihat_file', $data);
	}
}
