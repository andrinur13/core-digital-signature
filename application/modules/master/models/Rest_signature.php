<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rest_signature extends CI_Model {

    private $service_url;
    private $service_key;
 
    private $is_dev_url = 'sandbox'; // Set URL API => sandbox : production
    function __construct() {
        parent::__construct();
        $this->service_key = 'aa3aefd64ffa2be01503c2a2b202056aedf5ad6e';
    }



    function set_service_url($is_dev = 'sandbox'){
        if($is_dev == 'sandbox'){
            $this->service_url = 'https://stoplight.io/mocks/airslate/signnow/18723392/document';
        }elseif($is_dev == 'production'){
            $this->service_url = 'https://api.uad.ac.id/index.php?d=akademik';
        }else{
            $this->service_url = '';
        }
  
        return $this->service_url;
    }


    function GetDataTables($jenisData = '',$kode_unit ='', $object = array(), $limit = NULL, $offset = NULL, $order = array()){
        $params = array(
            'jenis_data' => $jenisData,
            'kode_unit' => $kode_unit,
            'object' => json_encode($object),
            'limit' => $limit,
            'offset' => $offset,
            'order' => json_encode($order)
        );
        // dd($params);
        $buffer = curl_api(array('key' => $this->service_key, 'url' => $this->set_service_url($this->is_dev_url).'&c=akademik&m=get_data_tables', 'post' => $params));
        // dd($buffer);
        if (!empty($buffer)) return json_decode($buffer,true);
        return NULL;
    }


    function BatalCuti($params){
        $buffer = curl_api(array('key' => $this->service_key, 'url' => $this->set_service_url($this->is_dev_url).'&c=akademik&m=batal_cuti', 'post' => $params));
        // dd($buffer);
        if (!empty($buffer)) return json_decode($buffer,true);
        return NULL;
    }
    
    function UpdateForm($params){
        $buffer = curl_api(array('key' => $this->service_key, 'url' => $this->set_service_url($this->is_dev_url).'&c=akademik&m=update_form', 'post' => $params));
        
        if (!empty($buffer)) return json_decode($buffer,true);
        return NULL;
    }
    
    
    function GetPembayaranCuti($params){
        $buffer = curl_api(array('key' => $this->service_key, 'url' => $this->set_service_url($this->is_dev_url).'&c=akademik&m=cek_bayar_cuti', 'post' => $params));
        // dd($buffer);
        if (!empty($buffer)) return json_decode($buffer,true);
        return NULL;
    }
    
    
    function GetRiwayatCuti($params){
        $buffer = curl_api(array('key' => $this->service_key, 'url' => $this->set_service_url($this->is_dev_url).'&c=akademik&m=riwayat_cuti', 'post' => $params));
        // dd($buffer);
        if (!empty($buffer)) return json_decode($buffer,true);
        return NULL;
    }
    
    function GetDetailCuti($params){
        $buffer = curl_api(array('key' => $this->service_key, 'url' => $this->set_service_url($this->is_dev_url).'&c=akademik&m=detail_cuti', 'post' => $params));
        // dd($buffer);
        if (!empty($buffer)) return json_decode($buffer,true);
        return NULL;
    }
   
    function ProsesCutiMahasiswa($params){
        $buffer = curl_api(array('key' => $this->service_key, 'url' => $this->set_service_url($this->is_dev_url).'&c=akademik&m=proses_cuti', 'post' => $params));
        // dd($buffer);
        if (!empty($buffer)) return json_decode($buffer,true);
        return NULL;
    }
    
    
    function GetDetailMahasiswa($params){
        $buffer = curl_api(array('key' => $this->service_key, 'url' => $this->set_service_url($this->is_dev_url).'&c=akademik&m=detail_mahasiswa', 'post' => $params));
        // dd($buffer);
        if (!empty($buffer)) return json_decode($buffer,true);
        return NULL;
    }

    function GetSebabCuti($params){
        $buffer = curl_api(array('key' => $this->service_key, 'url' => $this->set_service_url($this->is_dev_url).'&c=akademik&m=get_sebab_cuti', 'post' => $params));
        // dd($buffer);
        if (!empty($buffer)) return json_decode($buffer,true);
        return NULL;
    }
    
    function GetSemester($params){
        $buffer = curl_api(array('key' => $this->service_key, 'url' => $this->set_service_url($this->is_dev_url).'&c=akademik&m=get_semester', 'post' => $params));
        // dd($params);
        if (!empty($buffer)) return json_decode($buffer,true);
        return NULL;
    }
    
    function InsertFormCuti($params){
        $buffer = curl_api(array('key' => $this->service_key, 'url' => $this->set_service_url($this->is_dev_url).'&c=akademik&m=insert_form_cuti', 'post' => $params));
        // dd($buffer);
        if (!empty($buffer)) return json_decode($buffer,true);
        return NULL;
    }
    function KirimNotifikasi($params){
        $buffer = curl_api(array('key' => $this->service_key, 'url' => $this->set_service_url($this->is_dev_url).'&c=akademik&m=kirim_notifikasi', 'post' => $params));
        // dd($buffer);
        if (!empty($buffer)) return json_decode($buffer,true);
        return NULL;
    }
    
    
    function GetTugasAkhir($params){
        $buffer = curl_api(array('key' => $this->service_key, 'url' => $this->set_service_url($this->is_dev_url).'&c=akademik&m=get_tugas_akhir', 'post' => $params));
        // dd($buffer);
        if (!empty($buffer)) return json_decode($buffer,true);
        return NULL;
    }

    function GetKRS($params){
        $buffer = curl_api(array('key' => $this->service_key, 'url' => $this->set_service_url($this->is_dev_url).'&c=akademik&m=get_krs', 'post' => $params));
        // dd($buffer);
        if (!empty($buffer)) return json_decode($buffer,true);
        return NULL;
    }



}