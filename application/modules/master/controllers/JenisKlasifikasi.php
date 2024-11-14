<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class JenisKlasifikasi extends Dashboard_Controller {

	private $path = 'master';
    private $mod = 'JenisKlasifikasi';

	function __construct() 
	{
		parent::__construct();
		
		
		$this->load->model($this->path.'/m_jenis_klasisifikasi');
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
        $data['moduleAdd']= $this->module.'/add';

        $this->template->title('Jenis Klasifikasi');
		$this->template->set_breadcrumb('Jenis Klasisifikasi', $this->module);
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
            1 => 'jnsklasKode',
            2 => 'jnsklasNama',
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
        $qry = $this->m_jenis_klasisifikasi->get_data($object, $length, $this->input->post('start'), $order, NULL);
        $iTotalRecords = (!is_null($qry)) ? intval($this->m_jenis_klasisifikasi->get_data($object, NULL, NULL, array(), 'counter')) : 0;
        $iDisplayStart = intval($this->input->post('start'));
        $sEcho = intval($this->input->post('draw'));
        $records = array();
        $records["data"] = array(); 
        if(!is_null($qry)){
            foreach($qry->result_array() as $row){
                $records["data"][] = array(
                    '<input type="checkbox" class="data-id" name="data_id[]" value="'. encode($row['jnsklasId']) .'">',
                    $row['jnsklasKode'],
                    $row['jnsklasNama'],
                    '<a data-provide="tooltip" data-original-title="Edit" href="#" id="edit-btn" data-id="'.encode($row['jnsklasId']).'" data-href="'. site_url( $this->module.'/update/'. encode($row['jnsklasId']) ) .'" class="btn btn-square btn-round btn-warning" ><i class="fa fa-pencil"></i></a>',
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
				$this->form_validation->set_rules('jnsklasKode', 'Kode Jenis Klasifikasi', 'required');
				$this->form_validation->set_rules('jnsklasNama', 'Nama Jenis Klasisifikasi', 'required');
				$this->form_validation->set_error_delimiters('', '');
				if ($this->form_validation->run()) {
					$params['data']  = array(	
						'jnsklasKode'	=> $this->input->post('jnsklasKode'),
						'jnsklasNama'	=> $this->input->post('jnsklasNama'),
					);

					$params['tables'] = ' klasifikasi_jenis_ref';

					$proses = $this->m_form->insert_form($params);
					if($proses) {
						$result = array('error' => 'null', 'status' => true, 'type' => 'success', 'text' => 'Data berhasil ditambahkan.');
					} else {
						$result = array('error' => 'null', 'status' => false, 'type' => 'error', 'text' => 'Data gagal ditambahkan.');
						// $this->session->set_flashdata('msg', array('status' => 'danger', 'title' => 'Peringatan', 'message' => 'Data gagal disimpan.'));
					}

				}else{
					$error = array(
						'jnsklasKode' => form_error('jnsklasKode'),
						'jnsklasNama' => form_error('jnsklasNama'),
					);
					$result = array('error' => $error);
				}

				echo json_encode($result);
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

				$filter = array('jnsklasId' => decode($encId));
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
			if(is_null( $get_data = $this->m_jenis_klasisifikasi->get_data(array('jnsklasId' => ' = '. decode($encId) ), NULL, NULL, NULL, NULL) )) show_404();
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
                $params['data'] = array('jnsklasId' => decode($val)) ;
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
}


/* End of file JenisKlasifikasi.php */
/* Location: D:\laragon\www\bsi-devel\uad\e-office-uad\application\modules\master\controllers\JenisKlasifikasi.php */