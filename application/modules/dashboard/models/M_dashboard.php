<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_dashboard extends CI_Model
{
	function __construct()
	{
		parent::__construct();

		$ci =& get_instance();
	}



	function get_jumlah_unit(){
		$query = $this->db->get('sys_unit');


		return $query->num_rows();
		
	}
	
	
	function get_jumlah_user(){

		$this->db->where('UserId !=', get_user_id());

		$query = $this->db->get('sys_user');


		return $query->num_rows();
		
	}
}
