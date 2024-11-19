<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

defined('BASEPATH') or exit('No direct script access allowed');

class Validasi extends Dashboard_Controller
{
    function __construct()
	{
		parent::__construct();

        $this->load->model($this->path.'/M_Validasi');
		
	}

    public function index() {
        return $this->load->view('validasi/v_index');
    }

    public function detail($id) {
        $idDecoded = decode($id);
        $ppg = $this->M_Validasi->getDataDetailWithDocumentId($idDecoded);

        $data = [
            'ppg' => $ppg,
        ];

        return $this->load->view('validasi/v_detail', $data);
    }
    
}