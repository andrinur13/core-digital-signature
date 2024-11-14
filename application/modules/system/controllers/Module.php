<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Module extends Admin_Controller {

   private $module = 'system';

   public function __construct()
   {
      parent::__construct();
      //Do your magic here
      //restrict();
      $this->load->model($this->module.'/m_module');
   }

   public function index()
   {
      protect_acct();
      $tpl['module'] = $this->module.'/Module';  

      $tpl['data'] = $this->m_module->list_menu_module();

      $menu = $this->m_module->list_menu_module();

      $data = array();
      foreach($menu as $mn) {
         if ( is_null($mn['MenuParentId']) ) {
            $data[$mn['MenuId']] = $mn;
         } else {
            $data[$mn['MenuParentId']]['sub_menu'][] = $mn;
         }
      }

      $tpl['menu'] = $data;

      $this->template->title( 'Menu Module' );
      $this->template->set_breadcrumb( 'Dashboard' , 'system/dashboard/index' );
      $this->template->set_breadcrumb( 'Menu Module' , '');

      $this->template->build($this->module.'/v_module_index', $tpl);
   }

   function add_menu()
   {
      protect_acct();
      $tpl['module'] = $this->module.'/Module';
      $tpl['parent'] = $this->m_module->list_parent_menu();

      $app_path = APPPATH.'modules/';
      $parent = $this->input->post('parent');
      $this->form_validation->set_rules('menu', 'Menu Name', 'required|callback_menu_check['.$parent.']');
      if ($this->form_validation->run() == TRUE) {
         $params = array(
            'menu'   => $this->input->post('menu'),
            'parent_menu' => $this->input->post('parent') != '' ? $this->input->post('parent') : NULL,
            'module' => $this->input->post('module') != '' ? $this->input->post('module') : NULL,
            'order'  => $this->input->post('order'),
            'desc'   => $this->input->post('description'),
            'icon'   => $this->input->post('icon'),
            'is_show'=> $this->input->post('is_show')
         );
         $gen_module = $this->input->post('generate_module');

         //$dt = $this->m_module->check_menu_exist($params['parent_menu'], $params['menu']); print_r($dt); die();

         $rs = $this->m_module->insert_menu_module($params);
         if ($rs) {
            if ($gen_module == '1' && $params['module'] != '') {
               $module = explode('/', $params['module']); 
               if (!GenerateModule($module[0], $module[1])) {
                  $this->session->set_flashdata('msg_module', array('status' => FALSE, 'type' => 'danger', 'text' => 'Generate Module dan Controller gagal, Silahkan membuatnya secara manual.'));
               }
            }
            $this->session->set_flashdata('msg_module', array('status' => TRUE, 'type' => 'success', 'text' => 'Penambahan Menu Module berhasil.'));
         } else {
            $this->session->set_flashdata('msg_module', array('status' => FALSE, 'type' => 'danger', 'text' => 'Penambahan Menu Module gagal.'));
         }
         redirect(site_url($this->module.'/Module'));
      } 

      $this->template->inject_partial('modules_css', multi_asset( array(
                                                      'vendor/select2/css/select2.min.css' => '_theme_',
                                                      ), 'css' ) );
                                                      
      $this->template->inject_partial('modules_js', multi_asset( array(
                                                'vendor/select2/js/select2.full.min.js' => '_theme_',
                                             ), 'js' ) );

      $this->template->title( 'Tambah Menu Module' );
      $this->template->set_breadcrumb( 'Dashboard' , 'system/dashboard/index' );
      $this->template->set_breadcrumb( 'Menu Module' , '');

      $this->template->build($this->module.'/v_module_addmenu', $tpl);
   }

   function edit_menu($id)
   {
      protect_acct();
      $tpl['module'] = $this->module.'/Module';
      
      $tpl['parent'] = $this->m_module->list_parent_menu();

      $this->form_validation->set_rules('menu', 'Menu Name', 'required');
      if ($this->form_validation->run() == TRUE) {
         $params = array(
            'id'     => $id,
            'menu'   => $this->input->post('menu'),
            'parent_menu' => $this->input->post('parent') != '' ? $this->input->post('parent') : NULL,
            'module' => $this->input->post('module') != '' ? $this->input->post('module') : NULL,
            'order'  => $this->input->post('order'),
            'desc'   => $this->input->post('description'),
            'icon'   => $this->input->post('icon'),
            'is_show'=> $this->input->post('is_show')
         );

         $rs = $this->m_module->update_menu_module($params);

         if ($rs) {
            $this->session->set_flashdata('msg_module', array('status' => TRUE, 'type' => 'success', 'text' => 'Update Menu Module berhasil.'));
         } else {
            
            $this->session->set_flashdata('msg_module', array('status' => FALSE, 'type' => 'danger', 'text' => 'Update Menu Module gagal.'));
         }
         redirect(site_url($this->module.'/Module'));
      } 

      $tpl['menu'] = $this->m_module->get_detail_menu($id);

      $this->template->inject_partial('modules_css', multi_asset( array(
                                                      'vendor/select2/css/select2.min.css' => '_theme_',
                                                      ), 'css' ) );
                                                      
      $this->template->inject_partial('modules_js', multi_asset( array(
                                                'vendor/select2/js/select2.full.min.js' => '_theme_',
                                             ), 'js' ) );

      $this->template->title( 'Edit Menu Module' );
      $this->template->set_breadcrumb( 'Dashboard' , 'system/dashboard/index' );
      $this->template->set_breadcrumb( 'Menu Module' , '');

      $this->template->build($this->module.'/v_module_editmenu', $tpl);
   }

   function menu_check($menu, $parent)
   {
      protect_acct();
      $dt = $this->m_module->check_menu_exist($parent, $menu);
      if ($dt > 0) {
         $this->form_validation->set_message('menu_check', 'Name Menu sudah ada di data Menu sebelumnya, silahkan gunakan nama yang lain.');
         return FALSE;
      } else {
         return TRUE;
      }
   }

   function method($id)
   {
      protect_acct();
      $tpl['module'] = $this->module.'/Module';

      $tpl['data'] = $this->m_module->list_method($id);
      $tpl['menu_id'] = $id;

      $this->template->inject_partial('modules_css', multi_asset( array(
                                                      //'css/components-md.css' => '_theme_',
                                                         'vendor/datatables/css/dataTables.bootstrap4.min.css' => '_theme_',
                                                      ), 'css' ) );

      $this->template->inject_partial('modules_js', multi_asset( array(
                                                'vendor/datatables/js/jquery.dataTables.min.js' => '_theme_',
                                                'vendor/datatables/js/dataTables.bootstrap4.min.js' => '_theme_',
                                             ), 'js' ) );

      $this->template->title( 'Method Module' );
      $this->template->set_breadcrumb( 'Dashboard' , 'dashboard/Dashboard/index' );
      $this->template->set_breadcrumb( 'Menu Module' , 'system/Module/index' );
      $this->template->set_breadcrumb( 'Method' , '');

      $this->template->build($this->module.'/v_module_method', $tpl);
   }

   function datatables_method_list($id)
   {
      protect_acct();
      if (!$this->input->is_ajax_request()) exit('No direct script access allowed');
      $columns = array(
         0 => 'MenuActionId',
         1 => 'MenuActionFunction',
         2 => 'MenuActionSegmen'
      );

      $object = array();
      $object['MenuActionMenuId'] = '='.$id ;

      $order = array();
      if($this->input->post('order')){
         foreach( $this->input->post('order') as $row => $val){
            $order[$columns[$val['column']]] = $val['dir'];
         }
      }
      $length = ($this->input->post('length') == -1) ? NULL : $this->input->post('length');

      $qry = $this->m_module->list_method_module($object, $length, $this->input->post('start'), $order);
      $iTotalRecords = (!is_null($qry)) ? intval($this->m_module->list_method_module($object, NULL, NULL, NULL, 'counter')) : 0;
      $iDisplayStart = intval($this->input->post('start'));
      $sEcho = intval($this->input->post('draw'));


      $records = array();
      $records["data"] = array();
      if(!is_null($qry)){
         foreach($qry->result_array() as $row){
            $records["data"][] = array(
               /*'<input type="checkbox" name="id[]" value="'. $row['UserId'] .'">',*/
               $row['MenuActionName'],
               $row['MenuActionFunction'],
               $row['MenuActionSegmen'],
               '<a href="'. site_url($this->module.'/Module/update_method/'. $row['MenuActionId'] ) .'" id="update" class="btn btn-square btn-round btn-warning"  data-provide="tooltip" title="Edit Method"> <i class="ti-pencil"></i> </a> &nbsp;'.
               '<a href="'. site_url($this->module.'/Module/delete_method/'. $row['MenuActionId'] ) .'" id="delete" class="btn btn-square btn-round btn-danger"  data-provide="tooltip" title="Hapus Method"> <i class="ti-trash"></i> </a>'
            );
         }
      }
 

      if ($this->input->post('customActionType') == "group_action") {
         if($this->input->post('customActionName') == 'Delete'){
            $restrict = restrict( 'system/Group/delete', TRUE );
            if($restrict == TRUE){
               foreach($this->input->post('id') as $val){
                  $action  = $this->m_user->delete_data_group(array( 'UserId' => $val ));
               }
               $records["customActionStatus"] = "OK";
               $records["customActionMessage"] = "Data group yang anda pilih berhasil dihapus!";
            } else {
               $records["customActionStatus"] = "Warning";
               $records["customActionMessage"] = "Maaf, anda tidak memperoleh akses untuk aksi ini!";
            }
         }
      }

      $records["draw"] = $sEcho;
      $records["recordsTotal"] = $iTotalRecords;
      $records["recordsFiltered"] = $iTotalRecords;

      echo json_encode($records);
   }

   function add_method($id)
   {
      protect_acct();
      $tpl['module'] = $this->module.'/Module';
      $menu = $this->m_module->get_detail_menu($id);
      
      $tpl['menu']   = $menu;
      $tpl['menu_id']= $id;

      $this->form_validation->set_rules('name', 'Name', 'required');
      $this->form_validation->set_rules('method', 'Method', 'required|callback_method_check['.$id.']');
      if ($this->form_validation->run()) {
         $params = array(
            'id'     => $id,
            'name'   => $this->input->post('name'),
            'method' => $this->input->post('method'),
            'segmen' => $menu['module'] . '/' . $this->input->post('method')
         );
         $rs = $this->m_module->insert_method($params);
         if ($rs) {
            $this->session->set_flashdata('msg_method', array('status' => TRUE, 'type' => 'success', 'text' => 'Penambahan Menu Module berhasil.'));
         } else {
            $this->session->set_flashdata('msg_method', array('status' => FALSE, 'type' => 'danger', 'text' => 'Penambahan Menu Module gagal.'));
         }
         redirect(site_url($this->module.'/Module/method/'.$id));
      } 
      
      $error = $this->form_validation->error_array();
      if (!empty($this->form_validation->error_array())) {
         foreach($error as $err) {
            $this->session->set_flashdata('msg_method', array('status' => FALSE, 'type' => 'warning', 'text' => 'Penambahan Menu Module gagal. '.$err));  
         }
         redirect(site_url($this->module.'/Module/method/'.$id));  
      }
      
      $this->load->view($this->module . '/v_module_addmethod', $tpl);
   }

   function update_method($id)
   {
      protect_acct();
      $tpl['module'] = $this->module.'/Module';
      $method = $this->m_module->detail_method($id);
      $menu = $this->m_module->get_detail_menu($method['menu_id']);

      $this->form_validation->set_rules('name', 'Name', 'required');
      $this->form_validation->set_rules('method', 'Method', 'required|callback_method_check['.$id.']');
      if ($this->form_validation->run()) {
         $params = array( 
            'name'   => $this->input->post('name'),
            'method' => $this->input->post('method'),
            'segmen' => $menu['module'] . '/' . $this->input->post('method')
         );
         $rs = $this->m_module->update_method($id, $params);
         if ($rs) {
            $this->session->set_flashdata('msg_method', array('status' => TRUE, 'type' => 'success', 'text' => 'Perubahan Menu Module berhasil.'));
         } else {
            $this->session->set_flashdata('msg_method', array('status' => FALSE, 'type' => 'warning', 'text' => 'Perubahan Menu Module gagal.'));
         }

         redirect(site_url($this->module.'/Module/method/'.$method['menu_id']));
      }

      $error = $this->form_validation->error_array();
      if (!empty($this->form_validation->error_array())) {
         foreach($error as $err) {
            $this->session->set_flashdata('msg_method', array('status' => FALSE, 'type' => 'warning', 'text' => 'Perubahan Menu Module gagal. '.$err));  
         }
         redirect(site_url($this->module.'/Module/method/'.$id));  
      }

      $tpl['id']     = $id;
      $tpl['method'] = $method;
      $tpl['menu']   = $menu;

      $this->load->view($this->module . '/v_module_updatemethod', $tpl);
   }

   function method_check($method, $menu_id)
   {
      protect_acct();
      $menu = $this->m_module->get_detail_menu($menu_id);
      $segmen = $menu['module'] .'/'.$method;
      $dt = $this->m_module->check_method_exist($menu_id, $segmen);
      if ($dt > 0) {
         $this->form_validation->set_message('method_check', 'Method sudah digunakan di Controller Module ini.');
         return FALSE;
      } else {
         return TRUE;
      }
   }

   function delete_method($id)
   {
      protect_acct();
      $method = $this->m_module->detail_method($id);
      $menu_id = $method['menu_id'];
      if (!is_null($method)) {
         $rs = $this->m_module->delete_method($id);
         if ($rs) {
            $this->session->set_flashdata('msg_method', array('status' => TRUE, 'type' => 'success', 'text' => 'Hapus Method berhasil.'));
         } else {
            $this->session->set_flashdata('msg_method', array('status' => FALSE, 'type' => 'warning', 'text' => 'Hapus Method gagal.'));
         }
      }
      redirect(site_url($this->module .'/Module/method/'.$menu_id));
   }

}

/* End of file Module.php */
/* Location: ./application/modules/system/controllers/Module.php */