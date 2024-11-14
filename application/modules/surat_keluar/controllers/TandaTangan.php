<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TandaTangan extends Dashboard_Controller
{

	private $module = 'surat_keluar';

	function __construct()
	{
		parent::__construct();
		// loadmodel
		$this->load->model($this->module . '/M_tanda_tangan');
		protect_acct();
	}

	function index()
	{
		restrict($this->module . '/TandaTangan/index');
		$tpl['module'] = $this->module . '/TandaTangan';
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

		$this->template->title('Tanda Tangan Surat Keluar');
		// $this->template->set_breadcrumb('Dashboard', 'system/dashboard/index');
		$this->template->set_breadcrumb('Tanda Tangan Surat Keluar', '');

		$this->template->build($this->module . '/v_tanda_tangan_index', $tpl);
	}

	function datatables_data()
	{
		restrict($this->module . '/TandaTangan/datatables_data');
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
		$qry = $this->M_tanda_tangan->get_daftar_surat($tgl_awal, $tgl_akhir, $unit_id, $object, $length, $this->input->post('start'), $order);
		$iTotalRecords = (!is_null($qry)) ? intval($this->M_tanda_tangan->get_daftar_surat($tgl_awal, $tgl_akhir, $unit_id, $object, NULL, NULL, NULL, 'counter')) : 0;
		$iDisplayStart = intval($this->input->post('start'));
		$sEcho = intval($this->input->post('draw'));

		$records = array();
		$records["data"] = array();
		if (!is_null($qry)) {
			$no = $iDisplayStart + 1;
			foreach ($qry->result_array() as $row) {
				$btn_proses = '<a data-provide="tooltip" data-original-title="Generate Nomor dan Tanda Tangan" href="#" id="signature-btn" data-id="' . encode($row['srtId']) . '" data-href="' . site_url($this->module . '/signature/' . encode($row['srtId'])) . '" class="btn btn-square btn-round btn-brown" ><i class="fa fa-pencil-square-o"></i></a> ';

				$btn_detail = '<a href="#" title="Detail" id="detail-btn" data-id="' . encode($row['srtId']) . '" class="btn btn-square btn-round detail-btn btn-info" ><i class="fa fa-eye"></i></a> ';

				$records["data"][] = array(
					$no++,
					IndonesianDate($row['srtTglDraft']),
					$row['sifdisNama'],
					'<span class="text-success">' . $row['srtNomorSurat'] . '<span>',
					$row['srtPerihal'],
					$row['jnsrtNama'],
					'<div data-provide="tooltip" data-placement="top" title="" data-original-title="" class="btn btn-sm btn-bold btn-round btn-flat w-100px btn-' . $row['stColor'] . '">' . $row['stNama'] . '</div>',
					($row['srtPejabatPtdNama'] != '' && $row['srtIsSigned'] == '1') ? '<i class="fa fa-check-circle fa-lg text-success" title="Sudah Tanda tangan"></i>' : '',
					$btn_proses . $btn_detail
				);
			}
		}

		$records["draw"] = $sEcho;
		$records["recordsTotal"] = $iTotalRecords;
		$records["recordsFiltered"] = $iTotalRecords;

		echo json_encode($records);
	}

	function signature($id_enc)
	{
		$id = decode($id_enc);
		$username = $this->session->userdata('user_name');
		$user_id = $this->session->userdata('user_id');
		$unit_id = $this->session->userdata('user_unit_id');

		$tpl['module'] = $this->module . '/TandaTangan';
		$detail = $this->M_tanda_tangan->get_detail_surat($id);

		if ($this->input->post('action_final') == 'submit') {
			$this->form_validation->set_rules('file_final', 'Unggah Surat Final', 'required');

			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run() == true) {
				if ($_FILES['file_surat_final']['name'] != '') {
					$upload_file = $this->DoUploadDokumen('file_surat_final', rand(0, 10000) . date('YmdHis') . '-suratfinal-' . preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['file_surat_final']['name']));
					if ($upload_file != false) {
						$dokumen = $upload_file;
						if (is_file($this->config->item('upload_path') . $this->input->post('file_final_uploaded'))) {
							unlink($this->config->item('upload_path') . $this->input->post('file_final_uploaded'));
						}
					} else {
						$this->session->set_flashdata('msg_form', array('status' => false, 'type' => 'danger', 'text' => 'Upload file final gagal.'));
					}
				} else {
					$dokumen = $this->input->post('file_final_uploaded');
				}

				$params = array(
					'dokumen' => $dokumen,
					'user' => $username,
					'datetime' => date("Y-m-d H:i:s"),
					'id' => $id,
				);
				// print_r($params);
				// die;
				$proses = $this->M_tanda_tangan->do_signature($params);
				if ($proses) {
					$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Surat Keluar berhasil ditandatangani.');
				} else {
					$result = array('error' => 'null', 'status' => false, 'type' => 'error', 'text' => 'Surat Keluar gagal ditandatangani.');
				}
				$this->output->set_content_type('application/json')->set_output(json_encode($result));
			} else {
				$error = array(
					'file_final' => form_error('file_final'),
				);
				$result = array('error' => $error);
				echo json_encode($result);
			}
		} else {
			if (is_null($rs_data = $this->M_tanda_tangan->get_detail_surat($id))) show_404();
			// print_r($rs_data);
			$tpl['module'] = $this->module . '/TandaTangan/';
			$tpl['data'] = $rs_data;
			$tpl['path_file'] = $this->config->item('upload_path');
			$tpl['ref_pejabat'] = $this->M_tanda_tangan->get_ref_pejabat();
			$tpl['ref_ttd'] = array(0 => array('id' => '0', 'name' => 'Digital'), 1 => array('id' => '1', 'name' => 'Basah'));

			$tpl['surat_id'] = encode($id);
			$tpl['dt_kolom_surat'] = $this->M_tanda_tangan->get_detail_kolom_surat($id);

			$template = $this->config->item('upload_path') . '/template_surat/' . $detail['jnsrtTemplate'];
			$tpl['is_file_template'] = (is_file($template)) ? true : false;

			$tpl['detail_pejabat'] = $this->M_tanda_tangan->get_detail_pejabat($detail['srtPejabatPtdId']);

			$this->template->inject_partial('modules_css', multi_asset(array(
				'vendor/datatables/css/dataTables.bootstrap4.min.css' => '_theme_',
			), 'css'));

			$this->template->inject_partial('modules_js', multi_asset(array(
				'vendor/datatables/js/jquery.dataTables.min.js' => '_theme_',
				'vendor/datatables/js/dataTables.bootstrap4.min.js' => '_theme_',
			), 'js'));

			$this->load->view($this->module . '/v_tanda_tangan_update', $tpl);
		}
	}

	function update_nomor($id_enc)
	{
		$id = decode($id_enc);
		$username = $this->session->userdata('user_name');
		$tpl['module'] = $this->module . '/TandaTangan';
		$detail = $this->M_tanda_tangan->get_detail_surat($id);
		// print_r($_POST);
		// die;
		if ($this->input->post('action_nomor') == 'submit') {
			$this->form_validation->set_rules('nomor', 'Nomor', 'required');
			$this->form_validation->set_rules('penandatangan', 'Penandatangan', 'required');
			$this->form_validation->set_rules('pejabat_nama', 'Pejabat Nama', 'required');
			$this->form_validation->set_rules('pejabat_nipm', 'Pejabat NIPM', 'required');
			$this->form_validation->set_rules('pejabat_jabatan', 'Pejabat Jabatan', 'required');

			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run() == true) {
				$params = array(
					'nomor' => $this->input->post('nomor'),
					'penandatangan' => $this->input->post('penandatangan'),
					'pejabat_nama' => $this->input->post('pejabat_nama'),
					'pejabat_nipm' => $this->input->post('pejabat_nipm'),
					'pejabat_jabatan' => $this->input->post('pejabat_jabatan'),
					'user' => $username,
					'datetime' => date("Y-m-d H:i:s"),
					'id' => $id,
				);
				// print_r($params);
				// die;
				$proses = $this->M_tanda_tangan->do_update_nomor($params);

				if ($proses['status'] == true) {
					# generate new document jika menggunakan template
					$msg_generate = '';
					if ($detail['srtUseTemplate']) {
						$generate_surat = $this->generate_surat(encode($id));
						if ($generate_surat['status'] == TRUE) {
							#update filename surat
							$this->M_tanda_tangan->do_update_filename_surat($id, $generate_surat['filename']);
							unlink($this->config->item('upload_path') . $this->input->post('filename_surat_uploaded'));
							$msg_generate = 'File Surat berhasil digenerate.';
						} else {
							$msg_generate = 'File Surat gagal digenerate.';
						}
						$filename_surat = $generate_surat['filename'];
					} else {
						$filename_surat = $detail['srtFile'];
					}

					$result = array('error' => 'null', 'status' => TRUE, 'type' => 'success', 'text' => 'Nomor Surat Keluar berhasil disimpan. ' . $msg_generate, 'nomor' => $proses['data'], 'filename_surat' => $filename_surat);
				} else {
					$result = array('error' => 'null', 'status' => FALSE, 'type' => 'error', 'text' => 'Nomor Surat Keluar gagal disimpan.', 'nomor' => $proses['data']);
				}
				$this->output->set_content_type('application/json')->set_output(json_encode($result));
			} else {
				$error = array(
					'nomor' => form_error('nomor'),
					'penandatangan' => form_error('penandatangan'),
					'pejabat_nama' => form_error('pejabat_nama'),
					'pejabat_nipm' => form_error('pejabat_nipm'),
					'pejabat_jabatan' => form_error('pejabat_jabatan'),
				);
				$result = array('error' => $error);
				echo json_encode($result);
			}
		}
	}

	function detail($encId = NULL)
	{
		if (is_null($encId)) redirect($this->module);
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$id = decode($encId);
		$detail = $this->M_tanda_tangan->get_detail_surat($id);

		$tpl['module'] = $this->module . '/TandaTangan/';
		$tpl['data'] = $detail;
		$tpl['path'] = $this->config->item('upload_path');
		$tpl['dt_referensi_surat'] = $this->M_tanda_tangan->get_data_referensi_surat($id);
		$tpl['dt_kolom_surat'] = $this->M_tanda_tangan->get_detail_kolom_surat($id);

		$this->load->view($this->module . '/v_tanda_tangan_detail', $tpl);
	}

	function generate($idsurat_enc, $ptd_id)
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

		$nomor = NULL;
		$detail = $this->M_tanda_tangan->get_detail_surat(decode($idsurat_enc));
		$dt_pejabat = $this->M_tanda_tangan->get_detail_pejabat($ptd_id);
		// var_dump($nomor_surat);
		$tahun = date('Y');

		$rs_last_number = $this->M_tanda_tangan->get_data_last_number_surat($detail['srtKlasifikasiId'], $tahun); // nomor terakhir surat tiap tahun sesuai kode klasifikasi
		$last_no = $rs_last_number['last_number'] + 1; // jangan lupa diganti hasil numbering auto_counter
		$number = ($last_no < 10) ? '0' . $last_no : $last_no;
		$bulan = $this->bulanRomawi(date('m'));
		$number = $dt_pejabat['pjbKode'] . '/' . $detail['unit_asal_kode']  . '/' . $number . '/' . $detail['klasKode'] . '/' . $bulan . '/' . $tahun;
		$nomor = array('nomor' => $number, 'ptd' => $dt_pejabat);
		// print_r($detail);
		// die;
		echo json_encode($nomor);
	}

	function view_by_file($file_name)
	{
		if (is_null($file_name))  show_404();

		$path = $this->config->item('upload_path');

		$data['path'] = $path;
		$data['dokumen'] = $file_name;
		$this->load->view($this->module . '/v_lihat_file', $data);
	}

	// function unduh_surat($id_enc)
	// {
	// 	$id = decode($id_enc);
	// 	$detail = $this->M_tanda_tangan->get_detail_surat($id);
	// }

	function generate_surat($id_enc)
	{
		$id = decode($id_enc);
		$detail = $this->M_tanda_tangan->get_detail_surat($id);
		$path_template = $this->config->item('upload_path') . 'template_surat/';
		$template = $path_template . $detail['jnsrtTemplate'];

		// if (!is_file($template)) {
		// 	$this->session->set_flashdata('msg_form', array('status' => true, 'type' => 'danger', 'title' => 'Peringatan', 'text' => 'Template Surat tidak tersedia. Mohon menghubungi Adminstrator.'));
		// 	redirect(site_url($this->module . '/Konsep'));
		// }

		$defautl_variable = array(
			'%NOMOR_SURAT%',
			'%TANGGAL_SURAT%',
			'%PERIHAL%',
			'%ISI_SURAT%',
			'%JENIS_SURAT%',
			'%NAMA_PEJABAT%',
			'%NIPM_PEJABAT%',
			'%JABATAN%',
		);

		$rs_kolom_surat = $this->M_tanda_tangan->get_detail_kolom_surat($id);
		$adds_variable = array_column($rs_kolom_surat, 'kolVariable');

		$all_variable = array_merge($defautl_variable, $adds_variable);

		$content = array(
			$detail['srtNomorSurat'],
			IndonesianDate($detail['srtTglDraft']),
			$detail['srtPerihal'],
			$detail['srtIsiRingkasan'],
			$detail['jnsrtNama'],
			$detail['srtPejabatPtdNama'],
			$detail['srtPejabatPtdNipm'],
			$detail['srtPejabatPtdJabatan'],
		);

		$adds_content = array_column($rs_kolom_surat, 'surkolKonten');
		$all_content = array_merge($content, $adds_content);

		$file = file_get_contents($template);
		$document = str_replace($all_variable, $all_content, $file);

		$filename = str_replace(" ", "", $detail['jnsrtNama']) . '-' . str_replace(".", "", str_replace("/", "_", $detail['srtNomorSurat'])) . '.doc';
		file_put_contents($this->config->item('upload_path') . $filename, $document);
		if (is_file($this->config->item('upload_path') . $filename)) {
			$result = array('status' => TRUE, 'filename' => $filename);
		} else {
			$result = array('status' => FALSE, 'filename' => $filename);
		}
		return $result;
	}

	private function DoUploadDokumen($file, $filename)
	{
		$config = array(
			'upload_path' => $this->config->item('upload_path'),
			'allowed_types' => $this->config->item('file_allowed_types'),
			'max_size' => $this->config->item('file_max_size'),
			'overwrite' => TRUE,
			'file_name' => $filename
		);
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if ($this->upload->do_upload($file)) {
			return $this->upload->data('file_name');
		} else {
			return false;
		}
	}

	public function bulanRomawi($bln)
	{
		switch ($bln) {
			case 1:
				return "I";
				break;
			case 2:
				return "II";
				break;
			case 3:
				return "III";
				break;
			case 4:
				return "IV";
				break;
			case 5:
				return "V";
				break;
			case 6:
				return "VI";
				break;
			case 7:
				return "VII";
				break;
			case 8:
				return "VIII";
				break;
			case 9:
				return "IX";
				break;
			case 10:
				return "X";
				break;
			case 11:
				return "XI";
				break;
			case 12:
				return "XII";
				break;
		}
	}
}
