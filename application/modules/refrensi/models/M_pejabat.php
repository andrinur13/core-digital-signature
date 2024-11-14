<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//tes
class M_pejabat extends CI_Model
{
    function get_data( $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL ){


        $this->db->select('pejabat_ref.*, sys_user.UserName');
        $this->db->join('sys_user','UserId=pjbUserId','left');

		if(!is_null($object)) {
			foreach($object as $row => $val)
			{
				if(preg_match("/(<=|>=|=|<|>|!=)(\s*)(.+)/i", trim($val), $matches)){
					$this->db->where($row . ' ' . $matches[1], $matches[3]);
				} elseif ($row == 'filter_key') {
				  $where = "(pjbJabatan LIKE '%" . $val . "%' AND pjbKode '%" . $val . "%' AND pjbNama '%" . $val . "%' AND UserName '%" . $val . "%' )";
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


	function insert_form($params = array()){

		$this->db->trans_start();
		// insertkan dulu ke database user nya

		// dd($params);

		$jenis_form_user = $params['data']['jenis_form'];
		$user_id = '';
		if($jenis_form_user == 'add_user_pejabat'){
			$user = $params['data']['data_user'];

			$dt_user  = array(
				'UserRealName'    => $user['nama'],
				'UserName'        => $user['username'],
				'UserEmail'       => $user['email'],
				'UserUnitId'       => $user['unit_id'],
				'UserPassword'    => $user['password'],
				'UserSalt'        => $user['salt'],
				'UserIsActive'    => $user['is_active'],
				'UserAddTime'     => $user['time']
			);

			$this->db->insert('sys_user',$dt_user);
			$user_id = $this->db->insert_id();

			foreach($user['group'] as $grp) {
				$is_default = ($user['is_default'] == $grp) ? '1' : '0';
				$dt_grp = array( 
							'UserGroupUserId'    => $user_id ,
							'UserGroupGroupId'   => $grp,
							'UserGroupIsDefault' => $is_default
						);
				$this->db->insert('sys_user_group', $dt_grp);
			}
		}else{
			$user_id = $params['data']['data_user']['userId'];
		}
		

		// baru insertkan ke data pejabat

		$dt_pejabat = array(
			'pjbKode' => $params['data']['pjbtKode'],
			'pjbNama' => $params['data']['pjbtNama'],
			'pjbNipm' => $params['data']['pjbNipm'],
			'pjbJabatan' => $params['data']['pjbtJabatan'],
			'pjbUserId' => $user_id,
			'pjbUserCreate' => $params['data']['pjbUserCreate'],
			'pjbTglCreate' => $params['data']['pjbTglCreate'],
		);

		$this->db->insert($params['tables'],$dt_pejabat);

		
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			// Gagal melakukan transaksi
			return FALSE;
		}


		return TRUE;

	}


	function update_form($params = array(), $filter){
		$this->db->trans_start();
		// insertkan dulu ke database user nya

		// dd($params);

		$jenis_form_user = $params['data']['jenis_form'];
		$user_id = '';
		if($jenis_form_user == 'add_user_pejabat'){
			$user = $params['data']['data_user'];

			$dt_user  = array(
				'UserRealName'    => $user['nama'],
				'UserName'        => $user['username'],
				'UserEmail'       => $user['email'],
				'UserUnitId'       => $user['unit_id'],
				'UserPassword'    => $user['password'],
				'UserSalt'        => $user['salt'],
				'UserIsActive'    => $user['is_active'],
				'UserAddTime'     => $user['time']
			);

			$this->db->insert('sys_user',$dt_user);
			$user_id = $this->db->insert_id();

			foreach($user['group'] as $grp) {
				$is_default = ($user['is_default'] == $grp) ? '1' : '0';
				$dt_grp = array( 
							'UserGroupUserId'    => $user_id ,
							'UserGroupGroupId'   => $grp,
							'UserGroupIsDefault' => $is_default
						);
				$this->db->insert('sys_user_group', $dt_grp);
			}
		}else{
			$user_id = $params['data']['data_user']['userId'];
		}
		

		// baru insertkan ke data pejabat

		$dt_pejabat = array(
			'pjbKode' => $params['data']['pjbtKode'],
			'pjbNama' => $params['data']['pjbtNama'],
			'pjbJabatan' => $params['data']['pjbtJabatan'],
			'pjbNipm' => $params['data']['pjbNipm'],
			'pjbUserId' => $user_id,
			'pjbUserUpdate' => $params['data']['pjbUserUpdate'],
			'pjbTglUpdate' => $params['data']['pjbTglUpdate'],
		);

		$this->db->update($params['tables'], $dt_pejabat, $filter );

		
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			// Gagal melakukan transaksi
			return FALSE;
		}


		return TRUE;
	}
    function get_sys_user($params = array()){


        // $this->db->select('UserId as id, UserName as username');
        if(!empty($params['filter'])){
            $this->db->like('UserName', $params['filter'],'both');
        }
		
        $query = $this->db->get('sys_user');
        // dd( $query->result());
        return $query->result_array();
    }

	function cekUserByUserName($filter){


        // $this->db->select('UserId as id, UserName as username');
        $this->db->like('UserName', $filter);

        $query = $this->db->get('sys_user');
        // dd( $query->result());
        return $query->row_array();
    }
	
	function cekUserByUserId($filter){


        // $this->db->select('UserId as id, UserName as username');
        $this->db->like('UserId', $filter);

        $query = $this->db->get('sys_user');
        // dd( $query->result());
        return $query->row_array();
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


}
