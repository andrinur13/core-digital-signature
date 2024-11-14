<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Dokumen extends Dashboard_Controller {

	private $path = 'master';
    private $mod = 'Dokumen';

	function __construct() 
	{
		parent::__construct();
		
		
		$this->load->model($this->path.'/m_dokumen');
        // Models For Basic Forms
        $this->load->model($this->path.'/m_form');
		$this->load->helper($this->path.'/general');


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
		$data['jenisDokumen'] = $this->m_dokumen->get_jenis_dokumen();
		$data['listPejabat'] = $this->m_dokumen->get_pejabat();
        $data['moduleAdd']= $this->module.'/add';

		// dd($data);

        $this->template->title('Upload Dokumen');
		$this->template->set_breadcrumb('Upload Dokumen', $this->module);
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
        }elseif($action == 'modal_pejabat'){
			$this->modal_view('pejabat');
		}elseif($action == 'datatables_modal'){
            $this->datatables_modal();
        }
    }

	private function modal_view($jenis = 'pejabat'){
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
        $data['module']  = $this->module;
        if($jenis == 'pejabat'){
            $this->load->view(strtolower($this->module) . '/v_modal', $data);
        }
    }

	private function datatables(){
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
        $columns = array(
            1 => 'dokJnsId',
            2 => 'dokNoSrt',
            3 => 'dokNama',
        );
        
        $object = array();

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
        $qry = $this->m_dokumen->get_data($object, $length, $this->input->post('start'), $order, NULL);
        $iTotalRecords = (!is_null($qry)) ? intval($this->m_dokumen->get_data($object, NULL, NULL, array(), 'counter')) : 0;
        $iDisplayStart = intval($this->input->post('start'));
        $sEcho = intval($this->input->post('draw'));
        $records = array();
        $records["data"] = array(); 
        if(!is_null($qry)){
            foreach($qry->result_array() as $row){
                $records["data"][] = array(
                    '<input type="checkbox" class="data-id" name="data_id[]" value="'. encode($row['dokId']) .'">',
                    $row['dokJnsId'],
                    $row['dokNoSrt'],
                    $row['dokNama'],
                    $row['dokCatatan'],
                    'Proses',
                    $row['dokStatus'],
                    '<a data-provide="tooltip" data-original-title="Lihat Dokumen"  id="detail-btn" href="'. site_url( $this->module.'/detail/'. encode($row['dokId']) ) .'" class="btn btn-square btn-round btn-info" ><i class="fa fa-archive"></i></a>',
                );
            }
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        echo json_encode($records);
    }

	public function detail($encDocId){
		if(is_null( $encDocId )) redirect($this->module);
		$data['module']= $this->module;
		$id = decode($encDocId);
		
		$data['detail'] = $this->m_dokumen->detail_dokumen($id);
		$data['dokumenId'] = $encDocId;
		$data['path_dokumen'] = $this->config->item('upload_path').'dokumen/';
		
		$this->template->title('Detail Dokumen');
		$this->template->set_breadcrumb('Upload Dokumen', $this->module);
		$this->template->set_breadcrumb( 'Detail' , '' );
		$this->template->build(strtolower($this->module) . '/v_detail', $data);
	}

	public function save_pdf(){
		$this->load->library('signature');
		$data = json_decode($this->input->post('data'), true);
		$dokumenId = decode($this->input->post('dokumen'));
		$dataDokumen = $this->m_dokumen->detail_dokumen($dokumenId);
		$lokasi =  $this->config->item('upload_path').'dokumen/';

		$dokumenPdf = FCPATH.$lokasi.$dataDokumen->dokFile;
		$qrImage = FCPATH.$this->config->item('upload_path').'qrcode/qr_code.png';

		$generete = $this->signature->build_pdf($data,$qrImage ,$dokumenPdf,$lokasi.'fix/output_with_qrcode_' . time() . '.pdf');

		echo $generete;
	}

	private function datatables_modal(){
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');

        
        $columns = array(
            1 => 'pjbKode',
            2 => 'pjbNama',
            3 => 'pjbJabatan',
        );

        $object = array();

        $filter_key = $this->input->post('search');
        if ($filter_key['value'] != '') {
            $object['filter_key'] = $filter_key['value'];
        }

        if($this->input->post('f_jenis') != ''){
            $object['staseJenis'] = '='.$this->input->post('f_jenis');
        }

        

        $order = array();
        if($this->input->post('order')){
            foreach( $this->input->post('order') as $row => $val){
                $order[$columns[$val['column']]] = $val['dir'];
            }
        }

        $length = ($this->input->post('length') == -1) ? NULL : $this->input->post('length');
        $qry = $this->m_dokumen->get_data_pejabat($object, $length, $this->input->post('start'), $order, NULL);
        $iTotalRecords = (!is_null($qry)) ? intval($this->m_dokumen->get_data_pejabat($object, NULL, NULL, array(), 'counter')) : 0;
        $iDisplayStart = intval($this->input->post('start'));
        $sEcho = intval($this->input->post('draw'));
        $records = array();
        $records["data"] = array(); 
        if(!is_null($qry)){
            foreach($qry->result_array() as $row){
                $act_edit = '<a class="btn btn-info btn-xs" id="btnPilih" href="#" data-id="'.encode($row['pjbId']).'" title="Pilih Data"><i class="fa fa-check"></i></a>';
                $records["data"][] = array(
                    $act_edit,
                    $row['pjbKode'],
                    $row['pjbNama'],
                    $row['pjbJabatan'],
                );
            }
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        echo json_encode($records);


    }

	public function add($jenis_add = 'step_one', $encId = '') {
        if($jenis_add == 'step_one'){
			$this->add_default($encId);
			// dd($this->input->post());
		}elseif($jenis_add == 'step_two'){
			$this->add_two($encId);
		}elseif($jenis_add == 'step_three'){
			$this->add_three($encId);
		}
    }

	private function add_three($encId){
		$data['module'] = $this->module;
		$data['moduleAdd'] = $this->module.'/add/step_three/'.$encId;
		$data['createStepZero']= $this->module.'/add/step_one/'.$encId;
		$data['createStepOne']= $this->module.'/add/step_one/'.$encId;
		$data['createStepTwo'] = $this->module.'/add/step_two/'.$encId;
		$data['createStepThree'] = $this->module.'/add/step_three/'.$encId;
		$data['kembali']= $this->module.'/add/step_two/'.$encId;
		// $next = $this->module.'/add/finish/'.$encId;
		$next = $this->module;
		$dokumenId = decode($encId);

		// cek dulu apakah ini sudah ada pemaraf atau belum
		$cek = $this->m_dokumen->cek_dokumen_penandatangan($dokumenId);
		// dd($cek);
		if(!empty($cek)){
			
			$id = decode($encId);

			$this->form_validation->set_rules('dokumen_id', 'Dokumen', 'required');
			if ($this->form_validation->run()) {
				// dd($this->input->post());
				$pejabat = $this->input->post('pejabat_id');
				$dokumen_id = decode($this->input->post('dokumen_id'));

				// ambil dulu Id yang ada pada Database
				
				$pjbIdData = array();
				$pejbt = $cek;
				foreach($pejbt as $p => $pj){
					$pjbIdData[] = $pj->dokttdPjbId;
				}
				// dd($pjbIdData);



				$pjbId = array();
				foreach($pejabat as $i => $pjb){
					$pjbId[] = decode($pjb);
				}

				// cek dari inputan apakah ada data yang berbeda dari database
				$onlyInVa1 = array_diff($pjbIdData, $pjbId);

				if(!empty($onlyInVa1)){
					foreach($onlyInVa1 as $i => $pj){
						$params['data'] = array('dokttdDokId' => $dokumen_id,'dokttdPjbId' => $pj) ;
						$params['tables'] = 'dokumen_penandatangan';
						$delete = $this->m_form->delete_form($params);
						if($delete){
							$this->session->set_flashdata('msg', array('status' => 'success', 'title' => 'Informasi', 'message' => 'Pemaraf Berhasil Ditambahkan'));
						}else{
							$this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Pemaraf Gagal Ditambahkan'));
						}
					}
				}

				// dd($onlyInVa1);

				// Inputkan ID Pejabat yang tidak ada dalam database
				$data_pejabat = array_diff($pjbId, $pjbIdData);
				if(!empty($data_pejabat)){
					foreach($data_pejabat as $i => $pj){
						$param['data'] = array(
							'dokttdDokId' => $dokumen_id,
							'dokttdPjbId' => $pj
						);
						
						$param['tables'] = 'dokumen_penandatangan';
						// dd($param);
	
						$insert = $this->m_form->insert_form($param);
						if($insert){
							$this->session->set_flashdata('msg', array('status' => 'success', 'title' => 'Informasi', 'message' => 'Penandatangan Berhasil Ditambahkan'));
						}else{
							$this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Penandatangan Gagal Ditambahkan'));
						}
					}
				}


				redirect($next);

			}

			$data['jenisDokumen'] = $this->m_dokumen->get_jenis_dokumen();
			$data['penandatangan'] = $cek;
			$data['path'] = $this->config->item('upload_path').'dokumen/draft/';
			$data['dokumen_id'] = $encId;

			// dd($data);

			$this->template->title( 'Ajukan Dokumen' );
			$this->template->set_breadcrumb( 'Dokumen' , $this->module);
			$this->template->set_breadcrumb( 'Ajukan' , $this->module.'/add');

			$this->template->build(strtolower($this->module) . '/v_edit_dokumen_penandatangan', $data);
		}else{
			$this->form_validation->set_rules('dokumen_id', 'Dokumen', 'required');
			if ($this->form_validation->run()) {
				// dd($this->input->post());
				$pejabat = $this->input->post('pejabat_id');
				$dokumen_id = decode($this->input->post('dokumen_id'));

				foreach($pejabat as $i => $pj){
					$param['data'] = array(
						'dokttdDokId' => $dokumen_id,
						'dokttdPjbId' => decode($pj)
					);
					
					$param['tables'] = 'dokumen_penandatangan';
					// dd($param);

					$insert = $this->m_form->insert_form($param);
					if($insert){
						$this->session->set_flashdata('msg', array('status' => 'success', 'title' => 'Informasi', 'message' => 'Penandatangan Berhasil Ditambahkan'));
					}else{
						$this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Penandatangan Gagal Ditambahkan'));
					}
				}

				redirect($next);
			}

			$this->template->inject_partial('modules_css', multi_asset(array(
				'vendor/datatables/css/dataTables.bootstrap4.min.css' => '_theme_',
			), 'css'));

			$this->template->inject_partial('modules_js', multi_asset(array(
				'vendor/datatables/js/jquery.dataTables.min.js' => '_theme_',
				'vendor/datatables/js/dataTables.bootstrap4.min.js' => '_theme_',
			), 'js'));

			$data['dokumen_id'] = $encId;
			$this->template->title( 'Ajukan Dokumen' );
			$this->template->set_breadcrumb( 'Dokumen' , $this->module);
			$this->template->set_breadcrumb( 'Ajukan' , $this->module.'/add');

			$this->template->build(strtolower($this->module) . '/v_add_penandatangan', $data);
		}
	}


	private function add_two($encId){
		$data['module'] = $this->module;
		$data['moduleAdd'] = $this->module.'/add/step_two/'.$encId;
		$data['createStepZero']= $this->module.'/add/step_one/'.$encId;
		$data['createStepOne']= $this->module.'/add/step_one/'.$encId;
		$data['createStepTwo'] = $this->module.'/add/step_two/'.$encId;
		$data['createStepThree'] = $this->module.'/add/step_three/'.$encId;
		$next = $this->module.'/add/step_three/'.$encId;
		$dokumenId = decode($encId);

		// cek dulu apakah ini sudah ada pemaraf atau belum
		$cek = $this->m_dokumen->cek_dokumen_pemaraf($dokumenId);
		if(!empty($cek)){
			
			$id = decode($encId);

			$this->form_validation->set_rules('dokumen_id', 'Dokumen', 'required');
			if ($this->form_validation->run()) {
				// dd($this->input->post());
				$pejabat = $this->input->post('pejabat_id');
				$dokumen_id = decode($this->input->post('dokumen_id'));

				// ambil dulu Id yang ada pada Database
				
				$pjbIdData = array();
				$pejbt = $this->m_dokumen->cek_dokumen_pemaraf($id);
				foreach($pejbt as $p => $pj){
					$pjbIdData[] = $pj->dokprPjbId;
				}
				// dd($pjbIdData);



				$pjbId = array();
				foreach($pejabat as $i => $pjb){
					$pjbId[] = decode($pjb);
				}

				// cek dari inputan apakah ada data yang berbeda dari database
				$onlyInVa1 = array_diff($pjbIdData, $pjbId);

				if(!empty($onlyInVa1)){
					foreach($onlyInVa1 as $i => $pj){
						$params['data'] = array('dokprDokId' => $dokumen_id,'dokprPjbId' => $pj) ;
						$params['tables'] = 'dokumen_paraf';
						$delete = $this->m_form->delete_form($params);
						if($delete){
							$this->session->set_flashdata('msg', array('status' => 'success', 'title' => 'Informasi', 'message' => 'Pemaraf Berhasil Ditambahkan'));
						}else{
							$this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Pemaraf Gagal Ditambahkan'));
						}
					}
				}

				// dd($onlyInVa1);

				// Inputkan ID Pejabat yang tidak ada dalam database
				$data_pejabat = array_diff($pjbId, $pjbIdData);
				if(!empty($data_pejabat)){
					foreach($data_pejabat as $i => $pj){
						$param['data'] = array(
							'dokprDokId' => $dokumen_id,
							'dokprPjbId' => $pj
						);
						
						$param['tables'] = 'dokumen_paraf';
						// dd($param);
	
						$insert = $this->m_form->insert_form($param);
						if($insert){
							$this->session->set_flashdata('msg', array('status' => 'success', 'title' => 'Informasi', 'message' => 'Pemaraf Berhasil Ditambahkan'));
						}else{
							$this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Pemaraf Gagal Ditambahkan'));
						}
					}
				}


				redirect($next);

			}

			$data['jenisDokumen'] = $this->m_dokumen->get_jenis_dokumen();
			$data['pemaraf'] = $this->m_dokumen->cek_dokumen_pemaraf($id);
			$data['path'] = $this->config->item('upload_path').'dokumen/draft/';
			$data['dokumen_id'] = $encId;

			// dd($data);

			$this->template->title( 'Ajukan Dokumen' );
			$this->template->set_breadcrumb( 'Dokumen' , $this->module);
			$this->template->set_breadcrumb( 'Ajukan' , $this->module.'/add');

			$this->template->build(strtolower($this->module) . '/v_edit_dokumen_pemaraf', $data);
		}else{
			$this->form_validation->set_rules('dokumen_id', 'Dokumen', 'required');
			if ($this->form_validation->run()) {
				// dd($this->input->post());
				$pejabat = $this->input->post('pejabat_id');
				$dokumen_id = decode($this->input->post('dokumen_id'));

				foreach($pejabat as $i => $pj){
					$param['data'] = array(
						'dokprDokId' => $dokumen_id,
						'dokprPjbId' => decode($pj)
					);
					
					$param['tables'] = 'dokumen_paraf';
					// dd($param);

					$insert = $this->m_form->insert_form($param);
					if($insert){
						$this->session->set_flashdata('msg', array('status' => 'success', 'title' => 'Informasi', 'message' => 'Pemaraf Berhasil Ditambahkan'));
					}else{
						$this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Pemaraf Gagal Ditambahkan'));
					}
				}

				redirect($next);
			}

			$this->template->inject_partial('modules_css', multi_asset(array(
				'vendor/datatables/css/dataTables.bootstrap4.min.css' => '_theme_',
			), 'css'));

			$this->template->inject_partial('modules_js', multi_asset(array(
				'vendor/datatables/js/jquery.dataTables.min.js' => '_theme_',
				'vendor/datatables/js/dataTables.bootstrap4.min.js' => '_theme_',
			), 'js'));

			$data['dokumen_id'] = $encId;
			$this->template->title( 'Ajukan Dokumen' );
			$this->template->set_breadcrumb( 'Dokumen' , $this->module);
			$this->template->set_breadcrumb( 'Ajukan' , $this->module.'/add');

			$this->template->build(strtolower($this->module) . '/v_add_pemaraf', $data);
		}
		// dd($dokumenId);
	}

	private function add_default($encId = ''){
		$data['module'] = $this->module;
		$data['moduleAdd']= $this->module.'/add/step_one';
		$data['createStepTwo']= $this->module.'/add/step_two/'.$encId;
		$moduleTambahParaf= $this->module.'/add/step_two';

		// dd($encId);

		// Jika ada Encrypt Id berarti Fungsi Ubah
		if(!empty($encId)){
			$this->form_validation->set_rules('dokId', 'Dokumen Id', 'required');
			if ($this->form_validation->run()) {


			}

			$id = decode($encId);

			$data['jenisDokumen'] = $this->m_dokumen->get_jenis_dokumen();
			$data['dokumen'] = $this->m_dokumen->detail_dokumen($id);
			$data['path'] = $this->config->item('upload_path').'dokumen/draft/';

			// dd($data);

			$this->template->title( 'Ajukan Dokumen' );
			$this->template->set_breadcrumb( 'Dokumen' , $this->module);
			$this->template->set_breadcrumb( 'Ajukan' , $this->module.'/add');

			$this->template->build(strtolower($this->module) . '/v_edit_dokumen', $data);

			

		}else{
			$this->form_validation->set_rules('dokJnsId', 'Jenis Dokumen', 'required');
			$this->form_validation->set_rules('dokNoSrt', 'Nomor Surat', 'required');
			$this->form_validation->set_rules('dokNama', 'Judul Dokumen', 'required');
			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run()) {
				// dd($this->input->post());
				// dd($_FILES);
				if ($_FILES['file_surat']['name'] != '') {
					$fileName = $_FILES['file_surat']['name'];
					$file = 'file_surat';
					$upload_file = $this->DoUploadDokumen($file, $fileName);

					// dd($_POST);
					if ($upload_file != false) {
						$dokumen = $upload_file;
					} else {
						$result = array('error' => 'null', 'status' => false, 'type' => 'error', 'text' => 'Upload file gagal.');
					}
				}
				
				$params['data']  = array(	
					'dokJnsId'	=> decode($this->input->post('dokJnsId')),
					'dokNoSrt'	=> $this->input->post('dokNoSrt'),
					'dokNama'	=> $this->input->post('dokNama'),
					'dokCatatan'	=> $this->input->post('dokCatatan'),
					'dokFile'	=> $dokumen,
					'dokUserAdd'	=> get_user_id(),
					'dokUserAddDate'	=> date('Y-m-d'),
				);

				$params['tables'] = ' dokumen';

				$proses = $this->m_form->insert_form($params,'last_id');
				if($proses['status']) {
					$this->session->set_flashdata('msg', array('title' => 'Informasi!','status' => 'success', 'message' => 'Upload Naskah Dokumen berhasil.'));
				} else {
					$this->session->set_flashdata('msg', array('title' => 'Informasi!','status' => 'danger', 'message' => 'Upload Naskah Dokumen Gagal.'));
					// $this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Data gagal disimpan.'));
				}
				redirect(site_url($moduleTambahParaf.'/'.encode($proses['last_id'])));
			}
			$data['jenisDokumen'] = $this->m_dokumen->get_jenis_dokumen();

			$this->template->title( 'Ajukan Dokumen' );
			$this->template->set_breadcrumb( 'Dokumen' , $this->module);
			$this->template->set_breadcrumb( 'Ajukan' , $this->module.'/add');

			$this->template->build(strtolower($this->module) . '/v_add_dokumen', $data);
		}
	}

	public function update($encId = NULL){
		if(is_null( $encId )) redirect($this->module);
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		if ($this->input->post('action') == 'submit') {

			$this->form_validation->set_rules('jnsklasNama', 'Jenis Klasifikasi Nama', 'required');
			$this->form_validation->set_rules('jnsklasKode', 'Jenis Klasifikasi Kode', 'required');
			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run()) {
				$params['data']  = array(	
					'jnsklasNama'	=> $this->input->post('jnsklasNama'),
					'jnsklasKode'	=> $this->input->post('jnsklasKode'),
				);

				$params['tables'] = 'klasifikasi_jenis_ref';

				$filter = array('dokId' => decode($encId));
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
					'jnsklasNama' => form_error('jnsklasNama'),
					'jnsklasKode' => form_error('jnsklasKode'),
				);
				$result = array('error' => $error);
				$this->output->set_content_type('application/json')->set_output(json_encode($result));
			}
		}else{
			if(is_null( $get_data = $this->m_dokumen->get_data(array('dokId' => ' = '. decode($encId) ), NULL, NULL, NULL, NULL) )) show_404();
			$data['module']     = $this->module;
			$data['data']       = $get_data->row();

			$this->load->view(strtolower($this->module).'/v_update', $data);
		}
	}

	public function delete($data) {
		$restrict = restrict( $this->module.'/delete', TRUE);
        $action = TRUE;

        $params['tables'] = 'klasifikasi_jenis_ref';

        
        if($restrict == TRUE){
            foreach($data as $val){
                $params['data'] = array('dokId' => decode($val)) ;
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
                $records["customActionMessage"] = "Data yang anda pilih gagal untuk dihapus!, silahkan cek kembali apakah data yang anda pilih sudah digunakan pada data yang lain seperti pada data mahasiswa.";
            } 
        }else {
            $records["customActionStatus"] = "warning";
            $records["customActionMessage"] = "Maaf, anda tidak memperoleh akses untuk aksi ini!";
        }

        echo json_encode($records);
	}

	private function DoUploadDokumen($file, $filename)
	{
		
		$fileNames = 'draft-'.get_user_name().'-'.$filename;
		$config = array(
			'upload_path' => $this->config->item('upload_path').'dokumen/draft',
			'allowed_types' => 'pdf',
			'max_size' => $this->config->item('file_max_size'),
			'overwrite' => TRUE,
			'file_name' => $fileNames
		);
		// dd($file);
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if ($this->upload->do_upload($file)) {
			return $this->upload->data('file_name');
		} else {
			return false;
		}
	}
}


/* End of file JenisKlasifikasi.php */
/* Location: D:\laragon\www\bsi-devel\uad\e-office-uad\application\modules\master\controllers\JenisKlasifikasi.php */