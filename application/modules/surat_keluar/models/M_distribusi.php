<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_distribusi extends CI_Model
{
   function __construct()
   {
      parent::__construct();
   }

   function get_daftar_surat($unit_id, $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL)
   {
      $this->db->select('srtId, srtNomorSurat, srtJenisSuratId, srtPerihal, srtIsiRingkasan, srtTglDraft, srtFile, srtStatusId, jnsrtNama, stColor, stNama, GROUP_CONCAT(distId SEPARATOR "|") as distribusi_id');
      $this->db->join('surat_distribusi', 'srtId=distSuratId', 'left');
      $this->db->join('jenis_surat', 'jnsrtId=srtJenisSuratId', 'left');
      $this->db->join('surat_status_ref', 'stId=srtStatusId', 'left');

      if (!is_null($object)) {
         // print_r($object);exit;
         foreach ($object as $row => $val) {
            if (preg_match("/(<=|>=|=|<|>)(\s*)(.+)/i", trim($val), $matches)) {
               $this->db->where($row . ' ' . $matches[1], $matches[3]);
            } elseif ($row == 'filter_key') {
               $where = "(srtNomorSurat LIKE '%" . $val . "%' OR srtPerihal LIKE '%" . $val . "%')";
               $this->db->where($where);
            } else {
               $this->db->where($row . ' LIKE', '%' . $val . '%');
            }
         }
      }

      $this->db->where('srtStatusId', '4');

      if ($unit_id != '' or !is_null($unit_id)) {
         $this->db->where('srtUnitAsalId', $unit_id);
      }

      $this->db->group_by('srtId');


      if (!is_null($limit) && !is_null($offset)) {
         $this->db->limit($limit, $offset);
      }

      if (!empty($order)) {
         foreach ($order as $row => $val) {
            $ordered = (isset($val)) ? $val : 'ASC';
            $this->db->order_by($row, $val);
         }
      }

      if (is_null($status)) {
         $query = $this->db->get('surat');
         if ($query->num_rows() > 0) return $query;
         return NULL;
      } else if ($status == 'counter') {
         return $this->db->count_all_results('surat');
         // return $query;
      }
   }

   function get_detail_surat($id)
   {
      $this->db->select('srtId, srtNomorSurat, srtPerihal, srtTglDraft');
      $this->db->where('srtId', $id);

      $query = $this->db->get('surat');
      if ($query->num_rows() > 0) return $query->row_array();
      return NULL;
   }

   function get_distribusi_surat($id)
   {
      $this->db->select('*');
      $this->db->where('distSuratId', $id);

      $query = $this->db->get('surat_distribusi');
      if ($query->num_rows() > 0) return $query->result_array();
      return NULL;
   }

   // ----- DO ------ //
   function do_distribusi($params)
   {
      $this->db->trans_begin();

      $arr_penerima = $params['penerima'];
      $arr_email = $params['email'];
      $arr_no_wa = $params['no_wa'];
      for ($i = 0; $i < count($arr_penerima); $i++) {
         $data  = array(
            'distSuratId' => $params['id'],
            'distNamaPenerima' => $arr_penerima[$i],
            'distEmail' => $arr_email[$i],
            'distNoWA' => $arr_no_wa[$i],
            'distStatusKirim' => '1',
            'distUserCreate' => $params['user'],
            'distTglCreate' => $params['datetime'],
         );
         $this->db->insert('surat_distribusi', $data);
      }

      if ($this->db->trans_status() === FALSE) {
         $this->db->trans_rollback();
         return FALSE;
      } else {
         $this->db->trans_commit();
         return TRUE;
      }
   }
}
