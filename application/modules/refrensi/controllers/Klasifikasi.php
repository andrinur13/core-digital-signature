<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Klasifikasi extends Dashboard_Controller {

	private $path = 'refrensi';
	private $master = 'master';
    private $mod = 'Klasifikasi';



	function __construct() 
	{
		parent::__construct();
		
		
		$this->load->model($this->path.'/m_klasifikasi');
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
        $data['jenisKlasifikasi']= $this->m_klasifikasi->get_jenis_klasifikasi();

        $this->template->title('Klasifikasi');
		$this->template->set_breadcrumb('Klasifikasi', $this->module);
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
            1 => 'klasJenis',
            2 => 'klasKode',
            3 => 'klasNama',
        );
        
        $object = array();

		// filter data
        $f_jenis = $this->input->post('f_jenis');
        if ($f_jenis != '') {
            $object['klasJenis'] = '='.decode($f_jenis);
        }


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
        $qry = $this->m_klasifikasi->get_data($object, $length, $this->input->post('start'), $order, NULL);
        $iTotalRecords = (!is_null($qry)) ? intval($this->m_klasifikasi->get_data($object, NULL, NULL, array(), 'counter')) : 0;
        $iDisplayStart = intval($this->input->post('start'));
        $sEcho = intval($this->input->post('draw'));
        $records = array();
        $records["data"] = array(); 
        if(!is_null($qry)){
            foreach($qry->result_array() as $row){

				$roleKolom = restrict($this->module.'/set_kolom', true);
				$btn_edit = '<a data-provide="tooltip" data-original-title="Edit" href="#" id="edit-btn" data-id="'.encode($row['klasId']).'" data-href="'. site_url( $this->module.'/update/'. encode($row['klasId']) ) .'" class="btn btn-square btn-round btn-warning" ><i class="fa fa-pencil"></i></a>';
				$btn_set_kolom = ($roleKolom) ? '<a data-provide="tooltip" data-original-title="Seting Kolom Jenis Surat" href="#" id="set-btn" data-id="'.encode($row['klasId']).'" data-href="'. site_url( $this->module.'/update/'. encode($row['klasId']) ) .'" class="btn btn-square btn-round btn-primary" ><i class="fa fa-external-link"></i></a>' : '';
                $records["data"][] = array(
                    '<input type="checkbox" class="data-id" name="data_id[]" value="'. encode($row['klasId']) .'">',
                    $row['jnsklasNama'].'-'.$row['jnsklasKode'],
                    $row['klasKode'],
                    $row['klasNama'],
                    ($row['klasIsAktif'] == 1) ? 'Aktif' : 'Tidak Aktif',
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
				$this->form_validation->set_rules('klasJenis', 'Jenis Klasifikasi', 'required');
				$this->form_validation->set_rules('klasKode', 'Kode Klasifikasi', 'required');
				$this->form_validation->set_rules('klasNama', 'Nama Klasifikasi', 'required');
				$this->form_validation->set_error_delimiters('', '');
				if ($this->form_validation->run()) {
					
					$params['data']  = array(	
						'klasJenis'	=> decode($this->input->post('klasJenis')),
						'klasKode'	=> $this->input->post('klasKode'),
						'klasNama'	=> $this->input->post('klasNama'),
						'klasIsAktif'	=> ($this->input->post('klasIsAktif') == 'on') ? 1 : 0,
						'klasUserCreate' => get_user_name(),
						'klasTglCreate' => date('Y-m-d H:i:s')
					);

					$params['tables'] = 'klasifikasi';

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
						'klasJenis' => form_error('klasJenis'),
						'klasKode' => form_error('klasKode'),
						'klasNama' => form_error('klasNama'),
					);
					$result = array('error' => $error);
				}

				echo json_encode($result);
    }

	public function update($encId = NULL){
		if(is_null( $encId )) redirect($this->module);
		if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
		if ($this->input->post('action') == 'submit') {

			$this->form_validation->set_rules('klasJenis', 'Jenis Klasifikasi', 'required');
			$this->form_validation->set_rules('klasKode', 'Kode Klasifikasi', 'required');
			$this->form_validation->set_rules('klasNama', 'Nama Klasifikasi', 'required');
			$this->form_validation->set_error_delimiters('', '');
			if ($this->form_validation->run()) {
				$params['data']  = array(	
					'klasJenis'	=> decode($this->input->post('klasJenis')),
					'klasKode'	=> $this->input->post('klasKode'),
					'klasNama'	=> $this->input->post('klasNama'),
					'klasIsAktif'	=> ($this->input->post('klasIsAktif') == 'on') ? 1 : 0,
					'klasUserCreate' => get_user_name(),
					'klasTglCreate' => date('Y-m-d H:i:s')
				);

				$params['tables'] = 'klasifikasi';

				$filter = array('klasId' => decode($encId));
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
			if(is_null( $get_data = $this->m_klasifikasi->get_data(array('klasId' => ' = '. decode($encId) ), NULL, NULL, NULL, NULL) )) show_404();
			$data['module']     = $this->module;
			$data['data']       = $get_data->row();
			$data['jenisKlasifikasi']= $this->m_klasifikasi->get_jenis_klasifikasi();

			// dd($data);

			$this->load->view(strtolower($this->module).'/v_update', $data);
		}
	}

	public function delete($data) {
		$restrict = restrict( $this->module.'/delete', TRUE);
        $action = TRUE;

        $params['tables'] = 'klasifikasi';

        
        if($restrict == TRUE){
            foreach($data as $val){
                $params['data'] = array('klasId' => decode($val)) ;
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


/* End of file Klasifikasi.php */
/* Location: D:\laragon\www\bsi-devel\uad\e-office-uad\application\modules\refrensi\controllers\Klasifikasi.php */