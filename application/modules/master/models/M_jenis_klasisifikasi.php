<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_jenis_klasisifikasi extends CI_Model
{
    function get_data( $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL ){


		if(!is_null($object)) {
			foreach($object as $row => $val)
			{
				if(preg_match("/(<=|>=|=|<|>|!=)(\s*)(.+)/i", trim($val), $matches)){
					$this->db->where($row . ' ' . $matches[1], $matches[3]);
				} elseif ($row == 'filter_key') {
				  $where = "(jnsklasKode LIKE '%" . $val . "%' OR jnsklasNama LIKE '%" . $val . "%'  )";
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
			$query = $this->db->get( 'klasifikasi_jenis_ref' );
			if ( $query->num_rows() > 0 ) return $query;
			return NULL;
		} else if($status == 'counter'){
			return $this->db->count_all_results('klasifikasi_jenis_ref');
		}
	}



}
