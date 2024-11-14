<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

defined('BASEPATH') or exit('No direct script access allowed');

class Surat extends Dashboard_Controller
{

	private $module = 'surat_masuk';

	function __construct()
	{
		parent::__construct();

		$this->load->model($this->module . '/M_surat');
		restrict();
	}

	public function index()
	{
		$tpl['module'] = $this->module . '/Surat';
		$tpl['unit'] = $this->M_surat->ref_unit(get_user_unit_id());
		$tpl['jenis'] = $this->M_surat->ref_jenis_surat();
		$tpl['klasifikasi'] = $this->M_surat->ref_jenis_klasifikasi();
		$tpl['sifat'] = $this->M_surat->ref_sifat();
		$tpl['eksemplar'] = $this->M_surat->ref_eksemplar();
		$tpl['berkas'] = $this->M_surat->ref_berkas(get_user_unit_id());
		$tpl['path'] = $this->config->item('surat_masuk_path');
		// set tanggal 2 pekan
		$tpl['tgl_awal'] = date('d-m-Y', strtotime("-7 days", strtotime(date('Y-m-d'))));
		$tpl['tgl_akhir'] = date('d-m-Y', strtotime("+7 days", strtotime(date('Y-m-d'))));

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
		$this->template->title('Surat Masuk');
		$this->template->set_breadcrumb('Dashboard', 'dashboard/Dashboard/index');
		$this->template->set_breadcrumb('Surat Masuk', '');
		$this->template->build($this->module . '/v_index', $tpl);
	}

	public function datatables()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$unitId = get_user_unit_id();
		$tipe = $this->input->get('tipe', TRUE);
		$id = urldecode(decode($this->input->get('id', TRUE)));

		#datatable disposisi
		if ($tipe == 'disposisi') {
			$columns = array(
				0 => 'dispId',
				1 => 'sifdisId',
				2 => 'dispCatatan',
				3 => '',
				4 => '',
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

			$object['dispSuratId'] = '=' . $id;

			$length = ($this->input->post('length') == -1) ? NULL : $this->input->post('length');

			$qry = $this->M_surat->get_disposisi($object, $length, $this->input->post('start'), $order, NULL);
			$iTotalRecords = (!is_null($qry)) ? intval($this->M_surat->get_disposisi($object, NULL, NULL, NULL, 'counter')) : 0;
			$iDisplayStart = intval($this->input->post('start'));
			$isEcho = intval($this->input->post('draw'));

			$records = array();
			$records["data"] = array();
			if (!is_null($qry)) {
				$no = $iDisplayStart + 1;
				foreach ($qry->result_array() as $row) {

					$aksi = '<div class="btn-group btn-group-sm">
						<button class="btn dropdown-toggle bg-warning" data-toggle="dropdown">Aksi</button>
						<div class="dropdown-menu dropdown-menu-left">

							<a class="dropdown-item" href="javascript:void()" onclick="detail_dispo(' . "'" . encode(urlencode($row['id'])) . "','" . (($row['unit'] == '') ? 'staff' : 'unit') . "'" . ')">Detail</a>

							<a class="dropdown-item" href="javascript:void()" onclick="ubah_dispo(' . "'" . encode(urlencode($row['id'])) . "','" . (($row['unit'] == '') ? 'staff' : 'unit') . "'" . ')">Ubah Disposisi</a>

							<a class="dropdown-item" href="javascript:void()" onclick="hapus_dispo(' . "'" . encode(urlencode($row['id'])) . "'" . ')">Hapus</a>
						</div>
					</div>';

					// <a class="dropdown-item" href="javascript:void()" onclick="dispo_unit(' . "'" . encode(urlencode($row['id'])) . "'" . ')">Tambah Unit Penerima</a>

					// '' . (($row['asal_surat'] == 'Eksternal') ?
					// '<a class="dropdown-item" href="javascript:void()" onclick="ubah_dispo(' . "'" . encode(urlencode($row['id'])) . "'" . ')">Ubah</a>' : '') . ''

					$catatan = strip_tags($row['catatan']);
					if (strlen($catatan) > 35) {
						$catatanCut = substr($catatan, 0, 35);
						$catatan = substr($catatanCut, 0, strrpos($catatanCut, ' ')) . " ...";
					}

					$records["data"][] = array(
						// $no++,
						$row['sifat'],
						$catatan,
						$row['unit'],
						$row['staff'],
						$aksi,

					);
				}
			}
		} else {
			$columns = array(
				0 => 'srtId',
				1 => 'srtTglDraft',
				2 => 'dispSifatDisposisiId',
				3 => 'srtNomorSurat',
				4 => 'srtAsalSurat',
				5 => 'srtPerihal',
			);

			$dfl_tanggal = date('Y-m-d', strtotime("-7 days", strtotime(date('Y-m-d'))));
			$dfl_tanggal_akhir = date('Y-m-d', strtotime("+7 days", strtotime(date('Y-m-d'))));
			$isbaca = $this->input->post('isbaca', TRUE);
			$tanggal = $this->input->post('tanggal', TRUE);
			$tanggal_akhir = $this->input->post('tanggal_akhir', TRUE);
			$kategori = $this->input->post('kategori', TRUE);

			$params = array(
				'isbaca' => $isbaca,
				'tanggal' => ($tanggal == '') ? $dfl_tanggal : date('Y-m-d', strtotime($tanggal)),
				'tanggal_akhir' => ($tanggal_akhir == '') ? $dfl_tanggal_akhir : date('Y-m-d', strtotime($tanggal_akhir)),
				'kategori' => $kategori
			);

			// print_r($params);
			// die;
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

			$qry = $this->M_surat->get_list($params, $object, $length, $this->input->post('start'), $order, NULL);
			$iTotalRecords = (!is_null($qry)) ? intval($this->M_surat->get_list($params, $object, NULL, NULL, NULL, 'counter')) : 0;
			$iDisplayStart = intval($this->input->post('start'));
			$isEcho = intval($this->input->post('draw'));

			$records = array();
			$records["data"] = array();
			if (!is_null($qry)) {
				$no = $iDisplayStart + 1;
				foreach ($qry->result_array() as $row) {
					$file = '';
					if ($row['file'] != '') {
						$file = '<i class="fa fa-file" onclick="file(' . "'" . $row['file'] . "'" . ')" style="cursor:pointer"></i>';
					}

					$detail = '<a href="' . site_url($this->module . '/Surat/detail/' . urlencode(encode($row['id']))) . '" class="text-warning">' . (($row['baca'] == '') ? '<span class="font-weight-bold">' . IndonesianDate($row['tanggal']) . '</span>' : IndonesianDate($row['tanggal'])) . '</a>';

					$aksi = '<div class="btn-group btn-group-sm">
						<button class="btn dropdown-toggle bg-warning" data-toggle="dropdown">Aksi</button>
						<div class="dropdown-menu dropdown-menu-left">
							<a class="dropdown-item ' . ((($row['tindakanId'] != NULL) || ($row['balas_draft'] != NULL)) ? 'd-none' : '') . '" href="javascript:void()" onclick="balas(' . "'" . encode(urlencode($row['id'])) . "'" . ')">Balas</a>

							' . (($row['asal_surat'] == 'Eksternal') ?
						'<a class="dropdown-item" href="javascript:void()" onclick="ubah(' . "'" . encode(urlencode($row['id'])) . "'" . ')">Ubah</a>' : '') . '

							<a class="dropdown-item" href="javascript:void()" onclick="hapus(' . "'" . encode(urlencode($row['id'])) . "'" . ')">Hapus</a>
						</div>
					</div>';

					$kategori = '<small><span class="badge badge-' . (($row['asal_surat'] == 'Internal') ? 'success' : 'info') . '">' . $row['asal_surat'] . '</span></small><br>';

					$records["data"][] = array(
						// $no++,
						$detail,
						($row['baca'] == '') ? '<span class="font-weight-bold">' . $row['sifat'] . '</span>' : $row['sifat'],
						($row['baca'] == '') ? '<span class="font-weight-bold">' . $row['nomor'] . '</span>' : $row['nomor'],
						($row['baca'] == '') ? '<span class="font-weight-bold">' . $kategori . ' ' . $row['asal'] . '</span>' : $kategori . ' ' . $row['asal'],
						($row['baca'] == '') ? '<span class="font-weight-bold">' . $row['perihal'] . '</span>' : $row['perihal'],
						$file,
						$aksi,
					);
				}
			}
		}
		$records["draw"] = $isEcho;
		$records["recordsTotal"] = $iTotalRecords;
		$records["recordsFiltered"] = $iTotalRecords;

