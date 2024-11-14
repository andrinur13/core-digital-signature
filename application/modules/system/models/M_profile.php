<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_profile extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

    function update_user($params, $id) {
    $this->db->trans_begin();

        if ($params['password'] != '') {
            $dt_user['UserPassword']   = $params['password'];
            $dt_user['UserSalt']       = $params['salt'];
        }
        $this->db->where('UserId', $id);
        $this->db->update('sys_user',$dt_user);

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


}