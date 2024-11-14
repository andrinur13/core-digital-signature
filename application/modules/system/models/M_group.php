<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_group extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function get_group( $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL ){
		if(!is_null($object)) {
			foreach($object as $row => $val)
			{
				if(preg_match("/(<=|>=|=|<|>)(\s*)(.+)/i", trim($val), $matches))
					$this->db->where( $row .' '.$matches[1], $matches[3]);
				else
					$this->db->where( $row .' LIKE', '%'.$val.'%');
			}
		}

		//$this->db->where_not_in('GroupId', array('0','1'));

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
			$query = $this->db->get( 'sys_group' );
			if ( $query->num_rows() > 0 ) return $query;
			return NULL;
		} else if($status == 'counter'){
			return $this->db->count_all_results('sys_group');
		}
	}

	function get_menu( $object = array(), $order = array() ){
		# $this->db->select('MenuActionId,MenuId,MenuParentId,MenuName,MenuActionName');
		if(!is_null($object)) {
			$this->db->where( $object );
		}
		$this->db->join('sys_menu', ' MenuActionMenuId=MenuId', 'LEFT');
		if(!empty($order)){
			foreach($order as $row => $val)
			{
				$ordered = (isset($val)) ? $val : 'ASC';
				$this->db->order_by($row, $ordered);
			}
		}
		$query = $this->db->get( 'sys_menu_action' );
		if ( $query->num_rows() > 0 ) return $query;
		return NULL;
	}

	function get_group_menu( $object = array(), $order = array() ){
		# $this->db->select('MenuActionId,MenuId,MenuParentId,MenuName,MenuActionName');
		if(!is_null($object)) {
			$this->db->where( $object );
		}
		$this->db->join('sys_menu', ' MenuActionMenuId=MenuId', 'LEFT');
		$this->db->join('sys_group_detail', ' MenuActionId=GroupDetailMenuActionId', 'LEFT');
		if(!empty($order)){
			foreach($order as $row => $val)
			{
				$ordered = (isset($val)) ? $val : 'ASC';
				$this->db->order_by($row, $ordered);
			}
		}
		$query = $this->db->get( 'sys_menu_action' );
		if ( $query->num_rows() > 0 ) return $query;
		return NULL;
	}

	function input_data_group($data){
		$this->db->trans_begin();

		$this->db->insert('sys_group', array( 'GroupName' => $data['GroupName'], 'GroupDescription' => $data['GroupDescription'], 'GroupAddUser' => $data['userId'], 'GroupAddTime' => $data['datetime']) );
		$groupId = $this->db->insert_id();
		if(!is_null($data['menu'])){
			foreach($data['menu'] as $val){
				$this->db->query("INSERT INTO sys_group_detail (GroupDetailMenuActionId,GroupDetailGroupId,GroupDetailUpdateUser,GroupDetailAddTime) VALUES ('". $val ."', '". $groupId ."', '". $data['userId'] ."', '". $data['datetime'] ."') ON DUPLICATE KEY UPDATE GroupDetailUpdateUser = '". $data['userId'] ."', GroupDetailAddTime = '". $data['datetime'] ."'");
				/* $this->db->insert('sys_group_detail', array( 'GroupDetailMenuActionId' => $val, 'GroupDetailGroupId' =>  $groupId, 'GroupDetailUpdateUser' => $data['userId'], 'GroupDetailAddTime' => $data['datetime']  ) );  */
			}
		}

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

	function update_data_group($data){
		$this->db->trans_begin();

		$this->db->update('sys_group', array( 'GroupName' => $data['GroupName'], 'GroupDescription' => $data['GroupDescription'], 'GroupUpdateUser' => $data['userId'], 'GroupUpdateTime' => $data['datetime']), array('GroupId' => $data['GroupId']) );
		if(!is_null($data['menu'])){
			$this->db->delete('sys_group_detail', array('GroupDetailGroupId' => $data['GroupId']));
			foreach($data['menu'] as $val){
				$this->db->query("INSERT INTO sys_group_detail (GroupDetailMenuActionId,GroupDetailGroupId,GroupDetailUpdateUser,GroupDetailAddTime) VALUES ('". $val ."', '". $data['GroupId'] ."', '". $data['userId'] ."', '". $data['datetime'] ."') ON DUPLICATE KEY UPDATE GroupDetailUpdateUser = '". $data['userId'] ."', GroupDetailUpdateTime = '". $data['datetime'] ."'");
			}
		}

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

	function delete_data_group($data){
		$this->db->trans_begin();

		$this->db->delete('sys_group_detail', array('GroupDetailGroupId' => $data['GroupId']));
		$this->db->delete('sys_group', $data);
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