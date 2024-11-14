<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_form extends CI_Model
{


	// $this->db->trans_begin();
		
	// 	
	// 	$id = $this->db->insert_id();
		
	// 	if ($this->db->trans_status() === FALSE)
	// 	{
	// 		$this->db->trans_rollback();
	// 		return FALSE;
	// 	}
	// 	else
	// 	{
	// 		$this->db->trans_commit();

	// 		if($type == 'last_id'){
	// 			return array('status' => TRUE,'last_id' => $id);
	// 		}else{
	// 			return TRUE;
	// 		}
	// 	}
    function insert_form($params, $type = ''){
		try {
			$this->db->trans_start(FALSE);
			$this->db->insert($params['tables'], $params['data']);
			$id = $this->db->insert_id();
			$this->db->trans_complete();
	
			// documentation at
			// https://www.codeigniter.com/userguide3/database/queries.html#handling-errors
			// says; "the error() method will return an array containing its code and message"
			$db_error = $this->db->error();
			if (!empty($db_error['message'])) {
				throw new Exception('Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message']);
				return false; // unreachable retrun statement !!!
			}

			
			if($type == 'last_id'){
				return array('status' => TRUE,'last_id' => $id);
			}else{
				return TRUE;
			}
		} catch (Exception $e) {

			// this will not catch DB related errors. But it will include them, because this is more general. 
			// log_message('error: ',$e->getMessage());
			log_message('error', $e->getMessage() );
			return;
		}
	}


    function update_form($params, $filter){
		try {
			
			$this->db->update($params['tables'], $params['data'], $filter );
			

			$db_error = $this->db->error();

			// dd($db_error);
			
			if (!empty($db_error['message'])) {
				throw new Exception('Database error! Error Code [' . $db_error['code'] . '] Error: ' . $db_error['message']);
				return false; // unreachable retrun statement !!!
			}

			return TRUE;
		} catch (Exception $e) {
			log_message('error', $e->getMessage() );
			return FALSE;
		}

		// $this->db->trans_begin();
		
		// $this->db->update($params['tables'], $params['data'], $filter );
		
		// if ($this->db->trans_status() === FALSE)
		// {
		// 	$this->db->trans_rollback();
		// 	return FALSE;
		// }
		// else
		// {
		// 	$this->db->trans_commit();
		// 	return TRUE;
		// }
	}

    function delete_form($params){	
		$this->db->trans_begin();
		
		$this->db->delete($params['tables'], $params['data']); 
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
    }


	// Fungsi untuk memanggil seluruh data di tentukan
    function select_data($params = null,$result='result'){
        
        if(!empty($params['filter'])){
            $this->db->where($params['filter']);
        }

        $data = $this->db->get($params['tables']);

        if($result == 'row'){
            $response = $data->row_array();
        }elseif($result == 'count'){
            $response = $data->num_rows();
        }elseif($result == 'result'){
            $response = $data->result_array();
        }else{
            $response = $data;
        }

        return $response;
    }

}
