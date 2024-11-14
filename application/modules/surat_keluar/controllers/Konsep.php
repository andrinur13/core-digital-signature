<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Konsep extends Dashboard_Controller
{

	private $module = 'surat_keluar';

	function __construct()
	{
		parent::__construct();
		// loadmodel
		$this->load->model($this->module . '/M_konsep');
		$this->load->model($this->module . '/M_verifikasi_konsep');
		protect_acct();
	}

	function index()
	{
		restrict($this->module . '/Konsep/index');
		// print_r($this->session->userdata());
		// $generate_surat = $this->generate_surat('TrnCB_a3yO0C_IG9Z7a_x4-vJwx7FzzyoukHp0yUoZsSJg', 'test_doc');
		// var_dump$generate_surat['file_name']);
		// die;
		$tpl['module'] = $this->module . '/Konsep';
		// $tpl['moduleAdd'] = $this->module . '/Konsep/add';
		$tpl['ref_sifat'] = $this->M_konsep->get_ref_sifat();
		$tpl['ref_kategori'] = array(0 => array('id' => 'internal', 'name' => 'Internal'), 1 => array('id' => 'eksternal', 'name' => 'Eksternal'));
		$tpl['ref_status'] = $this->M_konsep->get_ref_status();

		// set tanggal  2 pekan
		$tpl['tgl_awal'] = date('d/m/Y', strtotime("-7 days", strtotime(date('Y-m-d'))));
		$tpl['tgl_akhir'] = date('d/m/Y', strtotime("+7 days", strtotime(date('Y-m-d'))));

		$this->template->inject_partial('modules_css', multi_asset(array(
			'vendor/datatables/css/dataTables.bootstrap4.min.css' => '_theme_',
		), 'css'));

		$this->template->inject_partial('modules_js', multi_asset(array(
			'vendor/datatables/js/jquery.dataTables.min.js' => '_theme_',
			'vendor/datatables/js/dataTables.bootstrap4.min.js' => '_theme_',
		), 'js'));

		$this->template->title('Konsep Surat Keluar');
		$this->template->set_breadcrumb('Dashboard', 'dashboard_kantor/Dashboard/index');
		$this->template->set_breadcrumb('Konsep Surat Keluar', '');

		$this->template->build($this->module . '/v_konsep_index', $tpl);
	}

	function datatables_data()
	{
		// restrict($this->module . '/Konsep/datatables_data');
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

		$columns = array(
			0 => 'srtId',
			1 => 'srtTglDraft',
			2 => 'srtNomorSurat',
			3 => 'srtNomorSurat',
			4 => 'srtPerihal',
			5 => 'jnsrtNama',
			6 => 'tujuan',
			7 => 'stId',
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


		$tgl_awal = ($this->input->post('filter_tanggal_awal') != '') ? date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('filter_tanggal_awal')))) : date('d/m/Y', strtotime("-7 days", strtotime(date('Y-m-d'))));
		$tgl_akhir = ($this->input->post('filter_tanggal_akhir') != '') ? date('Y-m-d', strtotime(str_replace("/", "-", $this->input->post('filter_tanggal_akhir')))) : date('d/m/Y', strtotime("+7 days", strtotime(date('Y-m-d'))));

		$unit_id = NULL;
		$user_group = $this->session->userdata('user_group');
		if ($user_group != '0') {
			$unit_id = $this->session->userdata('user_unit_id');
		}

		$length = ($this->input->post('length') == -1) ? NULL : $this->input->post('length');
		$qry = $this->M_konsep->get_konsep_surat($tgl_awal, $tgl_akhir, $kategori, $unit_id, $object, $length, $this->input->post('start'), $order);
		$iTotalRecords = (!is_null($qry)) ? intval($this->M_konsep->get_konsep_surat($tgl_awal, $tgl_akhir, $kategori, $unit_id, $object, NULL, NULL, NULL, 'counter')) : 0;
		$iDisplayStart = intval($this->input->post('start'));
		$sEcho = intval($this->input->post('draw'));

		$records = array();
		$records["data"] = array();
		if (!is_null($qry)) {
			$no = $iDisplayStart + 1;
			foreach ($qry->result_array() as $row) {
				$btn_update = '';
				if ($row['stId'] == '1' or $row['stId'] == '2') {
					$btn_update = '<a data-provide="tooltip" data-original-title="Ubah Konsep Surat" href="#" id="ubah-btn" data-id="' . encode($row['srtId']) . '" data-href="' . site_url($this->module . '/update/' . encode($row['srtId'])) . '" class="btn btn-square btn-round btn-warning" ><i class="fa fa-pencil"></i></a> ';
				}

				$btn_detail = '<a href="#" data-provide="tooltip" data-original-title="Detail" id="detail-btn" data-id="' . encode($row['srtId']) . '" class="btn btn-square btn-round btn-info detail-btn" ><i class="fa fa-eye"></i></a> ';

				$btn_delete = '';
				if ($row['stId'] == '1') {
					// $btn_delete = '<a href="' . site_url($this->module . '/Konsep/delete/' . $row['srtId']) . '" id="delete" class="btn btn-square btn-round btn-danger" data-provide="tooltip" title="Delete Surat"><i class="ti-trash"></i></a> ';
				}


				$catatan = ($row['logsCatatan'] != '') ? 'Catatan: ' . $row['logsCatatan'] : '';
				$stat_konsep = '<div data-provide="tooltip" data-placement="top" title="" data-original-title="' . $catatan . '" class="btn btn-sm btn-bold btn-round btn-flat w-100px btn-' . $row['stColor'] . '">' . $row['stNama'] . '</div>';

				$kategori = ($row['kategori'] == 'Internal') ? '<span class="badge badge-xs badge-success">Internal</span>' : '<span class="badge badge-xs badge-danger">Eksternal</span>';
				$records["data"][] = array(
					($no++) . '.',
					IndonesianDate($row['srtTglDraft']),
					$row['srtNomorSurat'],
					$row['sifdisNama'],
					$row['srtPerihal'],
					$row['jnsrtNama'],
					$kategori . '<br>' . $row['tujuan'],
					$stat_konsep,
					$btn_detail . $btn_update . $btn_delete
				);
			}
		}

		$records["draw"] = $sEcho;
		$records["recordsTotal"] = $iTotalRecords;
		$records["recordsFiltered"] = $iTotalRecords;

		echo json_encode($records);
	}

	function datatables_referensi_surat($suratid_enc = NULL)
	{
		// restrict($this->module . '/Konsep/datatables_data');
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

		$columns = array(
			0 => 'srtId',
			1 => 'srtTglDraft',
			2 => 'srtNomorSurat',
			3 => 'srtPerihal',
		);

		$object = array();
		//$object['UserName'] = '!='.get_user_name();
		$search = $this->input->post('search');
		if ($search['value'] != '') {
			$object['srtJenisSuratId'] = $search['value'];
			$object['srtNomorSurat'] = $search['value'];
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

		if ($suratid_enc != '' or !is_null($suratid_enc)) {
			$surat_id = decode($suratid_enc);
			$rs_surat_ref = $this->M_konsep->get_data_referensi_surat($surat_id);
		}

		$length = ($this->input->post('length') == -1) ? NULL : $this->input->post('length');
		$qry = $this->M_konsep->get_referensi_surat($unit_id, $object, $length, $this->input->post('start'), $order);
		$iTotalRecords = (!is_null($qry)) ? intval($this->M_konsep->get_referensi_surat($unit_id, $object, NULL, NULL, NULL, 'counter')) : 0;
		$iDisplayStart = intval($this->input->post('start'));
		$sEcho = intval($this->input->post('draw'));

		$records = array();
		$records["data"] = array();
		if (!is_null($qry)) {
			$no = $iDisplayStart + 1;
			foreach ($qry->result_array() as $row) {
				$srt_checked = '';
				if (!empty($rs_surat_ref)) {
					for ($i = 0; $i < count($rs_surat_ref); $i++) {
						if ($rs_surat_ref[$i]['srtId'] == $row['srtId']) {
							$srt_checked = 'checked="checked"';
						}
					}
				}
				$records["data"][] = array(
					$no++,
					'<input type="checkbox" class="surat-id" name="surat_id[]" value="' . encode($row['srtId']) . '~' . encode($row['arsId']) . '" ' . $srt_checked . '>',
					IndonesianDate($row['srtTglDraft']),
					$row['srtNomorSurat'],
					$row['srtPerihal'],
				);
			}
		}

		$records["draw"] = $sEcho;
		$records["recordsTotal"] = $iTotalRecords;
		$records["recordsFiltered"] = $iTotalRecords;

		echo json_encode($records);
	}

	function add($id_enc = NULL)
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		// print_r($rs_data);
		$username = $this->session->userdata('user_name');
		$user_id = $this->session->userdata('user_id');
		$unit_id = $this->session->userdata('user_unit_id');

		$tpl['module'] = $this->module . '/Konsep/';

		if ($this->input->post('action') == 'submit') {
			$this->form_validation->set_rules('jenis', 'Jenis Surat', 'required');
			$this->form_validation->set_rules('perihal', 'Perihal', 'required');
			$this->form_validation->set_rules('ringkasan', 'Isi/Ringkasan', 'required');
			$this->form_validation->set_rules('sifat', 'Sifat', 'required');
			$this->form_validation->set_rules('klasifikasi', 'Klasifikasi', 'required');
			$this->form_validation->set_rules('kategori', 'Kategori', 'required');

			$checked_template = ($this->input->post('checkbox-template') == '1') ? 1 : 0;
			if ($checked_template == '0') {
				// $this->form_vaslidation->set_rules('nomor', 'Nomor Surat', 'required');
				$this->form_validation->set_rules('field_file_surat', 'File Surat', 'required');
			}

			if ($this->input->post('kategori') == 'internal') {
				$this->form_validation->set_rules('tujuan_internal', 'Unit/Biro/Lembaga', 'required');
			} elseif ($this->input->post('kategori') == 'eksternal') {
				$this->form_validation->set_rules('tujuan_eksternal', 'Tujuan Surat (Luar Universitas)', 'required');
			}

			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run() == true) {

				$dokumen = '';
				if ($_FILES['file_surat']['name'] != '') {
					$upload_file = $this->DoUploadDokumen('file_surat', rand(0, 10000) . date('YmdHis') . '-' . $this->input->post('jenis') . '-' . preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['file_surat']['name']));
					if ($upload_file != false) {
						$dokumen = $upload_file;
					} else {
						$this->session->set_flashdata('message_file', array('status' => false, 'type' => 'danger', 'text' => 'Upload dokumen surat gagal.'));
					}
				}

				// file lampiran
				$files_lampiran = $_FILES;
				$jumlahFile = count($files_lampiran['file_lampiran']['name']);

				$upload_lampiran = NULL;
				if ($jumlahFile > 0) {
					$upload_lampiran = $this->upload_files($files_lampiran['file_lampiran']);
					if ($upload_lampiran == false) {
						$this->session->set_flashdata('message_file', array('status' => false, 'type' => 'danger', 'text' => 'Upload dokumen lampiran gagal.'));
					}
				}

				$params = array(
					'jenis' => $this->input->post('jenis'),
					'sifat' => $this->input->post('sifat'),
					'klasifikasi' => $this->input->post('klasifikasi'),
					// 'no_surat' => ($checked_template == '1') ? NULL : $this->input->post('nomor'),
					'tanggal' => date('Y-m-d', strtotime($this->input->post('tanggal'))),
					'perihal' => $this->input->post('perihal'),
					'ringkasan' => $this->input->post('ringkasan'),
					'kategori' => $this->input->post('kategori'),
					'dokumen' => ($checked_template == '1') ? NULL : $dokumen,
					'asal' => $unit_id,
					'tujuan' => ($this->input->post('kategori') == 'internal') ? $this->input->post('tujuan_internal') : $this->input->post('tujuan_eksternal'),
					'user' => $user_id,
					'username' => $username,
					'datetime' => date("Y-m-d H:i:s"),
					'ref_surat_id' => $this->input->post('surat_id'),
					'tembusan_internal' => $this->input->post('tembusan_internal'),
					'tembusan_eksternal' => $this->input->post('tembusan_eksternal'),
					'lampiran' => $upload_lampiran,
					'use_template' => $checked_template,
					// 'draft' => $file_draft,
				);

				$data_kolom = $this->M_konsep->get_kolom_surat($this->input->post('jenis'));
				if (!is_null($data_kolom)) {
					foreach ($data_kolom as $dt_kolom) {
						if ($dt_kolom['kolTipe'] == 'date') {
							// $tgl_surat = explode("-", $this->input->post('kolom_' . $dt_kolom['kolId']));
							$data_kolom_input_post = ($this->input->post('kolom_' . $dt_kolom['kolId']) != '') ? date('Y-m-d', strtotime($this->input->post('kolom_' . $dt_kolom['kolId']))) : NULL;
						} else {
							$data_kolom_input_post = $this->input->post('kolom_' . $dt_kolom['kolId']);
						}
						$params['jenis_kolom_id'][$dt_kolom['kolId']] = $this->input->post('jnskol_' . $dt_kolom['kolId']);
						$params['surat_kolom_id'][] = $dt_kolom['kolId'];
						$params['surat_kolom'][$dt_kolom['kolId']] = $data_kolom_input_post;
					}
				}
				$proses = $this->M_konsep->input_surat_keluar($params);
				if ($proses['status']) {
					# generate file jika menggunakan template
					$msg_generated = '';
					if ($checked_template == '1') {
						$detail_surat = $this->M_konsep->get_detail_surat($proses['id']);
						$filename_create = rand(0, 10000) . date('YmdHis') . '-' . str_replace(" ", "_", $detail_surat['jnsrtNama']) . '.doc';
						$generate_file = $this->generate_surat(encode($proses['id']), $filename_create);
						if ($generate_file['status']) {
							$this->M_konsep->update_filename_surat($proses['id'], $generate_file['filename']);
							$msg_generated = 'File Surat berhasil digenerate.';
						} else {
							$msg_generated = 'File Surat gagal digenerate.';
						}
					}
					$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Konsep Surat Keluar berhasil disimpan.' . $msg_generated);
				} else {
					$result = array('error' => 'null', 'status' => false, 'type' => 'error', 'text' => 'Konsep Surat Keluar gagal disimpan.');
				}
				// $this->output->set_content_type('application/json')->set_output(json_encode($result));
				echo json_encode($result);
			} else {
				$error = array(
					'nomor' => form_error('nomor'),
					'field_file_surat' => form_error('field_file_surat'),
					'jenis' => form_error('jenis'),
					'perihal' => form_error('perihal'),
					'ringkasan' => form_error('ringkasan'),
					'klasifikasi' => form_error('klasifikasi'),
					'sifat' => form_error('sifat'),
					'kategori' => form_error('kategori'),
				);

				if ($this->input->post('kategori') == 'internal') {
					$error2 = array('tujuan_internal' => form_error('tujuan_internal'));
				} elseif ($this->input->post('kategori') == 'eksternal') {
					$error2 = array('tujuan_eksternal' => form_error('tujuan_eksternal'));
				}

				$all_error = array_merge($error, $error2);
				$result = array('error' => $all_error);
				echo json_encode($result);
			}
		} else {
			$tpl['ref_jenis_surat'] = $this->M_konsep->get_ref_jenis_surat();
			$tpl['ref_klasifikasi'] = $this->M_konsep->get_ref_klasifikasi();
			$tpl['ref_unit'] = $this->M_konsep->get_ref_unit();
			$tpl['ref_sifat'] = $this->M_konsep->get_ref_sifat();
			$tpl['id_enc'] = $id_enc;

			$this->template->inject_partial('modules_css', multi_asset(array(
				'vendor/datatables/css/dataTables.bootstrap4.min.css' => '_theme_',
			), 'css'));

			$this->template->inject_partial('modules_js', multi_asset(array(
				'vendor/datatables/js/jquery.dataTables.min.js' => '_theme_',
				'vendor/datatables/js/dataTables.bootstrap4.min.js' => '_theme_',
			), 'js'));

			$this->load->view($this->module . '/v_konsep_add', $tpl);
		}
	}

	function update($id_enc)
	{
		// $this->load->helper('group/group');
		$id = decode($id_enc);
		$username = $this->session->userdata('user_name');
		$user_id = $this->session->userdata('user_id');
		$unit_id = $this->session->userdata('user_unit_id');
		$tpl['module'] = $this->module . '/Konsep';

		if ($this->input->post('action') == 'submit') {
			$this->form_validation->set_rules('jenis', 'Jenis Surat', 'required');
			$this->form_validation->set_rules('perihal', 'Perihal', 'required');
			$this->form_validation->set_rules('ringkasan', 'Isi/Ringkasan', 'required');
			$this->form_validation->set_rules('sifat', 'Sifat', 'required');
			$this->form_validation->set_rules('klasifikasi', 'Klasifikasi', 'required');
			$this->form_validation->set_rules('kategori', 'Kategori', 'required');

			if ($this->input->post('kategori') == 'internal') {
				$this->form_validation->set_rules('tujuan_internal', 'Unit/Biro/Lembaga', 'required');
			} elseif ($this->input->post('kategori') == 'eksternal') {
				$this->form_validation->set_rules('tujuan_eksternal', 'Tujuan Surat (Luar Universitas)', 'required');
			}

			$checked_template = ($this->input->post('checkbox-template') == '1') ? '1' : '0';
			// if ($checked_template == '0') {
			// 	// $this->form_vaslidation->set_rules('nomor', 'Nomor Surat', 'required');
			// 	$this->form_validation->set_rules('field_file_surat', 'File Surat', 'required');
			// }

			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run() == true) {

				$dokumen = $this->input->post('file_uploaded');
				if ($checked_template == '0') {
					if ($_FILES['file_surat']['name'] != '') {
						$upload_file = $this->DoUploadDokumen('file_surat', rand(0, 10000) . date('YmdHis') . '-' . decode($this->input->post('jenis')) . '-' . preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['file_surat']['name']));
						if ($upload_file != false) {
							$dokumen = $upload_file;
							if (is_file($this->config->item('upload_path') . $this->input->post('file_uploaded'))) {
								unlink($this->config->item('upload_path') . $this->input->post('file_uploaded'));
							}
						} else {
							$this->session->set_flashdata('msg_form', array('status' => false, 'type' => 'danger', 'text' => 'Upload file gagal.'));
						}
					} else {
						$dokumen = $this->input->post('file_uploaded');
					}
				}

				// file lampiran
				$files_lampiran = $_FILES;
				$jumlahFile = count($files_lampiran['file_lampiran']['name']);

				$upload_lampiran = NULL;

				if ($jumlahFile > 0 && $files_lampiran['file_lampiran']['name'][0] != '') {
					$upload_lampiran = $this->upload_files($files_lampiran['file_lampiran']);
					if ($upload_lampiran == false) {
						$this->session->set_flashdata('message_file', array('status' => false, 'type' => 'danger', 'title' => 'Peringatan', 'text' => 'Upload dokumen lampiran gagal.'));
					}
				}

				$params = array(
					'id' => $id,
					'jenis' => decode($this->input->post('jenis')),
					'sifat' => $this->input->post('sifat'),
					'klasifikasi' => $this->input->post('klasifikasi'),
					// 'no_surat' => NULL,
					'tanggal' => date('Y-m-d', strtotime($this->input->post('tanggal'))),
					'perihal' => $this->input->post('perihal'),
					'ringkasan' => $this->input->post('ringkasan'),
					'kategori' => $this->input->post('kategori'),
					'dokumen' => ($checked_template == '1') ? $this->input->post('file_generated') : $dokumen,
					'asal' => $unit_id,
					'tujuan' => ($this->input->post('kategori') == 'internal') ? $this->input->post('tujuan_internal') : $this->input->post('tujuan_eksternal'),
					'user' => $user_id,
					'username' => $username,
					'datetime' => date("Y-m-d H:i:s"),
					'ref_surat_id' => $this->input->post('surat_id'),
					'tembusan_internal' => $this->input->post('tembusan_internal'),
					'tembusan_eksternal' => $this->input->post('tembusan_eksternal'),
					'lampiran' => $upload_lampiran,
					'status' => decode($this->input->post('status')),
					'catatan' => $this->input->post('catatan'),
					'use_template' => $checked_template,
				);

				$data_kolom = $this->M_konsep->get_kolom_surat_bysurat_jenis($id, decode($this->input->post('jenis')));
				if (!is_null($data_kolom)) {
					foreach ($data_kolom as $dt_kolom) {
						if ($dt_kolom['kolTipe'] == 'date') {
							// $tgl_surat = explode("-", $this->input->post('kolom_' . $dt_kolom['kolId']));
							$data_kolom_input_post = ($this->input->post('kolom_' . $dt_kolom['kolId']) != '') ? date('Y-m-d', strtotime($this->input->post('kolom_' . $dt_kolom['kolId']))) : NULL;
						} else {
							$data_kolom_input_post = $this->input->post('kolom_' . $dt_kolom['kolId']);
						}
						$params['jenis_kolom_id'][$dt_kolom['kolId']] = $this->input->post('jnskol_' . $dt_kolom['kolId']);
						$params['surat_kolom_id'][] = $dt_kolom['kolId'];
						$params['surat_kolom'][$dt_kolom['kolId']] = $data_kolom_input_post;
					}
				}
				// print_r($params);
				// die;
				$proses = $this->M_konsep->update_surat_keluar($params);

				if ($proses) {
					$detail_surat = $this->M_konsep->get_detail_surat($id);
					# generate file jika menggunakan template
					$msg_generated = '';
					if ($checked_template == '1') {
						$detail_surat = $this->M_konsep->get_detail_surat($id);
						$filename_create = rand(0, 10000) . date('YmdHis') . '-' . $detail_surat['jnsrtNama'] . '.doc';
						$generate_file = $this->generate_surat(encode($id), $filename_create);
						if ($generate_file['status']) {
							$update_filename_surat = $this->M_konsep->update_filename_surat($id, $generate_file['filename']);
							if ($update_filename_surat) {
								unlink($this->config->item('upload_path') . $this->input->post('file_generated'));
							}
							$msg_generated = 'File Surat berhasil digenerate.';
						} else {
							$msg_generated = 'File Surat gagal digenerate.';
						}
					}
					$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Konsep Surat Keluar berhasil disimpan. ' . $msg_generated);
				} else {
					$result = array('error' => 'null', 'status' => false, 'type' => 'error', 'text' => 'Konsep Surat Keluar gagal disimpan.');
				}
				echo json_encode($result);
			} else {
				$error = array(
					'jenis' => form_error('jenis'),
					'perihal' => form_error('perihal'),
					'ringkasan' => form_error('ringkasan'),
					'klasifikasi' => form_error('klasifikasi'),
					'sifat' => form_error('sifat'),
					'kategori' => form_error('kategori'),
				);

				if ($this->input->post('kategori') == 'internal') {
					$error2 = array('tujuan_internal' => form_error('tujuan_internal'));
				} elseif ($this->input->post('kategori') == 'eksternal') {
					$error2 = array('tujuan_eksternal' => form_error('tujuan_eksternal'));
				}

				$all_error = array_merge($error, $error2);
				$result = array('error' => $all_error);
				echo json_encode($result);
			}
		} else {
			if (is_null($rs_data = $this->M_verifikasi_konsep->get_detail_surat($id))) show_404();
			// print_r($rs_data);
			$detail_surat = $this->M_konsep->get_detail_surat($id);
			$tpl['data'] = $detail_surat;
			$tpl['data_kolom'] = $this->M_konsep->get_kolom_surat_bysurat_jenis($id, $detail_surat['srtJenisSuratId']);
			$tpl['ref_jenis_surat'] = $this->M_konsep->get_ref_jenis_surat();
			$tpl['ref_klasifikasi'] = $this->M_konsep->get_ref_klasifikasi();
			$tpl['ref_unit'] = $this->M_konsep->get_ref_unit();
			$tpl['ref_sifat'] = $this->M_konsep->get_ref_sifat();
			$tpl['surat_id'] = NULL;
			$tpl['ref_kategori'] = array(0 => array('id' => 'internal', 'name' => 'Internal'), 1 => array('id' => 'eksternal', 'name' => 'Eksternal'));
			$tpl['ref_status'] = $this->M_konsep->get_ref_status();
			$tpl['surat_id'] = encode($id);
			$tpl['path_file'] = $this->config->item('upload_path');

			$this->template->inject_partial('modules_css', multi_asset(array(
				'vendor/datatables/css/dataTables.bootstrap4.min.css' => '_theme_',
			), 'css'));

			$this->template->inject_partial('modules_js', multi_asset(array(
				'vendor/datatables/js/jquery.dataTables.min.js' => '_theme_',
				'vendor/datatables/js/dataTables.bootstrap4.min.js' => '_theme_',
			), 'js'));

			$this->load->view($this->module . '/v_konsep_update', $tpl);
		}
	}

	function detail($encId = NULL)
	{
		if (is_null($encId)) redirect($this->module);
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$id = decode($encId);
		$detail = $this->M_konsep->get_detail_surat($id);
		$dt_referensi_surat = $this->M_konsep->get_data_referensi_surat($id);
		$tpl['module'] = $this->module . '/Konsep/';
		$tpl['data'] = $detail;
		$tpl['dt_referensi_surat'] = $dt_referensi_surat;
		$tpl['path'] = $this->config->item('upload_path');
		$tpl['dt_kolom_surat'] = $this->M_konsep->get_detail_kolom_surat($id);;

		$this->load->view($this->module . '/v_konsep_detail', $tpl);
	}

	function delete($id)
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$qry = $this->M_konsep->delete_user(decode($id));
		if ($qry == true) {
			echo json_encode(array("status" => TRUE, 'msg' => 'Data berhasil dihapus.'));
		} else {
			echo json_encode(array("status" => FALSE, 'msg' => 'Gagal memproses! Silahkan hubungi administrator'));
		}
	}

	function surat_kolom($jenis)
	{
		$rs_kolom = $this->M_konsep->get_kolom_surat($jenis);
		echo json_encode($rs_kolom);
	}

	function check_template($jenis)
	{
		$rs = $this->M_konsep->get_template_surat($jenis);
		$rs['is_template'] = false;
		if ($rs['jnsrtTemplate'] != '' && file_exists($this->config->item('upload_path') . 'template_surat/' . $rs['jnsrtTemplate'])) {
			$rs['is_template'] = true;
		}
		echo json_encode($rs);
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

	private function upload_files($files)
	{
		$config = array(
			'upload_path'   => $this->config->item('upload_path'),
			'allowed_types' => $this->config->item('file_allowed_types'),
			'overwrite'     => TRUE,
		);

		$this->load->library('upload', $config);

		$images = array();

		foreach ($files['name'] as $key => $image) {
			$_FILES['images[]']['name'] = $files['name'][$key];
			$_FILES['images[]']['type'] = $files['type'][$key];
			$_FILES['images[]']['tmp_name'] = $files['tmp_name'][$key];
			$_FILES['images[]']['error'] = $files['error'][$key];
			$_FILES['images[]']['size'] = $files['size'][$key];

			$title = rand(0, 10000) . date('YmdHis');
			$names = $title . '-lampiran-' . preg_replace("/[^a-zA-Z0-9.]/", "_", $files['name'][$key]);
			$fileName = $names;

			$images[] = $fileName;

			$config['file_name'] = $fileName;

			$this->upload->initialize($config);

			if ($this->upload->do_upload('images[]')) {
				$this->upload->data();
			} else {
				return false;
			}
		}

		return $images;
	}


	function view_by_file($file_name)
	{
		if (is_null($file_name))  show_404();

		$path = $this->config->item('upload_path');

		$data['path'] = $path;
		$data['dokumen'] = $file_name;
		$this->load->view($this->module . '/v_lihat_file', $data);
	}

	function delete_lampiran($id_enc, $srtid_enc)
	{
		$id = decode($id_enc);
		$proses = $this->M_konsep->delete_file_lampiran($id);
		if ($proses) {
			echo json_encode(array("status" => TRUE, 'title' => 'Informasi', 'type' => 'success', 'msg' => 'File Lampiran berhasil dihapus.'));
		} else {
			echo json_encode(array("status" => FALSE, 'title' => 'Peringatan', 'type' => 'error',  'msg' => 'Gagal memproses! Silahkan hubungi administrator'));
		}
	}

	function delete_tembusan_eksternal($val_enc, $srtid_enc)
	{
		$val = decode($val_enc);
		$id = decode($srtid_enc);
		$proses = $this->M_konsep->delete_tembusan_eksternal($val, $id);
		if ($proses) {
			echo json_encode(array("status" => TRUE, 'title' => 'Informasi', 'type' => 'success', 'msg' => 'Tembusan Eksternal berhasil dihapus.'));
		} else {
			echo json_encode(array("status" => FALSE, 'title' => 'Peringatan', 'type' => 'error',  'msg' => 'Gagal memproses! Silahkan hubungi administrator'));
		}
	}

	function generate_surat($id_enc, $filename) //filename with extention
	{
		$id = decode($id_enc);
		$detail = $this->M_konsep->get_detail_surat($id);
		$path_template = $this->config->item('upload_path') . 'template_surat/';
		$template = $path_template . $detail['jnsrtTemplate'];

		if (!is_file($template)) {
			$this->session->set_flashdata('msg_form', array('status' => true, 'type' => 'danger', 'title' => 'Peringatan', 'text' => 'Template Surat tidak tersedia. Mohon menghubungi Adminstrator.'));
			redirect(site_url($this->module . '/Konsep'));
		}

		$defautl_variable = array(
			'%NOMOR_SURAT%',
			'%TANGGAL_SURAT%',
			'%PERIHAL%',
			'%ISI_SURAT%',
			'%JENIS_SURAT%',
		);

		$rs_kolom_surat = $this->M_konsep->get_detail_kolom_surat($id);
		$adds_variable = array_column($rs_kolom_surat, 'kolVariable');

		$all_variable = array_merge($defautl_variable, $adds_variable);

		$content = array(
			$detail['srtNomorSurat'],
			IndonesianDate($detail['srtTglDraft']),
			$detail['srtPerihal'],
			$detail['srtIsiRingkasan'],
			$detail['jnsrtNama'],
		);

		$adds_content = array_column($rs_kolom_surat, 'surkolKonten');
		$all_content = array_merge($content, $adds_content);

		$file = file_get_contents($template);
		$document = str_replace($all_variable, $all_content, $file);
		/*jika di download*/
		// header("Content-type: application/msword");
		// header("Content-disposition: attachment; filename=" . $filename);
		// header("Content-length: " . strlen($document));
		// echo $document;

		// $fp = fopen($this->config->item('upload_path') . $filename, "wb");
		// fwrite($fp, $document);
		// fclose($fp);
		/*end*/

		file_put_contents($this->config->item('upload_path') . $filename, $document);
		if (is_file($this->config->item('upload_path') . $filename)) {
			$result = array('status' => TRUE, 'filename' => $filename);
		} else {
			$result = array('status' => FALSE, 'filename' => $filename);
		}
		return $result;
	}
}
