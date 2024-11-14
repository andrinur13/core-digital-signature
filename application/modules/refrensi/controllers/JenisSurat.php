<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class JenisSurat extends Admin_Controller {

	private $path = 'refrensi';
	private $master = 'master';
    private $mod = 'JenisSurat';


	function __construct() 
	{
		parent::__construct();
		
		
		$this->load->model($this->path.'/m_jenis_surat');
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

        $this->template->title('Data Refrensi Jenis Surat');
		$this->template->set_breadcrumb('Jenis Surat', $this->module);
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
        }
    }

	private function datatables(){
        if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
        $columns = array(
            1 => 'jnsrtKode',
            2 => 'jnsrtNama',
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
        $qry = $this->m_jenis_surat->get_data($object, $length, $this->input->post('start'), $order, NULL);
        $iTotalRecords = (!is_null($qry)) ? intval($this->m_jenis_surat->get_data($object, NULL, NULL, array(), 'counter')) : 0;
        $iDisplayStart = intval($this->input->post('start'));
        $sEcho = intval($this->input->post('draw'));
        $records = array();
        $records["data"] = array(); 
        if(!is_null($qry)){
            foreach($qry->result_array() as $row){

				$roleKolom = restrict($this->module.'/set_kolom', true);
				$btn_edit = '<a data-provide="tooltip" data-original-title="Edit" href="#" id="edit-btn" data-id="'.encode($row['jnsrtId']).'" data-href="'. site_url( $this->module.'/update/'. encode($row['jnsrtId']) ) .'" class="btn btn-square btn-round btn-warning" ><i class="fa fa-pencil"></i></a>';
				$btn_set_kolom = ($roleKolom) ? '<a data-provide="tooltip" data-original-title="Seting Kolom Jenis Surat" href="#" id="set-btn" data-id="'.encode($row['jnsrtId']).'" data-href="'. site_url( $this->module.'/update/'. encode($row['jnsrtId']) ) .'" class="btn btn-square btn-round btn-primary" ><i class="fa fa-external-link"></i></a>' : '';
                $records["data"][] = array(
                    '<input type="checkbox" class="data-id" name="data_id[]" value="'. encode($row['jnsrtId']) .'">',
                    $row['jnsrtKode'],
                    $row['jnsrtNama'],
                    $btn_edit.'&nbsp;'.$btn_set_kolom,
                );
            }
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        echo json_encode($records);
    }

	public function add() {
        $data['module'] = $this->module;
        // Form Validation
				$this->form_validation->set_rules('jnsrtNama', 'Nama Jenis Surat', 'required');
				$this->form_validation->set_rules('jnsrtKode', 'Kode Jenis Surat', 'required');
				$this->form_validation->set_error_delimiters('', '');
				if ($this->form_validation->run() == true) {

					if ($_FILES['file_surat']['name'] != '') {
						$upload_file = $this->DoUploadDokumen('file_surat', $this->input->post('jnsrtKode') . '-' .  $_FILES['file_surat']['name']);
						if ($upload_file != false) {
							$dokumen = $upload_file;
						} else {
							$result = array('error' => 'null', 'status' => false, 'type' => 'error', 'text' => 'Upload file gagal.');
						}
					}

					// dd($_FILES['file_surat']);

					$params['data']  = array(	
						'jnsrtNama'	=> $this->input->post('jnsrtNama'),
						'jnsrtKode'	=> $this->input->post('jnsrtKode'),
						'jnsrtTemplate'	=> $dokumen,
						'jnsrtUserCreate' => get_user_name(),
						'jnsrtTglCreate' => date('Y-m-d H:i:s')
					);

					$params['tables'] = 'jenis_surat';

					$proses = $this->m_form->insert_form($params);
					if($proses) {
						$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Data berhasil ditambahkan.');
					} else {
						$result = array('error' => 'null', 'status' => false, 'type' => 'error', 'text' => 'Data gagal ditambahkan.');
						// $this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Data gagal disimpan.'));
					}

				}else{
					$error = array(
						'jnsrtNama' => form_error('jnsrtNama'),
						'jnsrtKode' => form_error('jnsrtKode'),
					);
					$result = array('error' => $error);
				}

				echo json_encode($result);
    }

	public function update($encId = NULL){
		if(is_null( $encId )) redirect($this->module);
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		if ($this->input->post('action') == 'submit') {

			$file_path = $this->config->item('upload_path').'/template_surat/';

			$this->form_validation->set_rules('jnsrtNama', 'Nama Jenis Surat', 'required');
			$this->form_validation->set_rules('jnsrtKode', 'Kode Jenis Surat', 'required');
			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run()) {
				if ($_FILES['file_surat']['name'] != '') {
					$upload_file = $this->DoUploadDokumen('file_surat', rand(0, 10000) . '-' . $this->input->post('jenis') . '-' . preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['file_surat']['name']));
					if ($upload_file != false) {
						$dokumen = $upload_file;
						if (is_file($file_path . $this->input->post('file_uploaded'))) {
							unlink($file_path . $this->input->post('file_uploaded'));
						}
					} else {
						$result = array('error' => 'null', 'status' => false, 'type' => 'error', 'text' => 'Upload file gagal.');
					}
				} else {
					$dokumen = $this->input->post('file_uploaded');
				}

				

				$params['data']  = array(	
					'jnsrtNama'	=> $this->input->post('jnsrtNama'),
					'jnsrtKode'	=> $this->input->post('jnsrtKode'),
					'jnsrtTemplate'	=> $dokumen,
					'jnsrtUserUpdate' => get_user_name(),
					'jnsrtTglUpdate' => date('Y-m-d H:i:s')
				);

				$params['tables'] = 'jenis_surat';

				$filter = array('jnsrtId' => decode($encId));
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
					'jnsrtNama' => form_error('jnsrtNama'),
					'jnsrtKode' => form_error('jnsrtKode'),
				);
				$result = array('error' => $error);
				$this->output->set_content_type('application/json')->set_output(json_encode($result));
			}
		}else{
			if(is_null( $get_data = $this->m_jenis_surat->get_data(array('jnsrtId' => ' = '. decode($encId) ), NULL, NULL, NULL, NULL) )) show_404();
			$data['module']     = $this->module;
			$data['data']       = $get_data->row();
			$data['path_file'] = $this->config->item('upload_path').'/template_surat/';

			$this->load->view(strtolower($this->module).'/v_update', $data);
		}
	}

	public function delete($data) {
		$restrict = restrict( $this->module.'/delete', TRUE);
        $action = TRUE;

        $params['tables'] = 'jenis_surat';

        
        if($restrict == TRUE){
            foreach($data as $val){
                $params['data'] = array('jnsrtId' => decode($val)) ;
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

	public function set_kolom($encId = NULL){
		if(is_null( $encId )) redirect($this->module);
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		if ($this->input->post('action') == 'submit') {

			// dd($this->input->post());

			$data = $this->input->post('jnsurkolKolomId');

			$kolom = array();

			// dd($data);

			if(!empty($data)){
				foreach($data as $dt){
					$kolom[] =  $dt;
				}

				// hapus dulu data yang ada di database baru
				$params['tables'] = ' jenis_surat_kolom';
				$params['data'] = array('jnsurkolJenisSuratId' => decode($encId)) ;

				
                $hapus = $this->m_form->delete_form($params);
				foreach($kolom as $kol){
					$params['data']  = array(	
						'jnsurkolJenisSuratId'	=> decode($encId),
						'jnsurkolKolomId'	=> $kol,
					);
				
					$proses = $this->m_form->insert_form($params);
				}

				

				if($proses) {
					$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Data berhasil disimpan.');
				} else {
					$result = array('error' => 'null', 'status' => false, 'type' => 'error', 'text' => 'Data gagal disimpan.');
					// $this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Data gagal disimpan.'));
				}
			}else{
				$params['tables'] = ' jenis_surat_kolom';
				$params['data'] = array('jnsurkolJenisSuratId' => decode($encId)) ;

				
                $hapus = $this->m_form->delete_form($params);

				if($hapus) {
					$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Data berhasil disimpan.');
				} else {
					$result = array('error' => 'null', 'status' => false, 'type' => 'error', 'text' => 'Data gagal disimpan.');
					// $this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Data gagal disimpan.'));
				}
			}


			// dd($proses);

			
			$this->output->set_content_type('application/json')->set_output(json_encode($result));
			


			// dd($missingKolomIds);

			// cek apakah di database semua datanya sesuai ?
			// $filter['filter'] = array(
			// 	'jnsurkolJenisSuratId' => decode($encId)
			// );

			// $cek =  $this->m_jenis_surat->get_jenis_kolom($filter);

			// $existingKolomIds = array_map(function($kolom) {
			// 	return $kolom['jnsurkolJenisSuratId']; // Sesuaikan 'jnsurkolKolomId' dengan nama field yang benar dari database Anda
			// }, $cek);

			// $existingKolomIds = array_map(function($kolom) {
			// dd($kolom);


			// $this->form_validation->set_rules('jnsrtNama', 'Nama Jenis Surat', 'required');
			// $this->form_validation->set_rules('jnsrtKode', 'Kode Jenis Surat', 'required');
			// $this->form_validation->set_error_delimiters('', '');
			// if ($this->form_validation->run()) {
			// 	$params['data']  = array(	
			// 		'jnsrtNama'	=> $this->input->post('jnsrtNama'),
			// 		'jnsrtKode'	=> $this->input->post('jnsrtKode'),
			// 		'jnsrtUserUpdate' => get_user_name(),
			// 		'jnsrtTglUpdate' => date('Y-m-d H:i:s')
			// 	);

			// 	$params['tables'] = 'jenis_surat';

			// 	$filter = array('jnsrtId' => decode($encId));
			// 	$proses = $this->m_form->update_form($params,$filter);
			// 	if($proses) {
			// 		$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Data berhasil disimpan.');
			// 	} else {
			// 		$result = array('error' => 'null', 'status' => false, 'type' => 'error', 'text' => 'Data gagal disimpan.');
			// 		// $this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Data gagal disimpan.'));
			// 	}
			// 	$this->output->set_content_type('application/json')->set_output(json_encode($result));
			// }else{
			// 	$error = array(
			// 		'jnsrtNama' => form_error('jnsrtNama'),
			// 		'jnsrtKode' => form_error('jnsrtKode'),
			// 	);
			// 	$result = array('error' => $error);
			// 	$this->output->set_content_type('application/json')->set_output(json_encode($result));
			// }
		}else{
			if(is_null( $get_data = $this->m_jenis_surat->get_data(array('jnsrtId' => ' = '. decode($encId) ), NULL, NULL, NULL, NULL) )) show_404();
			$data['module']     = $this->module;
			$data['data']       = $get_data->row();
			$data['kolom']		= $this->m_jenis_surat->get_kolom_ref();

			$params['filter'] = array(
				'jnsurkolJenisSuratId' => decode($encId)
			);
			$kolom_jenis		= $this->m_jenis_surat->get_jenis_kolom($params);

			$kolomjenis = array();
			foreach($kolom_jenis as $kj){
				$kolomjenis[] = $kj->jnsurkolKolomId;
			}

			$data['kolom_jenis'] = $kolomjenis;


			$this->load->view(strtolower($this->module).'/v_set_kolom', $data);
		}
	}

	private function DoUploadDokumen($file, $filename)
	{
		$config = array(
			'upload_path' => $this->config->item('upload_path').'template_surat/',
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

	function view_by_file($file_name)
	{
		if (is_null($file_name))  show_404();

		$path = $this->config->item('upload_path').'/template_surat/';

		$data['path'] = $path;
		$data['dokumen'] = $file_name;
		$this->load->view(strtolower($this->module).'/v_lihat_file', $data);
	}
}


/* End of file JenisSurat.php */
/* Location: D:\laragon\www\bsi-devel\uad\e-office-uad\application\modules\master\controllers\JenisSurat.php */