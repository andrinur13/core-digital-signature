<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

defined('BASEPATH') or exit('No direct script access allowed');

class Arahan extends Dashboard_Controller
{

	private $module = 'surat_arahan';


	function __construct()
	{
		parent::__construct();

		$this->load->model($this->module . '/M_arahan');
		restrict();
	}

	public function index()
	{

		$tpl['module'] = $this->module . '/Arahan';
		$tpl['isPejabat'] = (get_user_group() == 4) ? TRUE : FALSE;
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
		$this->template->title('Arahan/Rapat');
		$this->template->set_breadcrumb('Dashboard', 'dashboard/Dashboard/index');
		$this->template->set_breadcrumb('arahan rapat', '');
		$this->template->build($this->module . '/v_index', $tpl);
	}

	public function datatables()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$unitId = get_user_unit_id();
		$tipe = $this->input->get('tipe', TRUE);
		$id = urldecode(decode($this->input->get('id', TRUE)));
		$isPejabat = (get_user_group() == 4) ? TRUE : FALSE;

		#datatable disposisi
		if ($tipe == 'detail_arahan') {
			$detail = $this->M_arahan->detail($id, $isPejabat);
			$tpl['module'] = $this->module . '/Arahan';
			$tpl['detail'] = $detail;
			$tpl['status'] = $this->M_arahan->status_tindakan($id);
			$tpl['isPejabat'] = (get_user_group() == 4) ? TRUE : FALSE;
			$tpl['path'] = $this->config->item('surat_masuk_path');
			if (($isPejabat == TRUE) && ($detail['tglBacaArahan'] == '')) {
				$data = array(
					'data' => array(
						'logPjbTglBaca' => date('Y-m-d H:i:s'),
						'logPjbIsDibaca' => 1
					),
					'id' => $detail['logId']
				);
				$this->M_arahan->baca_arahan($data);
			}

			$this->load->view($this->module . '/v_detail', $tpl);
		}
		if ($isPejabat == FALSE && $tipe != 'detail_arahan') {
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

			$qry = $this->M_arahan->get_list($params, $object, $length, $this->input->post('start'), $order, NULL);
			$iTotalRecords = (!is_null($qry)) ? intval($this->M_arahan->get_list($params, $object, NULL, NULL, NULL, 'counter')) : 0;
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
					$aksi = '<a href="#" type="button" class="btn btn-square btn-round btn-success" data-toggle="tooltip" title="ubah arahan" onclick="arahan(' . "'" . encode(urlencode($row['id'])) . "'" . ')"><i class="fa fa-pencil"></i></a>';

					$kategori = '<small><span class="badge badge-' . (($row['asal_surat'] == 'Internal') ? 'success' : 'info') . '">' . $row['asal_surat'] . '</span></small><br>';

					$catatan = strip_tags($row['arahan']);
					if (strlen($catatan) > 35) {
						$catatanCut = substr($catatan, 0, 35);
						$catatan = substr($catatanCut, 0, strrpos($catatanCut, ' ')) . " ...";
					}

					$arahan = '<span data-toggle="tooltip" data-placement="left" title="' . $row['arahan'] . '">' . $catatan . '</span>';

					$records["data"][] = array(
						IndonesianDate($row['tanggal']),
						$kategori . ' ' . $row['asal'],
						$row['nomor'],
						$row['perihal'],
						$arahan,
						$aksi . ' ' . $file,
					);
				}
			}
			$records["draw"] = $isEcho;
			$records["recordsTotal"] = $iTotalRecords;
			$records["recordsFiltered"] = $iTotalRecords;

			echo json_encode($records);
		}

		if ($isPejabat == TRUE && $tipe != 'detail_arahan') {
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

			$object['logPjbId'] = '=' . get_user_id();

			if ($this->input->post('sifat', TRUE) != '') {
				$object['srtSifatSurat'] = '=' . $this->input->post('sifat', TRUE);
			}

			if ($this->input->post('asal', TRUE) != '') {
				$object['srtUnitAsalId'] = '=' . $this->input->post('asal', TRUE);
			}

			$length = ($this->input->post('length') == -1) ? NULL : $this->input->post('length');

			$qry = $this->M_arahan->get_list_arahan($params, $object, $length, $this->input->post('start'), $order, NULL);
			$iTotalRecords = (!is_null($qry)) ? intval($this->M_arahan->get_list_arahan($params, $object, NULL, NULL, NULL, 'counter')) : 0;
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
					$aksi = '<a href="#" type="button" class="btn btn-square btn-round btn-success" data-toggle="tooltip" title="ubah arahan" onclick="arahan(' . "'" . encode(urlencode($row['id'])) . "'" . ')"><i class="fa fa-pencil"></i></a>';

					$kategori = '<small><span class="badge badge-' . (($row['asal_surat'] == 'Internal') ? 'success' : 'info') . '">' . $row['asal_surat'] . '</span></small><br>';

					$catatan = strip_tags($row['arahan']);
					if (strlen($catatan) > 35) {
						$catatanCut = substr($catatan, 0, 35);
						$catatan = substr($catatanCut, 0, strrpos($catatanCut, ' ')) . " ...";
					}

					$arahan = '<span data-toggle="tooltip" data-placement="left" title="' . $row['arahan'] . '">' . $catatan . '</span>';

					$records["data"][] = array(
						($row['tglBacaArahan'] == '') ? '<span class="font-weight-bold">' . IndonesianDate($row['tanggal']) . '</span>' : IndonesianDate($row['tanggal']),

						($row['tglBacaArahan'] == '') ? '<span class="font-weight-bold">' . $kategori .  $row['asal'] . '</span>' : $kategori . $row['asal'],

						($row['tglBacaArahan'] == '') ? '<span class="font-weight-bold">' . 'No. : ' . $row['nomor'] . '<br>Hal: ' . $row['perihal'] . '</span>' : 'No.: ' . $row['nomor'] . '<br>Hal : ' . $row['perihal'],
						($row['tglBacaArahan'] == '') ? '<span class="font-weight-bold">' . $arahan . '</span>' : $arahan,
						($row['tglBacaArahan'] == '') ? '<span class="font-weight-bold">' . $row['jawaban'] . '</span>' : $row['jawaban'],
						$aksi . ' ' . $file,
					);
				}
			}
			$records["draw"] = $isEcho;
			$records["recordsTotal"] = $iTotalRecords;
			$records["recordsFiltered"] = $iTotalRecords;

			echo json_encode($records);
		}
	}

	public function jawab()
	{
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$unitId = get_user_unit_id();
		$tipe = $this->input->get('tipe', TRUE);
		$mode = $this->input->get('mode', TRUE);

		if ($tipe == 'proses') {
			$this->form_validation->Set_rules('catatan_jawaban', 'catatan', 'required');
			$this->form_validation->set_error_delimiters('', '');

			if ($this->form_validation->run()) {
				$id = urldecode(decode($this->input->post('id', TRUE)));

				$params = array(
					'logPjbArahan' => $this->input->post('catatan_jawaban', TRUE),
					'logPjbTglArahan' => date('Y-m-d H:i:s'),
					'logPjbUserArahan' => get_user_real_name(),
				);

				$data = array(
					'data' => $params,
					'id' => $id,
					'mode' => $mode
				);

				$proses = $this->M_arahan->beri_arahan($data);

				if ($proses['status'] == TRUE) {
					$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Arahan surat berhasil disimpan');
				} else {
					$result = array('error' => 'null', 'status' => false, 'type' => 'danger', 'text' => 'Arahan surat gagal disimpan');
				}
			} else {
				$result = array('error' => array(
					form_error('catatan_jawaban') ? "catatan_jawaban" : "kosong" => form_error('catatan_jawaban'),
				));
			}
			echo json_encode($result);
		}
	}
}
