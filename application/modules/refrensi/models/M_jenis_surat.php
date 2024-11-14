<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//tes

class M_jenis_surat extends CI_Model
{
    function get_data( $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL ){
		if(!is_null($object)) {
			foreach($object as $row => $val)
			{
				if(preg_match("/(<=|>=|=|<|>|!=)(\s*)(.+)/i", trim($val), $matches)){
					$this->db->where($row . ' ' . $matches[1], $matches[3]);
				} elseif ($row == 'filter_key') {
				  $where = "(jnsrtKode LIKE '%" . $val . "%' AND jnsrtNama '%" . $val . "%' )";
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
			$query = $this->db->get( 'jenis_surat' );
			if ( $query->num_rows() > 0 ) return $query;
			return NULL;
		} else if($status == 'counter'){
			return $this->db->count_all_results('jenis_surat');
		}
	}



    function get_kolom_ref($params = array()){


        if(!empty($params['filter'])){
            $this->db->where($params['filter']);
        }


        $query = $this->db->get('kolom_ref');

        return $query->result();

    }
    
    
    function get_jenis_kolom($params = array(), $where_in = array()){


        if(!empty($params['filter'])){
            $this->db->where($params['filter']);
        }

        if(!empty($where_in['filter'])){
            $this->db->where_in($where_in['filter'], $where_in['data']);
        }

        $query = $this->db->get('jenis_surat_kolom');

        return $query->result();

    }

	function get_jenis_surat_kolom($params) {
		
		//
		$this->db->where('jnsurkolJenisSuratId', $params['jnsurkolJenisSuratId']);


		$query = $this->db->get('jenis_surat_kolom');


		return $query->r_array();

	}


	function get_surat_kolom($params) {
		
		
		$this->db->where('surkolJnsSuratKolomId', $params['jnsurkolId']);


		$query = $this->db->get('surat_kolom');


		return $query->row_array();

	}


}
