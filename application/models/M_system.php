<?php
class M_system extends CI_Model {
	
	function __construct()
	{
		parent::__construct();

		$ci =& get_instance();
	}
	
	function insert_log($activity){
		$this->db->insert($this->config->item('app_db_table_prefix').'log', array('LogUserId' => $ip_address, 'LogIpAddress' => $this->input->ip_address(), 'LogActivities' => $activity));
	}
}