		echo json_encode($records);
	}

	public function detail($id_enc = '')
	{
		$tipe = $this->input->get('tipe', TRUE);
		if ($tipe == 'detail_dispo') {
			if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
			$for = $this->input->get('for', TRUE);
			$id = urldecode(decode($this->input->get('id', TRUE)));
			$tpl['detail'] = $this->M_surat->detail_disposisi($id);
			$tpl['detail_dispo'] =  $this->M_surat->detail_disposisi_units($id);
			$tpl['for'] = $for;
			$this->load->view($this->module . '/v_detail_disposisi', $tpl);
		} elseif ($tipe == 'ajax_status') {
			if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
			$id = urldecode(decode($this->input->get('id', TRUE))); #suratId
			$data = $this->M_surat->status_tindakan($id);
			echo json_encode($data);
		} else {
			$id = decode(urldecode($id_enc));

			#background proses arsip
			background_arsip($id_enc);

			$detail = $this->M_surat->detail($id);
			$tpl['module'] = $this->module . '/Surat';
			$tpl['detail'] = $detail;
			$tpl['status'] = $this->M_surat->status_tindakan($id);
			$tpl['arahan'] = $this->M_surat->arahan_surat($id);
			$tpl['tanggapan'] = $this->M_surat->tanggapan_surat($id);
			$tpl['eksemplar'] = $this->M_surat->ref_eksemplar();
			$tpl['berkas'] = $this->M_surat->ref_berkas(get_user_unit_id());
			$tpl['path'] = $this->config->item('surat_masuk_path');

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

			$this->template->title('Detail Surat Masuk');
			$this->template->set_breadcrumb('Dashboard', 'dashboard/Dashboard/index');
			$this->template->set_breadcrumb('Detail', '');
			$this->template->build($this->module . '/v_detail', $tpl);
		}
	}

	public function add()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$tipe = $this->input->get('tipe', TRUE);
		$tpl['module'] = $this->module . '/Surat';

		if ($tipe == 'view') {
			$tpl['jenis'] = $this->M_surat->ref_jenis_surat();
			$tpl['klasifikasi'] = $this->M_surat->ref_jenis_klasifikasi();
			$tpl['sifat'] = $this->M_surat->ref_sifat();
			$tpl['eksemplar'] = $this->M_surat->ref_eksemplar();
			$tpl['berkas'] = $this->M_surat->ref_berkas(get_user_unit_id());
			$this->load->view($this->module . '/v_add', $tpl);
		} elseif ($tipe == 'proses') {
			$arsip_check = $this->input->post('check_arsip', TRUE);

			$this->form_validation->Set_rules('jenis', 'jenis surat', 'required');
			$this->form_validation->Set_rules('tanggal', 'tanggal surat', 'required');
			$this->form_validation->Set_rules('klasifikasi', 'klasifikasi surat', 'required');
			$this->form_validation->Set_rules('nomor', 'nomor surat', 'required');
			$this->form_validation->Set_rules('hal', 'perihal surat', 'required');
			$this->form_validation->Set_rules('isi', 'ringkasan surat', 'required');
			$this->form_validation->Set_rules('asal', 'asal surat', 'required');
			$this->form_validation->Set_rules('sifat', 'sifat urgensi surat', 'required');

			if ($arsip_check == 'on') {
				$this->form_validation->Set_rules('berkas', 'berkas arsip', 'required');
				$this->form_validation->Set_rules('eksemplar', 'jenis eksemplar', 'required');
			}
			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run()) {
				$tanggal = date('Y-m-d H:i:s', strtotime($this->input->post('tanggal', TRUE)));
				$unitId = get_user_unit_id();
				$unit = $this->M_surat->detail_unit($unitId);
				$file_surat = $_FILES['file'];
				$ext_surat = '.' . pathinfo($file_surat['name'], PATHINFO_EXTENSION);

				$params = array(
					'srtUnitTujuanUtama' => $unitId,
					'srtStatusId' => 3, #status surat 'fix'
					'srtJenisSuratId' => $this->input->post('jenis', TRUE),
					'srtPerihal' => $this->input->post('hal', TRUE),
					'srtNomorSurat' => $this->input->post('nomor', TRUE),
					'srtIsiRingkasan' => $this->input->post('isi', TRUE),
					'srtKlasifikasiId' => $this->input->post('klasifikasi', TRUE),
					'srtSifatSurat' => $this->input->post('sifat', TRUE),
					'srtAsalSurat' => $this->input->post('asal', TRUE),
					'srtTglDraft' => $tanggal, #not clear, tanyakan lagi!
					#'srtTglDraft' => date('Y-m-d H:i:s'), #not clear, tanyakan lagi!
					'srtUserDrafter' => get_user_id(), #not clear, tanyakan lagi!
					'srtIsSigned' => '1'
				);

				$arsip = '';
				if ($arsip_check == 'on') {
					$arsip = array(
						'arsPerihal' => $this->input->post('hal', TRUE),
						'arsBerkasId' => $this->input->post('berkas', TRUE),
						'arsJenisEksemplarId' => $this->input->post('eksemplar', TRUE),
						'arsJenis' => $this->input->post('jenis', TRUE), #not clear, apakah jenis surat?
						'arsAsal' => $this->input->post('asal', TRUE), #not clear, blm ada input asal
						'arsTujuanUnitId' => $unitId,
						'arsUserCreate' => get_user_name(),
						'arsTglCreate' => date('Y-m-d H:i:s'),
					);
				}

				$log = array(
					'logUserId' => get_user_id(),
					'logTanggal' => date('Y-m-d H:i:s'),
					'logUserCreate' => get_user_name(),
					'logTglCreate' => date('Y-m-d H:i:s'),
				);

				$jenis = $this->input->post('jenis', TRUE);
				$ref_kolom = $this->M_surat->ref_kolom($jenis);

				$surat_kolom = '';
				if ($ref_kolom != NULL) {
					foreach ($ref_kolom as $val) {
						$surat_kolom[] = array(
							'surkolJnsSuratKolomId' => $val['jenis_kol_id'],
							'surkolKonten' => $this->input->post($val['id'], TRUE)
						);
					}
				}

				#upload file surat
				$nama_unit = str_replace(" ", '_', $unit['nama']);
				if ($file_surat['name'] != '') {
					$nama_file =  'Surat_masuk_' . $nama_unit . '_' . time() . $ext_surat;
					$data = array(
						'tipe' => 'add',
						'file' => 'file',
						'nama_file' => $nama_file
					);
					$upload = $this->upload($data);

					if ($upload['status'] == TRUE) {
						$params['srtFile'] = $nama_file;

						if ($arsip_check == 'on') {
							$arsip['arsFileArsip'] = $nama_file;
						}
					} else {
						$result = array('error' => array(
							'file' => $upload['error']
						));
						echo json_encode($result);
						die;
					}
				}

				// print_r($params);
				// die;
				$proses = $this->M_surat->add($params, $arsip, $log, $surat_kolom);
				if ($proses['status'] == TRUE) {
					$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Surat masuk berhasil ditambahkan.');
				} else {
					unlink(FCPATH . 'uploads/' . $nama_file);
					$result = array('error' => 'null', 'status' => false, 'type' => 'danger', 'text' => 'Surat masuk gagal ditambahkan.');
				}
			} else {
				$result = array('error' => array(
					form_error('jenis') ? "jenis" : "kosong" => form_error('jenis'),
					form_error('tanggal') ? "tanggal" : "kosong" => form_error('tanggal'),
					form_error('klasifikasi') ? "klasifikasi" : "kosong" => form_error('klasifikasi'),
					form_error('nomor') ? "nomor" : "kosong" => form_error('nomor'),
					form_error('hal') ? "hal" : "kosong" => form_error('hal'),
					form_error('isi') ? "isi" : "kosong" => form_error('isi'),
					form_error('sifat') ? "sifat" : "kosong" => form_error('sifat'),
					form_error('asal') ? "asal" : "kosong" => form_error('asal'),
					form_error('berkas') ? "berkas" : "kosong" => form_error('berkas'),
					form_error('eksemplar') ? "eksemplar" : "kosong" => form_error('eksemplar'),
				));
			}
			echo json_encode($result);
		}
	}

	public function update($id_enc = '')
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$tipe = $this->input->get('tipe', TRUE);
		$id = urldecode(decode($this->input->get('id', TRUE)));
		$detail = $this->M_surat->detail($id);
		$tpl['module'] = $this->module . '/Surat';

		if ($tipe == 'view') {
			$tpl['jenis'] = $this->M_surat->ref_jenis_surat();
			$tpl['klasifikasi'] = $this->M_surat->ref_jenis_klasifikasi();
			$tpl['sifat'] = $this->M_surat->ref_sifat();
			$tpl['eksemplar'] = $this->M_surat->ref_eksemplar();
			$tpl['berkas'] = $this->M_surat->ref_berkas(get_user_unit_id());
			$tpl['detail'] = $detail;

			if ($detail['asal_surat'] == 'Internal') {
				echo '<div class="alert alert-danger m-auto text-center">Surat masuk dari internal tidak bisa diubah!</div>';
				die;
			}
			$this->load->view($this->module . '/v_update', $tpl);
		} elseif ($tipe == 'proses') {

			if ($detail['asal_surat'] == 'Internal') {
				$data = array('error' => 'null', 'text' => 'Surat masuk dari internal tidak bisa diubah!');
				echo json_encode($data);
			}

			$arsip_check = $this->input->post('check_arsip', TRUE);

			$this->form_validation->Set_rules('jenis', 'jenis surat', 'required');
			$this->form_validation->Set_rules('tanggal', 'tanggal surat', 'required');
			$this->form_validation->Set_rules('klasifikasi', 'klasifikasi surat', 'required');
			$this->form_validation->Set_rules('nomor', 'nomor surat', 'required');
			$this->form_validation->Set_rules('hal', 'perihal surat', 'required');
			$this->form_validation->Set_rules('isi', 'ringkasan surat', 'required');
			$this->form_validation->Set_rules('asal', 'asal surat', 'required');
			$this->form_validation->Set_rules('sifat', 'sifat urgensi surat', 'required');

			if ($arsip_check == 'on') {
				$this->form_validation->Set_rules('berkas', 'berkas arsip', 'required');
				$this->form_validation->Set_rules('eksemplar', 'jenis eksemplar', 'required');
			}
			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run()) {
				$tanggal = date('Y-m-d H:i:s', strtotime($this->input->post('tanggal', TRUE)));
				$unitId = get_user_unit_id();
				$unit = $this->M_surat->detail_unit($unitId);
				$file_surat = $_FILES['file'];
				$ext_surat = '.' . pathinfo($file_surat['name'], PATHINFO_EXTENSION);

				$params = array(
					'srtUnitTujuanUtama' => $unitId,
					'srtJenisSuratId' => $this->input->post('jenis', TRUE),
					'srtPerihal' => $this->input->post('hal', TRUE),
					'srtNomorSurat' => $this->input->post('nomor', TRUE),
					'srtIsiRingkasan' => $this->input->post('isi', TRUE),
					'srtKlasifikasiId' => $this->input->post('klasifikasi', TRUE),
					'srtSifatSurat' => $this->input->post('sifat', TRUE),
					'srtAsalSurat' => $this->input->post('asal', TRUE),
					'srtTglDraft' => $tanggal, #not clear, tanyakan lagi!
				);

				$arsip = '';
				if ($arsip_check == 'on') {
					$arsip = array(
						'arsPerihal' => $this->input->post('hal', TRUE),
						'arsBerkasId' => $this->input->post('berkas', TRUE),
						'arsJenisEksemplarId' => $this->input->post('eksemplar', TRUE),
						'arsJenis' => $this->input->post('jenis', TRUE), #not clear, apakah jenis surat?
						'arsAsal' => $this->input->post('asal', TRUE), #not clear, blm ada input asal
						'arsTujuanUnitId' => $unitId,
					);
				}

				if ($detail['is_arsip'] != '') {
					$arsip['arsUserUpdate'] = get_user_name();
					$arsip['arsTglUpdate'] = date('Y-m-d H:i:s');
				} else {
					$arsip['arsUserCreate'] = get_user_name();
					$arsip['arsTglCreate'] = date('Y-m-d H:i:s');
				}

				#upload file surat
				$nama_unit = str_replace(" ", '_', $unit['nama']);
				if ($file_surat['name'] != '') {
					$nama_file =  'Surat_masuk_' . $nama_unit . '_' . time() . $ext_surat;
					$data = array(
						'tipe' => 'add',
						'file' => 'file',
						'nama_file' => $nama_file
					);
					$upload = $this->upload($data);

					if ($upload['status'] == TRUE) {
						$params['srtFile'] = $nama_file;

						if ($arsip_check == 'on') {
							$arsip['arsFileArsip'] = $nama_file;
						}
					} else {
						$result = array('error' => array(
							'file' => $upload['error']
						));
						echo json_encode($result);
						die;
					}
				}

				$proses = $this->M_surat->update($id, $params, $arsip, $detail['is_arsip']);
				if ($proses['status'] == TRUE) {
					$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Surat masuk berhasil diubah.');
				} else {
					unlink(FCPATH . 'uploads/' . $nama_file);
					$result = array('error' => 'null', 'status' => false, 'type' => 'danger', 'text' => 'Surat masuk gagal diubah');
				}
			} else {
				$result = array('error' => array(
					form_error('jenis') ? "jenis" : "kosong" => form_error('jenis'),
					form_error('tanggal') ? "tanggal" : "kosong" => form_error('tanggal'),
					form_error('klasifikasi') ? "klasifikasi" : "kosong" => form_error('klasifikasi'),
					form_error('nomor') ? "nomor" : "kosong" => form_error('nomor'),
					form_error('hal') ? "hal" : "kosong" => form_error('hal'),
					form_error('isi') ? "isi" : "kosong" => form_error('isi'),
					form_error('sifat') ? "sifat" : "kosong" => form_error('sifat'),
					form_error('asal') ? "asal" : "kosong" => form_error('asal'),
					form_error('berkas') ? "berkas" : "kosong" => form_error('berkas'),
					form_error('eksemplar') ? "eksemplar" : "kosong" => form_error('eksemplar'),
				));
			}
			echo json_encode($result);
		}
	}

	public function disposisi()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$tipe = $this->input->get('tipe', TRUE);
		$mode = $this->input->get('mode', TRUE);
		$tipe_update = $this->input->get('tipe_update', TRUE);
		$tpl['module'] = $this->module . '/Surat';

		if ($tipe == 'view') {
			$id = urldecode(decode($this->input->get('id', TRUE))); #dispoId
			$unitId = urldecode(decode($this->input->get('unitId', TRUE))); #unitId
			$tpl['sifat'] = $this->M_surat->ref_sifat();
			$tpl['unit'] = $this->M_surat->ref_unit(get_user_unit_id());
			$tpl['staff'] = $this->M_surat->staff_unit(get_user_unit_id());
			$tpl['instruksi'] = $this->M_surat->ref_instruksi();
			$tpl['id'] = $id;
			$tpl['mode'] = $mode;
			if ($mode == 'update') {
				$detail = $this->M_surat->detail_disposisi($id);
				$tpl['detail'] = $detail;
				$tpl['detail_dispo'] = $this->M_surat->detail_disposisi_units($id);
				$tpl['detail_staff'] = $this->M_surat->detail_disposisi_staffs($detail['suratId']);
				$tpl['tipe_update'] = $tipe_update;
			}

			if ($mode == 'dispo_unit') {
				$tpl['detail'] = $this->M_surat->detail_disposisi_unit($id, $unitId);
				$tpl['key'] = $this->input->get('key', TRUE);
			}

			$view = ($mode == 'dispo_unit') ? '/v_dispo_unit' : '/v_disposisi';
			$this->load->view($this->module . $view, $tpl);

			#view add/update unit_disposisi
		} elseif ($tipe == 'view_unit') {
			$id = urldecode(decode($this->input->get('id', TRUE))); #dispoId
			$unitId = urldecode(decode($this->input->get('unitId', TRUE))); #unitId
			$key = $this->input->get('key', TRUE);
			if ($mode == 'update') {
				$tpl['detail'] = $this->M_surat->detail_disposisi_unit($id, $unitId);
				$tpl['key'] = $this->input->get('key', TRUE);
			}
			$tpl['mode'] = $mode;
			$tpl['id'] = $id;
			$tpl['key'] = $key;
			$tpl['unit'] = $this->M_surat->ref_unit(get_user_unit_id());
			$tpl['instruksi'] = $this->M_surat->ref_instruksi();
			$this->load->view($this->module . '/v_dispo_unit', $tpl);

			#view add/update staff_disposisi
		} elseif ($tipe == 'view_staff') {
			$id = urldecode(decode($this->input->get('id', TRUE))); #suratId
			$key = $this->input->get('key', TRUE);
			if ($mode == 'update') {
				$detail = $this->M_surat->detail_disposisi_staff($id);
				$tpl['detail'] = $detail;
				$tpl['detail_staff'] = $this->M_surat->detail_disposisi_staffs($detail['suratId']);
			}
			$tpl['mode'] = $mode;
			$tpl['id'] = $id;
			$tpl['key'] = $key;
			$tpl['sifat'] = $this->M_surat->ref_sifat();
			$tpl['staff'] = $this->M_surat->staff_unit(get_user_unit_id());
			$this->load->view($this->module . '/v_dispo_staff', $tpl);

			#process record surat_disposisi
		} elseif ($tipe == 'proses') {
			$dsp_units = count($this->input->post('key', TRUE));

			if ($mode == 'dispo_unit') {
				$this->form_validation->Set_rules('instruksi', 'instruksi disposisi', 'required');
				$this->form_validation->Set_rules('catatan_units', 'catatan disposisi', 'required');
			} elseif ($mode == 'dispo_staff') {
				$this->form_validation->Set_rules('sifat_staff', 'sifat urgensi surat', 'required');
				$this->form_validation->Set_rules('catatan_staff', 'catatan disposisi', 'required');
			} elseif ($dsp_units != 0) {
				$this->form_validation->Set_rules('catatan', 'catatan disposisi', 'required');
				$this->form_validation->Set_rules('sifat', 'sifat urgensi surat', 'required');
			}
			$this->form_validation->Set_rules('id', 'id', 'required');
			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run()) {

				$id = urldecode(decode($this->input->post('id', TRUE)));

				$data = array();
				$proses = array();
				if ($mode == 'dispo_staff') {
					$params = array(
						'dispSifatDisposisiId' => $this->input->post('sifat_staff', TRUE),
						'dispCatatan' => $this->input->post('catatan_staff', TRUE),
						'dispUserUpdate' => get_user_name(),
						'dispTglUpdate' => date('Y-m-d H:i:s')
					);

					$data = [
						'data' => $params,
						'id' => $id,
					];

					$proses = $this->M_surat->update_disposisi_staff($data);

					#update disposisi penerima unit.
				} elseif ($mode == 'dispo_unit') {
					$unitId = urldecode(decode($this->input->post('unitId', TRUE)));
					$params = array(
						'disunitInstruksiId' => $this->input->post('instruksi', TRUE),
						'disunitCatatan' => $this->input->post('catatan_units', TRUE),
					);

					$data = [
						'data' => $params,
						'id' => $id,
						'unitId' => $unitId
					];

					$proses = $this->M_surat->update_disposisi_unit($data);
				} else {
					$params = '';
					if (($this->input->post('sifat') != '') && ($this->input->post('catatan') != '')) {
						$params = array(
							'dispSifatDisposisiId' => $this->input->post('sifat'),
							'dispCatatan' => $this->input->post('catatan'),
						);

						if ($mode == 'add') {
							$params['dispSuratId'] = $id;
							$params['dispUserCreate'] = get_user_name();
							$params['dispTglCreate'] = date('Y-m-d H:i:s');
						}

						if ($mode == 'update') {
							$params['dispUserUpdate'] = get_user_name();
							$params['dispTglUpdate'] = date('Y-m-d H:i:s');
						}
					}

					$key = $this->input->post('key', TRUE);
					$dispo_unit = '';
					if (count($key) > 0) {
						foreach ($key as $val) {
							$dispo_unit[] = array(
								'disunitUnitId' => $this->input->post('dispo_unit[' . $val . ']', TRUE),
								'disunitInstruksiId' => $this->input->post('dispo_instruksi[' . $val . ']', TRUE),
								'disunitCatatan' => $this->input->post('dispo_catatan[' . $val . ']', TRUE),
							);
						}
					}

					$key_staff = $this->input->post('key_staff', TRUE);
					$dispo_staff = '';
					if (count($key_staff) > 0) {
						foreach ($key_staff as $val) {
							$dispo_staff[] = array(
								'dispSuratId' => $id,
								'dispUserId' => $this->input->post('dispo_staff[' . $val . ']', TRUE),
								'dispSifatDisposisiId' => $this->input->post('dispo_sifat_staff[' . $val . ']', TRUE),
								'dispCatatan' => $this->input->post('dispo_catatan_staff[' . $val . ']', TRUE),
								'dispUserCreate' => get_user_name(),
								'dispTglCreate' => date('Y-m-d H:i:s')
							);
						}
					}

					$data = array(
						'mode' => $mode,
						'data' => $params,
						'id' => $id,
						'dispo_unit' => $dispo_unit,
						'dispo_staff' => $dispo_staff,
					);

					$proses = $this->M_surat->proses_disposisi($data);
				}

				if ($proses['status'] == TRUE) {
					$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Disposisi surat berhasil ' . (($mode == 'add') ? 'ditambahkan.' : 'diubah.'));
				} else {
					$result = array('error' => 'null', 'status' => false, 'type' => 'danger', 'text' => 'Disposisi surat gagal ' . (($mode == 'add') ? 'ditambahkan.' : 'diubah.'));
				}
			} else {
				$result = array('error' => 'null', 'text' => 'Tidak ada data disposisi yang ditambahkan.');
				if ($mode == 'dispo_unit') {
					$result = array('error' => array(
						form_error('catatan_units') ? "catatan_units" : "kosong" => form_error('catatan_units'),
						form_error('instruksi') ? "instruksi" : "kosong" => form_error('instruksi'),
					));
				} elseif ($mode == 'dispo_staff') {
					$result = array('error' => array(
						form_error('sifat_staff') ? "sifat_staff" : "kosong" => form_error('sifat_staff'),
						form_error('catatan_staff') ? "catatan_staff" : "kosong" => form_error('catatan_staff'),
					));
				} elseif ($dsp_units != 0) {
					$result = array('error' => array(
						form_error('catatan') ? "catatan" : "kosong" => form_error('catatan'),
						form_error('sifat') ? "sifat" : "kosong" => form_error('sifat')
					));
				}
			}
			echo json_encode($result);

			#delete disposisi 
		} elseif ($tipe == 'delete') {
			$dispoId = urldecode(decode($this->input->get('dispoId', TRUE)));
			$id = urldecode(decode($this->input->get('id', TRUE)));

			if ($this->input->get('dispoId', TRUE) == '') {
				$proses = $this->M_surat->hapus_disposisi($id);
			} else {
				$proses = $this->M_surat->hapus_disposisi_unit($dispoId, $id);
			}
			if ($proses['status'] == TRUE) {
				$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Disposisi surat berhasil dihapus');
			} else {
				$result = array('error' => 'null', 'status' => false, 'type' => 'danger', 'text' => 'Disposisi surat gagal dihapus');
			}
			echo json_encode($result);
		}
	}

	public function arahan()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$tipe = $this->input->get('tipe', TRUE);
		$mode = $this->input->get('mode', TRUE);
		$tpl['module'] = $this->module . '/Surat';

		if ($tipe == 'view') {
			$id = urldecode(decode($this->input->get('id', TRUE)));
			$tpl['mode'] = $mode;
			$tpl['pejabat'] = $this->M_surat->ref_pejabat();
			if ($mode == 'update') {
				$id = $this->input->get('id', TRUE);
				$tpl['detail'] = $this->M_surat->detail_arahan($id);
			}
			$tpl['id'] = $id;

			$this->load->view($this->module . '/v_arahan', $tpl);
		} elseif ($tipe == 'ajax_arahan') {
			$id = urldecode(decode($this->input->get('id', TRUE))); #suratId
			$data = $this->M_surat->arahan_surat($id);
			echo json_encode($data);
		} elseif ($tipe == 'proses') {
			$this->form_validation->Set_rules('catatan', 'catatan', 'required');
			if ($mode == 'add') {
				$this->form_validation->Set_rules('pejabat', 'pejabat', 'required');
			}
			$this->form_validation->set_error_delimiters('', '');

			if ($this->form_validation->run()) {
				$id = urldecode(decode($this->input->post('id', TRUE)));

				$params = array(
					'logCatatan' => $this->input->post('catatan', TRUE),
				);

				if ($mode == 'add') {
					$params['logPjbId'] = $this->input->post('pejabat', TRUE);
					$params['logPjbUserArahan'] = $this->input->post('pejabat_nama', TRUE);
					$params['logSuratId'] = $id;
					$params['logUserId'] = get_user_id();
					$params['logUserCreate'] = get_user_name();
					$params['logTglCreate'] = date('Y-m-d H:i:s');
					$params['logTanggal'] = date('Y-m-d H:i:s');
				}

				if ($mode == 'update') {
					$params['logCatatan'] = $this->input->post('catatan', TRUE);
					$params['logUserUpdate'] = get_user_name();
					$params['logTglUpdate'] = date('Y-m-d H:i:s');
				}

				$data = array(
					'data' => $params,
					'id' => $id,
					'mode' => $mode
				);

				$proses = $this->M_surat->set_arahan($data);

				if ($proses['status'] == TRUE) {
					$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Arahan surat berhasil ' . (($mode == 'add') ? 'ditambahkan.' : 'diubah.'));
				} else {
					$result = array('error' => 'null', 'status' => false, 'type' => 'danger', 'text' => 'Arahan surat gagal ' . (($mode == 'add') ? 'ditambahkan.' : 'diubah.'));
				}
			} else {
				$result = array('error' => array(
					form_error('catatan') ? "catatan" : "kosong" => form_error('catatan'),
					form_error('pejabat') ? "pejabat" : "kosong" => form_error('pejabat'),
				));
			}
			echo json_encode($result);
		} elseif ($tipe == 'delete') {
			$id = $this->input->get('id', TRUE);

			$proses = $this->M_surat->delete_arahan($id);
			if ($proses['status'] == TRUE) {
				$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Arahan surat berhasil dihapus');
			} else {
				$result = array('error' => 'null', 'status' => false, 'type' => 'danger', 'text' => 'Arahan surat gagal dihapus');
			}
			echo json_encode($result);
		}
	}


	public function tanggapan()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$tipe = $this->input->get('tipe', TRUE);
		$mode = $this->input->get('mode', TRUE);
		$tpl['module'] = $this->module . '/Surat';

		if ($tipe == 'view') {
			$id = urldecode(decode($this->input->get('id', TRUE)));
			$tpl['id'] = $id;
			$tpl['mode'] = $mode;
			$tpl['user'] = $this->M_surat->user_tindakan();
			$tpl['tindakan'] = $this->M_surat->ref_tindakan();
			if ($mode == 'update') {
				$tinId = $this->input->get('id', TRUE);
				$tpl['tinId'] = $tinId;
				$tpl['detail'] = $this->M_surat->detail_tanggapan_surat($tinId);
			}

			$this->load->view($this->module . '/v_tanggapan', $tpl);
		} elseif ($tipe == 'ajax_tanggapan') {
			$id = urldecode(decode($this->input->get('id', TRUE))); #suratId
			$data = $this->M_surat->tanggapan_surat($id);
			echo json_encode($data);
		} elseif ($tipe == 'proses') {
			$this->form_validation->Set_rules('catatan', 'catatan balasan', 'required');
			// $this->form_validation->Set_rules('tindakan', 'tindakan', 'required');
			// $this->form_validation->Set_rules('user', 'pejabat/staff', 'required');
			$this->form_validation->set_error_delimiters('', '');

			if ($this->form_validation->run()) {
				$id = urldecode(decode($this->input->post('id', TRUE)));

				$params = array(
					'tindCatatan' => $this->input->post('catatan', TRUE),
					// 'tindJenisTindakanId' => $this->input->post('tindakan', TRUE),
					// 'tindUserPjbId' => $this->input->post('user', TRUE),
				);

				if ($mode == 'add') {
					$params['tindSuratId'] = $id;
					$params['tindUserCreate'] = get_user_name();
					$params['tindTglCreate'] = date('Y-m-d H:i:s');
				}

				if ($mode == 'update') {
					$params['tindUserUpdate'] = get_user_name();
					$params['tindTglUpdate'] = date('Y-m-d H:i:s');
				}

				$data = array(
					'data' => $params,
					'id' => ($mode == 'add') ? $id : $this->input->post('id', TRUE),
					'mode' => $mode
				);

				$proses = $this->M_surat->set_tanggapan($data);

				if ($proses['status'] == TRUE) {
					$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Tanggapan surat berhasil ' . (($mode == 'add') ? 'ditambahkan.' : 'diubah.'));
				} else {
					$result = array('error' => 'null', 'status' => false, 'type' => 'danger', 'text' => 'Tanggapan surat gagal ' . (($mode == 'add') ? 'ditambahkan.' : 'diubah.'));
				}
			} else {
				$result = array('error' => array(
					form_error('catatan') ? "catatan" : "kosong" => form_error('catatan'),
					// form_error('tindakan') ? "tindakan" : "kosong" => form_error('tindakan'),
					// form_error('user') ? "user" : "kosong" => form_error('user'),
				));
			}
			echo json_encode($result);
		} elseif ($tipe == 'delete') {
			$id = $this->input->get('id', TRUE);

			$proses = $this->M_surat->delete_tanggapan($id);
			if ($proses['status'] == TRUE) {
				$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Tanggapan surat berhasil dihapus');
			} else {
				$result = array('error' => 'null', 'status' => false, 'type' => 'danger', 'text' => 'Tanggapan surat gagal dihapus');
			}
			echo json_encode($result);
		}
	}

	public function arsip()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$tipe = $this->input->get('tipe', TRUE);
		$mode = $this->input->get('mode', TRUE);
		$unitId = get_user_unit_id();
		$tpl['module'] = $this->module . '/Surat';

		if ($tipe == 'view') {
			$suratId = urldecode(decode($this->input->get('suratId', TRUE)));
			$id = urldecode(decode($this->input->get('id', TRUE)));
			$tpl['eksemplar'] = $this->M_surat->ref_eksemplar();
			$tpl['berkas'] = $this->M_surat->ref_berkas(get_user_unit_id());
			$tpl['id'] = $suratId;
			$tpl['mode'] = $mode;
			$tpl['detail'] = $this->M_surat->detail($suratId);

			$this->load->view($this->module . '/v_arsip', $tpl);
		} elseif ($tipe == 'ajax_arsip') {
			$id = urldecode(decode($this->input->get('id', TRUE)));
			$detail = $this->M_surat->detail($id);
			$data[] = array(
				'suratId' => encode(urlencode($detail['id'])),
				'arsipId' => encode(urlencode($detail['is_arsip'])),
				'eksemplar_nama' => $detail['eksemplar_nama'],
				'berkas_nama' => $detail['berkas_nama'],
			);
			echo json_encode($data);
		} elseif ($tipe == 'proses') {
			$this->form_validation->Set_rules('berkas', 'berkas', 'required');
			$this->form_validation->Set_rules('eksemplar', 'jenis eksemplar', 'required');
			$this->form_validation->set_error_delimiters('', '');

			if ($this->form_validation->run()) {
				$suratId = urldecode(decode($this->input->post('suratId', TRUE)));
				$id = urldecode(decode($this->input->post('id', TRUE)));

				if ($mode == 'add') {
					$detail = $this->M_surat->detail($suratId);
					$params = array(
						'arsSuratId' => $suratId,
						'arsPerihal' => $detail['perihal'],
						'arsBerkasId' => $this->input->post('berkas', TRUE),
						'arsJenisEksemplarId' => $this->input->post('eksemplar', TRUE),
						'arsJenis' => $detail['jenis_id'],
						'arsTujuanUnitId' => $unitId,
						'arsUserCreate' => get_user_name(),
						'arsTglCreate' => date('Y-m-d H:i:s'),
					);

					if ($detail['asal_surat'] == 'Eksternal') {
						$params['arsAsal'] = $detail['id_asal_surat'];
					}
					if ($detail['asal_surat'] == 'Internal') {
						$params['arsAsalUnitId'] = $detail['id_asal_surat'];
					}
				}

				if ($mode == 'update') {
					$params = array(
						'arsBerkasId' => $this->input->post('berkas', TRUE),
						'arsJenisEksemplarId' => $this->input->post('eksemplar', TRUE),
						'arsUserUpdate' => get_user_name(),
						'arsTglUpdate' => date('Y-m-d H:i:s'),
					);
				}

				$data = array(
					'data' => $params,
					'id' => $id,
					'mode' => $mode
				);

				$proses = $this->M_surat->set_arsip($data);

				if ($proses['status'] == TRUE) {
					$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Surat berhasil ' . (($mode == 'add') ? 'diarsipkan.' : 'diarsipkan.'));
				} else {
					$result = array('error' => 'null', 'status' => false, 'type' => 'danger', 'text' => 'Surat gagal ' . (($mode == 'add') ? 'diarsipkan.' : 'diarsipkan.'));
				}
			} else {
				$result = array('error' => array(
					form_error('berkas') ? "berkas" : "kosong" => form_error('berkas'),
					form_error('eksemplar') ? "eksemplar" : "kosong" => form_error('eksemplar'),
				));
			}
			echo json_encode($result);
		}
	}

	public function delete()
	{
		$id = urldecode(decode($this->input->get('id', TRUE)));
		$proses = $this->M_surat->hapus_surat($id);

		if ($proses['status'] == TRUE) {
			$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Surat masuk berhasil dihapus');
		} else {
			$result = array('error' => 'null', 'status' => false, 'type' => 'danger', 'text' => 'Surat masuk gagal dihapus');
		}
		echo json_encode($result);
	}

	public function draft()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$tipe = $this->input->get('tipe', TRUE);
		$tpl['module'] = $this->module . '/Surat';

		if ($tipe == 'view') {
			$id = urldecode(decode($this->input->get('id', TRUE)));
			$tpl['jenis'] = $this->M_surat->ref_jenis_surat();
			$tpl['klasifikasi'] = $this->M_surat->ref_jenis_klasifikasi();
			$tpl['sifat'] = $this->M_surat->ref_sifat();
			$tpl['unit'] = $this->M_surat->ref_unit(get_user_unit_id());
			$tpl['detail'] = $this->M_surat->detail($id);
			$this->load->view($this->module . '/v_draft', $tpl);
		} elseif ($tipe == 'ajax_kolom') {
			$id = $this->input->get('id', TRUE);
			$kolom = $this->M_surat->ref_kolom($id);
			echo json_encode($kolom);
		} elseif ($tipe == 'proses') {
			$jenis = $this->input->post('jenis', TRUE);
			$ref_kolom = $this->M_surat->ref_kolom($jenis);
			$kategori = $this->input->post('kategori', TRUE);

			$this->form_validation->Set_rules('jenis', 'jenis surat', 'required');
			$this->form_validation->Set_rules('sifat', 'sifat urgensi surat', 'required');
			$this->form_validation->Set_rules('klasifikasi', 'klasifikasi surat', 'required');
			$this->form_validation->Set_rules('kategori', 'kategori surat', 'required');
			$this->form_validation->Set_rules('tanggal', 'tanggal', 'required');
			$this->form_validation->Set_rules('hal', 'perihal', 'required');
			$this->form_validation->Set_rules('isi', 'isi', 'required');

			if ($kategori == 'internal') {
				$this->form_validation->Set_rules('tujuan_internal', 'tujuan', 'required');
			}
			if ($kategori == 'eksternal') {
				$this->form_validation->Set_rules('tujuan_eksternal', 'tujuan', 'required');
			}

			/*
				if ($ref_kolom != NULL) {
					foreach ($ref_kolom as $key => $val) {
						if ($val['id'] != 1) {
							$this->form_validation->Set_rules($val['id'], $val['nama'], 'required');
						}
					}
				}
			*/
			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run()) {

				$unitId = get_user_unit_id();
				$suratId = decode(urldecode($this->input->post('id', TRUE)));
				$arsipId = decode(urldecode($this->input->post('arsipId', TRUE)));
				// $tanggal = date('Y-m-d H:i:s', strtotime($this->input->post('4', TRUE))); #hard code kolom id ref_kolom
				$tembusan_internal = $this->input->post('tembusan_internal', TRUE);
				$tembusan_eksternal = $this->input->post('tembusan_eksternal', TRUE);

				$surat = array(
					'srtUnitAsalId' => $unitId,
					'srtStatusId' => 1, #status surat 'proses'
					'srtJenisSuratId' => $this->input->post('jenis', TRUE),
					'srtKlasifikasiId' => $this->input->post('klasifikasi', TRUE),
					'srtSifatSurat' => $this->input->post('sifat', TRUE),
					'srtPerihal' => $this->input->post('hal', TRUE),
					'srtIsiRingkasan' => $this->input->post('isi', TRUE),
					'srtTglDraft' =>  date('Y-m-d H:i:s', strtotime($this->input->post('tanggal', TRUE))),
					// 'srtNomorSurat' => $this->input->post('1', TRUE), #hard code kolom id ref_kolom
					// 'srtPerihal' => $this->input->post('10', TRUE), #hard code kolom id ref_kolom
					// 'srtIsiRingkasan' => $this->input->post('9', TRUE), #hard code kolom id ref_kolom
					// 'srtTglDraft' => $tanggal, #hard code kolom id ref_kolom
					'srtUserDrafter' => get_user_id(),
				);

				if ($kategori == 'internal') {
					$surat['srtUnitTujuanUtama'] = $this->input->post('tujuan_internal', TRUE);
				}

				if ($kategori == 'eksternal') {
					$surat['srtTujuanSurat'] = $this->input->post('tujuan_eksternal', TRUE);
				}

				$surat_kolom = '';
				if ($ref_kolom != NULL) {
					foreach ($ref_kolom as $val) {
						$surat_kolom[] = array(
							'surkolJnsSuratKolomId' => $val['jenis_kol_id'],
							'surkolKonten' => $this->input->post($val['id'], TRUE)
						);
					}
				}

				$surat_ref = array(
					'surefSuratRefId' => $suratId,
					'surefArsipRefId' => $arsipId,
				);


				$log = array(
					'logUserId' => get_user_id(),
					'logTanggal' => date('Y-m-d H:i:s'),
					'logUserCreate' => get_user_name(),
					'logTglCreate' => date('Y-m-d H:i:s'),
				);

				$log_status = array(
					'logsStatusId' => 1,
					'logsUserUpdate' => get_user_name(),
					'logsTglUpdate' => date('Y-m-d H:i:s'),
				);

				$tmb_int = '';
				if (count($tembusan_internal) > 0) {
					foreach ($tembusan_internal as $val) {
						$tmb_int[] = array(
							'tembUnitId' => $val
						);
					}
				}

				$tmb_eks = '';
				if ($tembusan_eksternal != '') {
					$tmb_eks = explode("|", $tembusan_eksternal);
				}

				#upload file surat
				$file_surat = $_FILES['file'];
				$ext_surat = '.' . pathinfo($file_surat['name'], PATHINFO_EXTENSION);
				$nama_unit = str_replace(" ", '_', get_user_unit_name());
				if ($file_surat['name'] != '') {
					$nama_file =  'Surat_masuk_' . $nama_unit . '_' . time() . $ext_surat;
					$data = array(
						'tipe' => 'add',
						'file' => 'file',
						'nama_file' => $nama_file
					);
					$upload = $this->upload($data);

					if ($upload['status'] == TRUE) {
						$surat['srtFile'] = $nama_file;
					} else {
						$result = array('error' => array(
							'file' => $upload['error']
						));
						echo json_encode($result);
						die;
					}
				}

				#upload file lampiran
				$file_lampiran = $_FILES['file_lampiran'];
				$nama_unit = str_replace(" ", '_', get_user_unit_name());
				$lampiran = '';
				if ($file_lampiran['name'] != '') {
					foreach ($file_lampiran['name'] as $key => $val) {
						$ext = '.' . pathinfo($val, PATHINFO_EXTENSION);
						$lmp_name = 'Lampiran_surat_' . $nama_unit . '_' . now() . $ext;
						$lampiran[] = array(
							'surlampFileLampiran' => $lmp_name
						);
					}
				}

				$data = array(
					'surat' => $surat,
					'surat_ref' => $surat_ref,
					'surat_kolom' => $surat_kolom,
					'log' => $log,
					'log_status' => $log_status,
					'tembusan_internal' => $tmb_int,
					'tembusan_eksternal' => $tmb_eks,
					'lampiran' => $lampiran
				);

				$proses = $this->M_surat->add_draft($data);
				if ($proses['status'] == TRUE) {
					$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Draft surat berhasil ditambahkan.');
				} else {
					unlink(FCPATH . 'uploads/' . $nama_file);
					$result = array('error' => 'null', 'status' => false, 'type' => 'danger', 'text' => 'Draft surat gagal ditambahkan.');
				}
			} else {
				$result = array('error' => array(
					form_error('jenis') ? "jenis" : "kosong" => form_error('jenis'),
					form_error('sifat') ? "sifat" : "kosong" => form_error('sifat'),
					form_error('klasifikasi') ? "klasifikasi" : "kosong" => form_error('klasifikasi'),
					form_error('kategori') ? "kategori" : "kosong" => form_error('kategori'),
					form_error('tujuan_internal') ? "tujuan_internal" : "kosong" => form_error('tujuan_internal'),
					form_error('tujuan_eksternal') ? "tujuan_eksternal" : "kosong" => form_error('tujuan_eksternal'),
					form_error('tanggal') ? "tanggal" : "kosong" => form_error('tanggal'),
					form_error('hal') ? "hal" : "kosong" => form_error('hal'),
					form_error('isi') ? "isi" : "kosong" => form_error('isi'),
				));
			}
			/*
			if ($ref_kolom != NULL) {
				foreach ($ref_kolom as $key => $val) {
					$kol = ((form_error($val['id'])) ? $val['id'] : 'kosong');
					$result['error'][$kol] = form_error($val['id']);
				}
			}
				*/
			echo json_encode($result);
		}
	}

	private function upload($data)
	{
		$config = array(
			'upload_path'  => $this->config->item('surat_masuk_path'),
			'max_size'      => $this->config->item('surat_masuk_file_max_size'),
			'allowed_types' =>  $this->config->item('surat_masuk_file_allowed_types'),
			'overwrite'    => TRUE,
			'file_name'      => $data['nama_file']
		);

		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if ($data['tipe'] == 'add') {
			if ($this->upload->do_upload($data['file'])) {
				$this->upload->data('file_name');
				return array('status' => TRUE);
			} else {
				return array('status' => FALSE, 'error' => $this->upload->display_errors('', ''));
			}
		} elseif ($data['tipe'] == 'update') {
			if ($this->upload->do_upload($data['file'])) {
				unlink(FCPATH . 'uploads/' . $data['old_file']);
				$this->upload->data('file_name');
				return array('status' => TRUE);
			} else {
				return array('status' => FALSE, 'old_file' => $data['old_file'], 'error' => $this->upload->display_errors('', ''));
			}
		}
	}
}
