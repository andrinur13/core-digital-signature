<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_module extends CI_Model {

   public function __construct()
   {
      parent::__construct();
      //Do your magic here
   }

   /*function list_menu_module($object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL)
   {
      $this->db->select('MenuId, MenuParentId, MenuName, MenuDescription, MenuModule, MenuIsShow, MenuIconClass, MenuOrder');
      $this->db->select('GROUP_CONCAT(MenuActionName)  AS action_name, GROUP_CONCAT(MenuActionFunction) AS action_function');
      
      $this->db->join('core_menu_action', 'MenuActionMenuId = MenuId');

      if(!is_null($object)) {
         foreach($object as $row => $val)
         {
            if(preg_match("/(<=|>=|=|<|>)(\s*)(.+)/i", trim($val), $matches))
               $this->db->where( $row .' '.$matches[1], $matches[3]);
            else
               $this->db->where( $row .' LIKE', '%'.$val.'%');
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
      $this->db->group_by('MenuId');
      if(is_null($status)){
         $query = $this->db->get( 'core_group' );
         if ( $query->num_rows() > 0 ) return $query;
         return NULL;
      } else if($status == 'counter'){
         return $this->db->count_all_results('core_group');
      }
   }*/

   function list_menu_module()
   {
      $sql = "
         SELECT MenuId, MenuParentId, menu_name, MenuName, MenuDescription, MenuModule, MenuIsShow, MenuIconClass, MenuOrder
         , GROUP_CONCAT(MenuActionName ORDER BY MenuActionId ASC) AS action_name, GROUP_CONCAT(MenuActionFunction ORDER BY MenuActionId ASC) AS action_function
         FROM sys_menu
         LEFT JOIN (
            SELECT MenuId AS menu_id, MenuName AS menu_name
            FROM sys_menu 
            WHERE MenuParentId IS NULL OR MenuParentId = ''
         ) AS tbl ON tbl.menu_id = MenuParentId
         LEFT JOIN sys_menu_action ON MenuActionMenuId = MenuId
         GROUP BY `MenuId`
         ORDER BY MenuIsShow, MenuId, MenuOrder
      ";
      $query = $this->db->query($sql, array());
      return $query->result_array();
   }

   function list_parent_menu()
   {
      $this->db->select('MenuId AS id, MenuName AS name');
      $this->db->where('MenuParentId', ' ');
      $this->db->or_where('MenuParentId IS NULL', '', FALSE);
      $query = $this->db->get('sys_menu');
      
      return $query->result_array();
   }

   function insert_menu_module($params)
   {
      $this->db->trans_begin();
      $qry = $this->db->get_where('sys_menu',  array('MenuName' => $params['menu']));
      if ($qry->num_rows() < 1) {
         $data = array(
            'MenuName' => $params['menu'],
            'MenuParentId' => $params['parent_menu'],
            'MenuDescription' => $params['desc'],
            'MenuModule'   => $params['module'],
            'MenuIsShow'   => $params['is_show'],
            'MenuIconClass'=> $params['icon'],
            'MenuOrder'    => $params['order']
         );

         $this->db->insert('sys_menu', $data);

         $menu_id  = $this->db->insert_id();
         if (is_null($params['parent_menu'])) {
            $default = array(
               'MenuActionMenuId'   => $menu_id,
               'MenuActionName'     => 'View',
            );
            $this->db->insert('sys_menu_action', $default);
         }

         
         if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
         } else {
            $this->db->trans_commit();
            return TRUE;
         }
      } else {
         return FALSE;
      }
   }

   function get_detail_menu($id)
   {
      $this->db->select('MenuId AS id, MenuName AS name, MenuParentId AS parent_id, MenuModule AS module, MenuDescription AS desc, MenuIsShow AS is_show, MenuIconClass AS icon, MenuOrder AS order');
      $this->db->where('MenuId', $id);
      $query = $this->db->get('sys_menu');
      return $query->row_array();
   }

   function update_menu_module($params)
   {
      $data = array(
         'MenuName' => $params['menu'],
         'MenuParentId' => $params['parent_menu'],
         'MenuDescription' => $params['desc'],
         'MenuModule'   => $params['module'],
         'MenuIsShow'   => $params['is_show'],
         'MenuIconClass'=> $params['icon'],
         'MenuOrder'    => $params['order']
      );

      $this->db->where('MenuId', $params['id']);
      $rs = $this->db->update('sys_menu', $data);
      
      return ($this->db->affected_rows() > 0);
   }

   function list_method_module($object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL)
   {
      $this->db->select('MenuActionId, MenuActionName, MenuActionFunction, MenuActionSegmen');
      if(!is_null($object)) {
         foreach($object as $row => $val)
         {
            if(preg_match("/(<=|>=|=|<|>)(\s*)(.+)/i", trim($val), $matches))
               $this->db->where( $row .' '.$matches[1], $matches[3]);
            else
               $this->db->where( $row .' LIKE', '%'.$val.'%');
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
         $query = $this->db->get( 'sys_menu_action' );
         if ( $query->num_rows() > 0 ) return $query;
         return NULL;
      } else if($status == 'counter'){
         return $this->db->count_all_results('sys_menu_action');
      }
   }

   function list_method($menu_id)
   {
      $this->db->select('MenuActionId AS id, MenuActionName AS name, MenuActionFunction AS method, MenuActionSegmen as segmen');
      $this->db->where('MenuActionMenuId', $menu_id);
      $query = $this->db->get('sys_menu_action');

      return ($query->num_rows() > 0) ? $query->result_array() : NULL ;
   }

   function detail_method($id)
   {
      $this->db->select('MenuActionId AS id, MenuActionMenuId AS menu_id, MenuActionName AS name, MenuActionFunction AS method, MenuActionSegmen as segmen');
      $this->db->where('MenuActionId', $id);
      $query = $this->db->get('sys_menu_action');

      return ($query->num_rows() > 0) ? $query->row_array() : NULL ;
   }

   function insert_method($params)
   {
       $data = array(
         'MenuActionMenuId'   => $params['id'],
         'MenuActionName'     => $params['name'],
         'MenuActionFunction' => $params['method'],
         'MenuActionSegmen'   => $params['segmen']
      );
      $this->db->insert('sys_menu_action', $data);

      return ($this->db->affected_rows() > 0);
   }

   function update_method($id, $params) 
   {
      $data = array(
         'MenuActionName'     => $params['name'],
         'MenuActionFunction' => $params['method'],
         'MenuActionSegmen'   => $params['segmen']
      );
      $this->db->where('MenuActionId', $id);
      $this->db->update('sys_menu_action', $data);

      return ($this->db->affected_rows() > 0);  
   }

   function delete_method($id)
   {
      $this->db->where('MenuActionId', $id);
      return $this->db->delete('sys_menu_action');
   }

   function check_menu_exist($parent_id, $menu) 
   {
      $this->db->select('MenuId AS id,MenuName AS name');
      $this->db->where(array('MenuParentId' => $parent_id, 'MenuName' => $menu));
      $query = $this->db->get('sys_menu');
      
      return $query->num_rows();
   }

   function check_method_exist($id, $segmen) 
   {
      $this->db->select('MenuActionId AS id');
      $this->db->where(array('MenuActionMenuId' => $id, 'MenuActionSegmen' => $segmen));
      $query = $this->db->get('sys_menu_action');

      return $query->num_rows();
   }
   

}

/* End of file M_module.php */
/* Location: ./application/modules/system/models/M_module.php */