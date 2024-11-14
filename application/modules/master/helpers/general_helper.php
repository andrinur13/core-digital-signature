<?php

class GeneralHelper {

    private $is_dev_url = true; // setting jika mode get data atau sync mode Development

    private $ci;

    public function __construct()
    {      
             $this->ci=& get_instance();
    }

    function CI()
    {
        static $CI;
        isset($CI) || $CI = CI_Controller::get_instance();

        return $CI;
    }
    

    function module($path,$mod){
        if(!empty($path) && $mod != null){
            $setting = $path.'/'.$mod;
        }else{
            $setting = $path;
        }
        return $setting;
    }


    // convert jenis semester dan tahun semester

    function convert_sem_id($tahun = '',$jenis = ''){
        if(!empty($tahun) && $jenis != 0 || $jenis != null){
            $semId = $tahun.$jenis;
        }else{
            $semId = date('Y').'1';
        }

        return $semId;
    }



    function check_count($params, $filter = ''){

        if(empty($filter)){
            $this->ci->db->select('*');
            
        }else{
            $this->ci->db->where($filter['data']);
        }

        $query = $this->ci->db->get($params['tables']);
        
        return $query->num_rows();

    }


    function periode_aktif(){
        $this->ci->db->select('semId as periode_id, semTahun as tahun, semJenis as jenis, semIsAktif as status');
        $this->ci->db->where('semIsAktif',1);

        $query = $this->ci->db->get('semester');

        return $query->row_array();
    }


    function ganjil_or_genap($params){
        if($params == 1){
            $status = 'Ganjil';
        }elseif($params == 2){
            $status = 'Genap';
        }else{
            $status = 'Tidak Terdeteksi Ganjil maupun Genap';
        }


        return $status;
    }

    function onOffStatus($params){
        if($params == 0){
            $status = '<span class="badge badge-danger">Non Aktif</span>';
        }elseif($params == 1){
            $status = '<span class="badge badge-success">Aktif</span>';
        }else{
            $status = 'Tidak Terdeteksi';
        }


        return $status;
    }
    
    function YesOrNo($params){
        if($params == 0){
            $status = '<span class="badge badge-danger">Tidak</span>';
        }elseif($params == 1){
            $status = '<span class="badge badge-success">Ya</span>';
        }else{
            $status = 'Tidak Terdeteksi';
        }


        return $status;
    }
    
}


