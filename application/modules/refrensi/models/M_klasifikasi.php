<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//tes
class M_klasifikasi extends CI_Model
{
    function get_data( $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL ){
		
		$this->db->join('klasifikasi_jenis_ref','jnsklasId=klasJenis');
		
		if(!is_null($object)) {
			foreach($object as $row => $val)
			{
				if(preg_match("/(<=|>=|=|<|>|!=)(\s*)(.+)/i", trim($val), $matches)){
					$this->db->where($row . ' ' . $matches[1], $matches[3]);
				} elseif ($row == 'filter_key') {
				  $where = "(jnsklasNama LIKE '%" . $val . "%' AND klasKode '%" . $val . "%' AND klasNama '%" . $val . "%' )";
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
			$query = $this->db->get( 'klasifikasi' );
			if ( $query->num_rows() > 0 ) return $query;
			return NULL;
		} else if($status == 'counter'){
			return $this->db->count_all_results('klasifikasi');
		}
	}



    function get_jenis_klasifikasi($params = array()){


        if(!empty($params['filter'])){
            $this->db->where($params['filter']);
        }


        $query = $this->db->get('klasifikasi_jenis_ref');

        return $query->result();

    }
    


}
