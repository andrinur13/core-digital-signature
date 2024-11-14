<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_user extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function get_user( $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL ){
		$this->db->select('sys_user.*, IF(UserBanned=1, "Tidak", "Aktif") AS BannedText, sys_unit.*, sys_role.*' );
		$this->db->select('GROUP_CONCAT(sys_group.GroupName) AS NamaGroup', FALSE);
		$this->db->join('sys_user_group','UserGroupUserId=UserId','LEFT');
		$this->db->join('sys_group','UserGroupGroupId=GroupId','LEFT');
		$this->db->join('sys_role','UserRoleId=roleId','LEFT');
		$this->db->join('sys_unit','UnitId=UserUnitId','LEFT');
		$this->db->group_by('sys_user.UserName');

		if(!is_null($object)) {
			foreach($object as $row => $val)
			{
				if(preg_match("/(<=|>=|=|<|>|!=)(\s*)(.+)/i", trim($val), $matches))
					$this->db->where( $row .' '.$matches[1], $matches[3]);
				else
					$this->db->or_where( $row .' LIKE', '%'.$val.'%');
			}
		}
		//$this->db->where_not_in('UserGroupGroupId', array(0,1));

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
			$query = $this->db->get('sys_user');
			if ( $query->num_rows() > 0 ) return $query;
			return NULL;
		} else if($status == 'counter'){
			return $this->db->count_all_results('sys_user');
			// return $query;
		}
	}

	function get_ref_group(){
		# $this->db->select('MenuActionId,MenuId,MenuParentId,MenuName,MenuActionName');
		$query = $this->db->select('GroupId,GroupName')->where('GroupId !=','0')->from('sys_group')->get();
		if ( $query->num_rows() > 0 ) return $query;
		return NULL;
	}

   function get_ref_role(){
		# $this->db->select('MenuActionId,MenuId,MenuParentId,MenuName,MenuActionName');
		$query = $this->db->select('roleId,roleNama')->from('sys_role')->get();
		if ( $query->num_rows() > 0 ) return $query;
		return NULL;
	}

	function get_ref_organisasi(){
		$query = $this->db->select('orgId,orgNama')->from('organisasi')->get();
		if ( $query->num_rows() > 0 ) return $query;
		return NULL;
	}

	function get_user_menu( $object = array(), $order = array() ){
		# $this->db->select('MenuActionId,MenuId,MenuParentId,MenuName,MenuActionName');
		if(!is_null($object)) {
			$this->db->where( $object );
		}
		$this->db->join('sys_menu', ' MenuActionMenuId=MenuId', 'LEFT');
		$this->db->join('sys_user_detail', ' MenuActionId=userDetailMenuActionId', 'LEFT');
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

	function get_group_by_user_id($user_id){
		$query = $this->db->select('GroupId, GroupName, UserGroupIsDefault')
						->from('sys_group')
						->join('sys_user_group','UserGroupGroupId=GroupId')
						->where('UserGroupUserId',$user_id)
						->get();
	if ( $query->num_rows() > 0 ) return $query;
			return NULL;
	}

   function input_user($params) {
      $this->db->trans_begin();

      $dt_user  = array(
         'UserRealName'    => $params['nama'],
         'UserName'        => $params['username'],
         'UserEmail'       => $params['email'],
         'UserUnitId'       => $params['unit_id'],
         'UserPassword'    => $params['password'],
         'UserSalt'        => $params['salt'],
         'UserRoleId'        => $params['role'],
         'UserIsActive'    => $params['is_active'],
         'UserAddTime'     => $params['time']
      );

      $this->db->insert('sys_user',$dt_user);
      $user_id = $this->db->insert_id();

      // insert user rumah sakit
     
      foreach($params['group'] as $grp) {
         $is_default = ($params['is_default'] == $grp) ? '1' : '0';
         $dt_grp = array( 
                     'UserGroupUserId'    => $user_id ,
                     'UserGroupGroupId'   => $grp,
                     'UserGroupIsDefault' => $is_default
                  );
         $this->db->insert('sys_user_group', $dt_grp);
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

   function update_user($params, $id) {
      $this->db->trans_begin();

      $dt_user  = array(
         'UserRealName'    => $params['nama'],
         'UserEmail'       => $params['email'],
         'UserUnitId'       => $params['unit_id'],
         'UserIsActive'    => $params['is_active'],
         'UserRoleId'        => $params['role'],
         'UserUpdateTime'  => $params['time_update'],
         'UserUpdateUser'  => $params['user_update']
      );
      if ($params['password'] != '') {
         $dt_user['UserPassword']   = $params['password'];
         $dt_user['UserSalt']       = $params['salt'];
      }
      $this->db->where('UserId', $id);
      $this->db->update('sys_user',$dt_user);
      


      $this->db->delete('sys_user_group', array('UserGroupUserId' => $id));

      foreach($params['group'] as $grp) {
         $is_default = ($params['is_default'] == $grp) ? '1' : '0';
         $dt_grp = array( 
                     'UserGroupUserId'    => $id ,
                     'UserGroupGroupId'   => $grp,
                     'UserGroupIsDefault' => $is_default
                  );
         $this->db->insert('sys_user_group', $dt_grp);
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

	function delete_user($user_id) {
      $this->db->trans_begin();

      $this->db->delete('sys_user_group', array('UserGroupUserId' => $user_id));
		$this->db->delete('sys_user', array('UserId' => $user_id));

      $qry = $this->db->where('UserId', $user_id)->get_compiled_delete('sys_user');
      $this->db->insert('sys_log', array('LogUserName' => get_user_name(), 'LogIpAddress' => $this->input->ip_address(), 'LogActivities' => $qry));

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