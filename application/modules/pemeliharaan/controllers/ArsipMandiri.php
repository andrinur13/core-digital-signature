<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class ArsipMandiri extends Dashboard_Controller {

	private $path = 'pemeliharaan';
	private $master = 'master';
    private $mod = 'ArsipMandiri';

	function __construct() 
	{
		parent::__construct();
		
		
		$this->load->model($this->path.'/m_arsip_mandiri');
        // Models For Basic Form
        $this->load->model($this->master.'/m_form');
		$this->load->helper($this->master.'/general');


		$this->general = new GeneralHelper();
        $this->module = $this->general->module($this->path, $this->mod);
        
        restrict();
	}

	public function index() {
        $this->template->inject_partial('modules_css', multi_asset(array(
			'vendor/datatables/css/dataTables.bootstrap4.min.css' => '_theme_',
		), 'css'));

		$this->template->inject_partial('modules_js', multi_asset(array(
			'vendor/datatables/js/jquery.dataTables.min.js' => '_theme_',
			'vendor/datatables/js/dataTables.bootstrap4.min.js' => '_theme_',
		), 'js'));

        

        $data['roleAdd'] = restrict($this->module.'/add', true);
        $data['module']= $this->module;
        $data['moduleAdd']= $this->module.'/add';
        $data['KlasifikasiList']= $this->m_arsip_mandiri->get_klasifikasi();
        $data['GetAllBerkas']= $this->m_arsip_mandiri->get_berkas(get_user_unit_id());

		// dd($data);

        $this->template->title('Arsip Mandiri');
		$this->template->set_breadcrumb('Arsip Mandiri', $this->module);
		$this->template->build(strtolower($this->module) . '/v_index', $data);
    }

	public function ajax($action = 'datatables') {
        if(is_null( $action ))  exit('Null action');
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
        if($action == 'datatables'){
           	if ($this->input->post('customActionType') == "group_action") {
                if($this->input->post('customActionName') == 'Delete'){
                    $this->delete($this->input->post('data'));
                }
            }else{
                $this->datatables();
            }
        }elseif($action == 'datatables_arsip'){
			if ($this->input->post('customActionType') == "group_action") {
                if($this->input->post('customActionName') == 'Delete'){
                    $this->delete_arsip($this->input->post('data'));
                }
            }else{
				$arsipId = $this->input->get('arsip_id');
                $this->datatables_arsip(decode($arsipId));
            }
		}elseif($action == 'select'){
			$jenis = $this->input->get('type');
			$data = $this->input->get('data');
            if($jenis == 'select_nomor_klasifikasi'){
                $this->get_nomor_klasifikasi($data);
            }
		}elseif($action == 'search_arsip'){
			$jenis = $this->input->get('type');
			$data = $this->input->get('data');
            if($jenis == 'search_surat'){
                $this->get_surat($data);
            }
		}
    }


	private function get_surat($data){
		// dd($data);
		$surat = $this->m_arsip_mandiri->cari_surat_by_nomor($data);
		

		// dd($surat);

		if(!empty($surat)){
			$surat['surat_id'] = encode($surat['surat_id']);
			$status = 200;
			$data = $surat;
			$type = 'success';
			$msg = '';
		}else{
			$status = 200;
			$data = array();
			$type = 'error';
			$msg = 'Surat Tidak Ditemukan';
		}


		

		return $this->output
		->set_content_type('application/json')
		->set_status_header($status)
		->set_output(json_encode([
			'data' => $data,
			'type' => $type,
			'msg' => $msg
		]));
	}

	private function datatables_arsip($arsipId){
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
        $columns = array(
            1 => 'brksNomor',
            2 => 'brksKlasifikasiId',
            3 => 'brksNama',
        );
        
        $object = array();

		// filter data
        $f_klasifikasi = $this->input->post('f_klasifikasi');
        if ($f_klasifikasi != '') {
            $object['brksKlasifikasiId'] = '='.decode($f_klasifikasi);
        }
        
		// $f_berkas = $this->input->post('f_berkas');
        // if ($f_berkas != '') {
        //     $object['brksId'] = '='.decode($f_berkas);
        // }

		$object['arsBerkasId'] = '='.$arsipId;

        $filter_key = $this->input->post('search');
        if ($filter_key['value'] != '') {
            $object['filter_key'] = $filter_key['value'];
        }

        $order = array();
        if($this->input->post('order')){
            foreach( $this->input->post('order') as $row => $val){
                $order[$columns[$val['column']]] = $val['dir'];
            }
        }

        $length = ($this->input->post('length') == -1) ? NULL : $this->input->post('length');
        $qry = $this->m_arsip_mandiri->get_data_arsip($object, $length, $this->input->post('start'), $order, NULL);
        $iTotalRecords = (!is_null($qry)) ? intval($this->m_arsip_mandiri->get_data_arsip($object, NULL, NULL, array(), 'counter')) : 0;
        $iDisplayStart = intval($this->input->post('start'));
        $sEcho = intval($this->input->post('draw'));
        $records = array();
        $records["data"] = array(); 
		// dd($qry->result_array());
        if(!is_null($qry)){
            foreach($qry->result_array() as $row){

				$roleKolom = restrict($this->module.'/arsip', true);
				$btn_edit = '<a data-provide="tooltip" data-original-title="Edit" href="#" id="edit-btn" data-id="'.encode($row['arsId']).'" data-href="'. site_url( $this->module.'/update/'. encode($row['arsId']) ) .'" class="btn btn-square btn-round btn-warning" ><i class="fa fa-pencil"></i></a>';
				$btn_detail = ($roleKolom) ? '<a data-provide="tooltip" data-original-title="Detail Arsip" data-id="'.encode($row['arsId']).'" id="detail-btn" href="javascirip:void(0)" data-href="'. site_url( $this->module.'/arsip/'. encode($row['arsId']) ) .'" class="btn btn-square btn-round btn-primary" ><i class="fa fa-external-link"></i></a>' : '';
                $records["data"][] = array(
                    '<div class="custom-controls-stacked">
						<label class="custom-control custom-checkbox">
							<input type="checkbox" class="custom-control-input data-id" name="data_id[]" value="'. encode($row['arsId']) .'">
							<span class="custom-control-indicator"></span>
						</label>
					</div>',
                    $row['jnsrtNama'],
                    $row['perhial_arsip'],
                    IndonesianDate($row['srtTglDraft']),
                    $btn_detail,
                );
            }
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        echo json_encode($records);
    }

	private function datatables(){
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
        $columns = array(
            1 => 'brksNomor',
            2 => 'brksKlasifikasiId',
            3 => 'brksNama',
        );
        
        $object = array();

		// filter data
        $f_klasifikasi = $this->input->post('f_klasifikasi');
        if ($f_klasifikasi != '') {
            $object['brksKlasifikasiId'] = '='.decode($f_klasifikasi);
        }
        
		// $f_berkas = $this->input->post('f_berkas');
        // if ($f_berkas != '') {
        //     $object['brksId'] = '='.decode($f_berkas);
        // }

		$object['brksUnitId'] = '='.get_user_unit_id();

        $filter_key = $this->input->post('search');
        if ($filter_key['value'] != '') {
            $object['filter_key'] = $filter_key['value'];
        }

        $order = array();
        if($this->input->post('order')){
            foreach( $this->input->post('order') as $row => $val){
                $order[$columns[$val['column']]] = $val['dir'];
            }
        }

        $length = ($this->input->post('length') == -1) ? NULL : $this->input->post('length');
        $qry = $this->m_arsip_mandiri->get_data($object, $length, $this->input->post('start'), $order, NULL);
        $iTotalRecords = (!is_null($qry)) ? intval($this->m_arsip_mandiri->get_data($object, NULL, NULL, array(), 'counter')) : 0;
        $iDisplayStart = intval($this->input->post('start'));
        $sEcho = intval($this->input->post('draw'));
        $records = array();
        $records["data"] = array(); 
		// dd($qry->result_array());
        if(!is_null($qry)){
            foreach($qry->result_array() as $row){

				$roleKolom = restrict($this->module.'/arsip', true);
				$btn_edit = '<a data-provide="tooltip" data-original-title="Edit" href="#" id="edit-btn" data-id="'.encode($row['brksId']).'" data-href="'. site_url( $this->module.'/update/'. encode($row['brksId']) ) .'" class="btn btn-square btn-round btn-warning" ><i class="fa fa-pencil"></i></a>';
				$btn_detail = ($roleKolom) ? '<a data-provide="tooltip" data-original-title="Detail Berkas Arsip" id="set-btn-disabled" href="'. site_url( $this->module.'/arsip/'. encode($row['brksId']) ) .'" class="btn btn-square btn-round btn-primary" ><i class="fa fa-external-link"></i></a>' : '';
                $records["data"][] = array(
                    '<div class="custom-controls-stacked">
						<label class="custom-control custom-checkbox">
							<input type="checkbox" class="custom-control-input data-id" name="data_id[]" value="'. encode($row['brksId']) .'">
							<span class="custom-control-indicator"></span>
						</label>
					</div>',
                    $row['brksNomor'],
                    $row['klasifikasi'],
                    $row['brksNama'],
                    $row['total_arsip'],
                    $btn_detail.'&nbsp;'.$btn_edit,
                );
            }
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        echo json_encode($records);
    }


	private function get_nomor_klasifikasi($data = ''){
		$encId = decode($data);
		$buatNomor = $this->genereateBerkasNumber($encId);
		
		$response = array('data' => $buatNomor);

		echo json_encode($response);
	}

	private function genereateBerkasNumber($klasId){
		// Get Last Number
		$params['filter'] = array('brksKlasifikasiId' => $klasId,'brksUnitId' => get_user_unit_id());
		$lastNumber = $this->m_arsip_mandiri->getLastNumberBerkas($params);

		// dd($lastNumber);

		// Get Detail Klasifikasi By Id

		$klasifikasi['filter'] = array('klasId' => $klasId);
		$detailKlasifikasi = $this->m_arsip_mandiri->getDetailKlaifikasi($klasifikasi);

		if(!empty($lastNumber)){
			if($lastNumber->brksNomor != ''){
				$exploded_format = explode("/",$lastNumber->brksNomor); // memisahkan nomorBerkas berdasarkan tanda "/"
				$num = $exploded_format[4] + 1; // mengambil angka pada kode nomor dan menjumlahkan
				$length = strlen($num); // menghitung panjang karakter dari angka pada nomorBerkas
				if ($length == 1) {
					$BerkasNumber = 'ARSM/'.get_user_unit_kode().'/'.$detailKlasifikasi->klasKode.'/'.$detailKlasifikasi->jnsklasKode.'/000'.$num; // menulis kode spmu
				  }else if ($length == 2) {
					$BerkasNumber = 'ARSM/'.get_user_unit_kode().'/'.$detailKlasifikasi->klasKode.'/'.$detailKlasifikasi->jnsklasKode.'/00'.$num; // menulis kode spmu
				  }else if ($length == 3) {
					$BerkasNumber = 'ARSM/'.get_user_unit_kode().'/'.$detailKlasifikasi->klasKode.'/'.$detailKlasifikasi->jnsklasKode.'/0'.$num; // menulis kode spmu
				  }else if ($length == 4) {
					$BerkasNumber = 'ARSM/'.get_user_unit_kode().'/'.$detailKlasifikasi->klasKode.'/'.$detailKlasifikasi->jnsklasKode.'/'.$num; // menulis kode spmu
				  }
			}else{
				$BerkasNumber = 'ARSM/'.get_user_unit_kode().'/'.$detailKlasifikasi->klasKode.'/'.$detailKlasifikasi->jnsklasKode.'/0001';
			}
		}else{
			$BerkasNumber = 'ARSM/'.get_user_unit_kode().'/'.$detailKlasifikasi->klasKode.'/'.$detailKlasifikasi->jnsklasKode.'/0001';
		}
		

		return $BerkasNumber;
	}

	

	public function add() {
        $data['module'] = $this->module;
        // Form Validation
				$this->form_validation->set_rules('brksKlasifikasiId', 'Klasifikasi', 'required');
				$this->form_validation->set_rules('brksNomor', 'Nomor Berkas', 'required');
				$this->form_validation->set_rules('brksNama', 'Nama Berkas', 'required');
				$this->form_validation->set_error_delimiters('', '');
				if ($this->form_validation->run()) {
					
					$params['data']  = array(	
						'brksKlasifikasiId'	=> decode($this->input->post('brksKlasifikasiId')),
						'brksNomor'	=> $this->input->post('brksNomor'),
						'brksNama'	=> $this->input->post('brksNama'),
						'brksUnitId' => get_user_unit_id(),
						'brksKeterangan' => $this->input->post('brksKeterangan')
					);

					$params['tables'] = 'berkas';

					// dd($params);

					$proses = $this->m_form->insert_form($params);
					if($proses) {
						$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Data berhasil ditambahkan.');
					} else {
						$result = array('error' => 'null', 'status' => false, 'type' => 'error', 'text' => 'Data gagal ditambahkan.');
						// $this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Data gagal disimpan.'));
					}

				}else{
					$error = array(
						'brksKlasifikasiId' => form_error('brksKlasifikasiId'),
						'brksNomor' => form_error('brksNomor'),
						'brksNama' => form_error('brksNama'),
					);
					$result = array('error' => $error);
				}

				echo json_encode($result);
    }

	public function update($encId = NULL){
		if(is_null( $encId )) redirect($this->module);
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		if ($this->input->post('action') == 'submit') {

			$this->form_validation->set_rules('brksKlasifikasiId', 'Klasifikasi', 'required');
			$this->form_validation->set_rules('brksNomor', 'Nomor Berkas', 'required');
			$this->form_validation->set_rules('brksNama', 'Nama Berkas', 'required');
			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run()) {
				$params['data']  = array(	
					'brksKlasifikasiId'	=> decode($this->input->post('brksKlasifikasiId')),
					'brksNomor'	=> $this->input->post('brksNomor'),
					'brksKeterangan'	=> $this->input->post('brksKeterangan'),
					'brksNama'	=> $this->input->post('brksNama')
				);

				$params['tables'] = 'berkas';

				$filter = array('brksId' => decode($encId));
				$proses = $this->m_form->update_form($params,$filter);
				if($proses) {
					$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Data berhasil disimpan.');
				} else {
					$result = array('error' => 'null', 'status' => false, 'type' => 'error', 'text' => 'Data gagal disimpan.');
					// $this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Data gagal disimpan.'));
				}
				$this->output->set_content_type('application/json')->set_output(json_encode($result));
			}else{
				$error = array(
					'brksKlasifikasiId' => form_error('brksKlasifikasiId'),
					'brksNomor' => form_error('brksNomor'),
					'brksNama' => form_error('brksNama'),
				);
				$result = array('error' => $error);
				$this->output->set_content_type('application/json')->set_output(json_encode($result));
			}
		}else{
			if(is_null( $get_data = $this->m_arsip_mandiri->get_data(array('brksId' => ' = '. decode($encId) ), NULL, NULL, NULL, NULL) )) show_404();
			$data['module']     = $this->module;
			$data['data']       = $get_data->row();
			$data['KlasifikasiList']= $this->m_arsip_mandiri->get_klasifikasi();

			// dd($data);

			$this->load->view(strtolower($this->module).'/v_update', $data);
		}
	}

	public function arsip($encId = ''){
		if(is_null( $encId )) redirect($this->module);

		$this->template->inject_partial('modules_css', multi_asset(array(
			'vendor/datatables/css/dataTables.bootstrap4.min.css' => '_theme_',
		), 'css'));

		$this->template->inject_partial('modules_js', multi_asset(array(
			'vendor/datatables/js/jquery.dataTables.min.js' => '_theme_',
			'vendor/datatables/js/dataTables.bootstrap4.min.js' => '_theme_',
		), 'js'));

        

        $data['roleAdd'] = restrict($this->module.'/add', true);
        $data['module']= $this->module;
        $data['moduleAdd']= $this->module.'/add';
        $data['KlasifikasiList']= $this->m_arsip_mandiri->get_klasifikasi();
        $data['GetAllBerkas']= $this->m_arsip_mandiri->get_berkas(get_user_unit_id());
        $data['ArsipId']= $encId;
        $data['detail_berkas']= $this->m_arsip_mandiri->getDetailBerkas(decode($encId));

		// dd($data);

        $this->template->title('Detail Berkas Arsip');
		$this->template->set_breadcrumb('Berkas Arsip', $this->module);
		$this->template->set_breadcrumb('Detail Berkas Arsip','');
		$this->template->build(strtolower($this->module) . '/v_arsip', $data);	
	}

	public function add_arsip($encId = ''){
		if(is_null( $encId )) redirect($this->module);
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		if ($this->input->post('action') == 'submit') {

			$action = TRUE;

			$this->form_validation->set_rules('data_id[]', 'Nomor Surat', 'required');
			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run()) {
				$arsip = $this->input->post('data_id');
				foreach($arsip as $val){
					$filter = array('arsId' =>  decode($val));
					$params['data']  = array(	
						'arsBerkasId'	=> decode($encId),
						'arsUserUpdate' => get_user_name(),
						'arsTglUpdate' => date('Y-m-d H:i:s')
					);
					$params['tables'] = 'arsip';

					if($this->m_form->update_form($params,$filter)){
						
					} else {
						$action = FALSE;
						break;
					}
				}
				
				
				if($action) {
					$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Data berhasil disimpan.');
				} else {
					$result = array('error' => 'null', 'status' => false, 'type' => 'error', 'text' => 'Data gagal disimpan.');
					// $this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Data gagal disimpan.'));
				}
				$this->output->set_content_type('application/json')->set_output(json_encode($result));
			}else{
				$error = array(
					'data_id' => form_error('data_id'),
				);
				$result = array('error' => $error);
				$this->output->set_content_type('application/json')->set_output(json_encode($result));
			}
		}else{
			// if(is_null( $get_data = $this->m_arsip_mandiri->get_data_arsip(array('arsBerkasId' => ' = '. decode($encId) ), NULL, NULL, NULL, NULL) )) show_404();
			$data['module']     = $this->module;
			$data['data_arsip']       = $this->m_arsip_mandiri->get_list_arsip_internal();
			$data['jenis_exemplar']= $this->m_arsip_mandiri->get_jenis_exemplar();

			// dd($data);

			$this->load->view(strtolower($this->module).'/v_add_arsip', $data);
		}
	}

	public function arsip_detail($encId = ''){
		if(is_null( $encId )) redirect($this->module);
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		$data['module']     = $this->module;
			$data['data']       = $this->m_arsip_mandiri->get_detail_arsip_surat(decode($encId));
			$dt_referensi_surat = $this->m_arsip_mandiri->get_data_referensi_arsip_surat(decode($encId));
			$data['dt_referensi_surat'] = $dt_referensi_surat;
			// dd($data);
			$this->load->view(strtolower($this->module).'/v_detail_arsip', $data);
	}

	public function delete($data) {
		$restrict = restrict( $this->module.'/delete', TRUE);
        $action = TRUE;

        $params['tables'] = 'berkas';

        
        if($restrict == TRUE){
            foreach($data as $val){
                $params['data'] = array('brksId' => decode($val)) ;
                if($this->m_form->delete_form($params)){
                    
                } else {
                    $action = FALSE;
                    break;
                }
            }
            if($action == TRUE){
                $records["customActionStatus"] = "success"; 
                $records["customActionMessage"] = "Data yang anda pilih berhasil dihapus!";
            } else {
                $records["customActionStatus"] = "warning"; 
                $records["customActionMessage"] = "Data yang anda pilih gagal untuk dihapus!, silahkan cek kembali apakah data yang anda pilih sudah digunakan pada data yang lain seperti pada data arsip.";
            } 
        }else {
            $records["customActionStatus"] = "warning";
            $records["customActionMessage"] = "Maaf, anda tidak memperoleh akses untuk aksi ini!";
        }

        echo json_encode($records);
	}

	public function delete_arsip($data) {
		$restrict = restrict( $this->module.'/delete', TRUE);
        $action = TRUE;

        $params['tables'] = 'berkas';

		// dd($data);
        
        if($restrict == TRUE){
            foreach($data as $val){
                // $params['data'] = array('brksId' => decode($val)) ;
				$params['data']  = array(	
					'arsBerkasId'	=> NULL,
					'arsUserUpdate'	=> get_user_name(),
					'arsTglUpdate'	=> date('Y-m-d H:i:s')
				);

				$params['tables'] = 'arsip';

				$filter = array('arsId' => decode($val));
				$proses = $this->m_form->update_form($params,$filter);

                if($proses){
                    
                } else {
                    $action = FALSE;
                    break;
                }
            }
            if($action == TRUE){
                $records["customActionStatus"] = "success"; 
                $records["customActionMessage"] = "Data yang anda pilih berhasil dihapus!";
            } else {
                $records["customActionStatus"] = "warning"; 
                $records["customActionMessage"] = "Data yang anda pilih gagal untuk dihapus!, silahkan cek kembali apakah data yang anda pilih sudah digunakan pada data yang lain seperti pada data arsip.";
            } 
        }else {
            $records["customActionStatus"] = "warning";
            $records["customActionMessage"] = "Maaf, anda tidak memperoleh akses untuk aksi ini!";
        }

        echo json_encode($records);
	}
}


/* End of file ArsipMandiri.php */
/* Location: D:\laragon\www\bsi-devel\uad\e-office-uad\application\modules\pemeliharaan\controllers\ArsipMandiri.php */