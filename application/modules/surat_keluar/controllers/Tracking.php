<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tracking extends Dashboard_Controller
{

	private $module = 'surat_keluar';

	function __construct()
	{
		parent::__construct();
		// loadmodel
		$this->load->model($this->module . '/M_tracking');
		protect_acct();
	}

	function index()
	{
		restrict($this->module . '/Tracking/index');
		$tpl['module'] = $this->module . '/Tracking';

		$this->template->inject_partial('modules_css', multi_asset(array(
			'vendor/datatables/css/dataTables.bootstrap4.min.css' => '_theme_',
		), 'css'));

		$this->template->inject_partial('modules_js', multi_asset(array(
			'vendor/datatables/js/jquery.dataTables.min.js' => '_theme_',
			'vendor/datatables/js/dataTables.bootstrap4.min.js' => '_theme_',
		), 'js'));

		$this->template->title('Tracking Permohonan');
		// $this->template->set_breadcrumb('Dashboard', 'system/dashboard/index');
		$this->template->set_breadcrumb('Tracking Permohonan', '');

		$this->template->build($this->module . '/v_tracking_index', $tpl);
	}

	function datatables_data()
	{
		restrict($this->module . '/Tracking/datatables_data');
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
		$qry = $this->M_tracking->get_daftar_surat($unit_id, $object, $length, $this->input->post('start'), $order);
		$iTotalRecords = (!is_null($qry)) ? intval($this->M_tracking->get_daftar_surat($unit_id, $object, NULL, NULL, NULL, 'counter')) : 0;
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
					IndonesianDate($row['srtTglDraft']),
					$row['srtNomorSurat'],
					$row['srtPerihal'],
					'<div class="btn btn-sm btn-bold btn-round btn-flat w-100px btn-' . $row['stColor'] . '">' . $row['stNama'] . '</div>',
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
		// print_r($this->session->userdata());
		restrict($this->module . '/Tracking/detail');
		if (is_null($encId)) redirect($this->module);
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$id = decode($encId);
		$detail = $this->M_tracking->get_detail_surat($id);
		// print_r($detail);
		$rs_log = $this->M_tracking->get_log_surat($id);
		$rs_log_status = $this->M_tracking->get_log_status_surat($id);

		# timeline  tracking
		$color_dibaca = "default";
		$text_dibaca = "";
		$time_dibaca = "";

		$color_arahan = "default";
		$text_arahan = "";
		$time_arahan = "";

		$color_tindakan = "default";
		$time_tindakan = "";
		$text_tindakan = "";

		$color_dibalas = "default";
		$text_dibalas = "";
		$time_dibalas = "";

		$color_selesai = "default";
		$text_selesai = "";
		$time_selesai = "";

		# info dibaca
		if ($detail['srtTglBaca'] != '') {
			$color_dibaca = "info";
			$diff_time_read = date_diff(date_create($detail['srtTglBaca']), date_create());
			$time_dibaca = ($diff_time_read->h == 0) ? $diff_time_read->i . ' <i>menit yang lalu</i>' : $diff_time_read->h . ' <i>jam yang lalu</i><br>';
			$text_dibaca = IndonesianDate($detail['srtTglBaca']) . ' - ' . date('H:i:s', strtotime($detail['srtTglBaca']));
		}

		# info tindakan
		if ($detail['tindTglCreate'] != '' or $detail['logCatatan'] != '') {
			$color_tindakan = 'cyan';;
			$diff_time_tindakan = ($detail['tindTglCreate'] != '') ? date_diff(date_create($detail['tindTglCreate']), date_create()) : date_diff(date_create($detail['logTanggal']), date_create());
			$time_tindakan = ($diff_time_tindakan->h == 0) ? $diff_time_tindakan->i . ' <i>menit yang lalu</i>' : $diff_time_tindakan->h . ' <i>jam yang lalu</i><br>';
			$text_arahan = '';
			if ($detail['tindTglCreate'] != '') {
				$text_arahan = 'SELESAI' . '<br>';
			}
			$text_tindakan = $text_arahan . ($detail['tindTglCreate'] != '') ? IndonesianDate($detail['tindTglCreate']) . ' - ' . date('H:i:s', strtotime($detail['tindTglCreate'])) : IndonesianDate($detail['logTanggal']) . ' - ' . date('H:i:s', strtotime($detail['logTanggal']));
		}

		# info arahan
		// if ($detail['logPjbTglArahan'] != '') {
		// 	$color_arahan = 'cyan';;
		// 	$diff_time_arahan = date_diff(date_create($detail['logPjbTglArahan']), date_create());
		// 	$time_arahan = ($diff_time_arahan->h == 0) ? $diff_time_arahan->i . ' <i>menit yang lalu</i>' : $diff_time_arahan->h . ' <i>jam yang lalu</i><br>';
		// 	$text_arahan = IndonesianDate($detail['logPjbTglArahan']) . ' - ' . date('H:i:s', strtotime($detail['logPjbTglArahan']));
		// 	$text_dibalas = "Sedang diproses";
		// }

		# info dibalas
		$cek_balasan_surat = $this->M_tracking->get_balasan_surat($id);
		// print_r($cek_balasan_surat);
		if (!empty($cek_balasan_surat)) {
			$color_dibalas = "cyan";
			$diff_time_dibalas = date_diff(date_create($cek_balasan_surat['srtTglDraft']), date_create());
			$time_dibalas = ($diff_time_dibalas->h == 0) ? $diff_time_dibalas->i . ' <i>menit yang lalu</i>' : $diff_time_dibalas->h . ' <i>jam yang lalu</i><br>';
			$text_dibalas = IndonesianDate($cek_balasan_surat['srtTglDraft']) . ' - ' . date('H:i:s', strtotime($cek_balasan_surat['srtTglDraft']));
		}

		if ($cek_balasan_surat['srtIsSigned'] == '1' || $detail['tindTglCreate'] != '') {
			# info selesai
			$color_selesai = "success";
			$text_selesai = "Selesai";
			$diff_time_selesai = date_diff(date_create($cek_balasan_surat['logTglUpdate']), date_create());
			$time_selesai = ($diff_time_selesai->h == 0) ? $diff_time_selesai->i . ' <i>menit yang lalu</i>' : $diff_time_selesai->h . ' <i>jam yang lalu</i><br>';
			if ($detail['tindTglCreate'] != '') {
				$link_surat = 'CATATAN : ' . $detail['tindCatatan'];
			}
			if ($cek_balasan_surat['srtIsSigned'] == '1') {
				$link_surat = '<a href="#" data-href="' . site_url($this->module . '/Tracking/detail_surat/') . encode($cek_balasan_surat['srtId']) . ' class="text-danger" title="Lihat Surat" id="btn-lihat-surat"> Lihat Surat</a>';
			}
			$text_selesai = "Selesai " . IndonesianDate($cek_balasan_surat['logTglUpdate']) . ' - ' . date('H:i:s', strtotime($cek_balasan_surat['logTglUpdate'])) . '<br>' . $link_surat;
		}

		$tpl['module'] = $this->module . '/Tracking/';
		$tpl['data'] = $detail;
		$tpl['log_surat'] = $rs_log;
		$tpl['log_status_surat'] = $rs_log_status;
		$tpl['data'] = $detail;
		$tpl['path'] = $this->config->item('upload_path');

		# info
		$tpl['color_dibaca'] = $color_dibaca;
		$tpl['text_dibaca'] = $text_dibaca;
		$tpl['time_dibaca'] = $time_dibaca;

		$tpl['color_tindakan'] = $color_tindakan;
		$tpl['time_tindakan'] = $time_tindakan;
		$tpl['text_tindakan'] = $text_tindakan;

		$tpl['color_arahan'] =  $color_arahan;
		$tpl['text_arahan'] = $text_arahan;
		$tpl['time_arahan'] =  $time_arahan;

		$tpl['color_dibalas'] = $color_dibalas;
		$tpl['time_dibalas'] = $time_dibalas;
		$tpl['text_dibalas'] = $text_dibalas;

		$tpl['color_selesai'] = $color_selesai;
		$tpl['time_selesai'] = $time_selesai;
		$tpl['text_selesai'] = $text_selesai;

		$this->load->view($this->module . '/v_tracking_detail', $tpl);
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
