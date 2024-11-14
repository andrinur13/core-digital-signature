<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_dokumen extends CI_Model
{
    function get_data( $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL ){


		if(!is_null($object)) {
			foreach($object as $row => $val)
			{
				if(preg_match("/(<=|>=|=|<|>|!=)(\s*)(.+)/i", trim($val), $matches)){
					$this->db->where($row . ' ' . $matches[1], $matches[3]);
				} elseif ($row == 'filter_key') {
				  $where = "(dokNoSrt LIKE '%" . $val . "%' OR dokNama LIKE '%" . $val . "%'  )";
				  $this->db->where($where);
				} else {
					$this->db->where( $row .' LIKE', '%'.$val.'%');
				}
			}
		}	
		
		if(!is_null($limit) && !is_null($offset)){
			$this->db->limit($limit, $offset );
		} 
		

		if(!empty($order)){
			foreach($order as $row => $val)
			{
				$ordered = (isset($val)) ? $val : 'ASC';
				$this->db->order_by($row, $val);
			}
		}

       
		
		if(is_null($status)){
			$query = $this->db->get( 'dokumen' );
			if ( $query->num_rows() > 0 ) return $query;
			return NULL;
		} else if($status == 'counter'){
			return $this->db->count_all_results('dokumen');
		}
	}


	function get_data_pejabat( $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL ){

		if(!is_null($object)) {
			foreach($object as $row => $val)
			{
				if(preg_match("/(<=|>=|=|<|>|!=)(\s*)(.+)/i", trim($val), $matches)){
					$this->db->where($row . ' ' . $matches[1], $matches[3]);
				} elseif ($row == 'filter_key') {
				  $where = "(pjbJabatan LIKE '%" . $val . "%' OR pjbNama LIKE '%" . $val . "%'   OR pjbKode LIKE '%" . $val . "%')";
				  $this->db->where($where);
				} else {
					$this->db->where( $row .' LIKE', '%'.$val.'%');
				}
			}
		}	
		
		if(!is_null($limit) && !is_null($offset)){
			$this->db->limit($limit, $offset );
		} 
		

		if(!empty($order)){
			foreach($order as $row => $val)
			{
				$ordered = (isset($val)) ? $val : 'ASC';
				$this->db->order_by($row, $val);
			}
		}

       
		
		if(is_null($status)){
			$query = $this->db->get( 'pejabat_ref' );
			if ( $query->num_rows() > 0 ) return $query;
			return NULL;
		} else if($status == 'counter'){
			return $this->db->count_all_results('pejabat_ref');
		}
	}

	function cek_dokumen_pemaraf($dokId){
		$this->db->where('dokprDokId',$dokId);
		$this->db->join('pejabat_ref','dokprPjbId=pjbId');
		$query = $this->db->get('dokumen_paraf');

        return $query->result();
	}
	function cek_dokumen_penandatangan($dokId){
		$this->db->where('dokttdDokId',$dokId);
		$this->db->join('pejabat_ref','dokttdPjbId=pjbId');
		$query = $this->db->get('dokumen_penandatangan');

        return $query->result();
	}

	function detail_dokumen($dokId){

		$this->db->where('dokId',$dokId);
		$query = $this->db->get('dokumen');

        return $query->row();
	}


    function get_jenis_dokumen(){
        $query = $this->db->get('ref_dokumen_jenis');

        return $query->result();
    }

	function get_pejabat(){
		$query = $this->db->get('pejabat_ref');

        return $query->result();
	}



}
