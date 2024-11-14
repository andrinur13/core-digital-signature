<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_disposisi_keluar extends CI_Model
{
   function __construct()
   {
      parent::__construct();
   }

   function get_daftar_surat($unit_id, $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL)
   {
      $this->db->select('srtId, srtNomorSurat, srtJenisSuratId, srtPerihal, srtIsiRingkasan, srtTglDraft, srtFile, jnsrtNama, sifdisNama, stColor, stNama, dispTglCreate');
      $this->db->join('surat_disposisi', 'srtId=dispSuratId');
      $this->db->join('sifat_disposisi', 'sifdisId=dispSifatDisposisiId');
      $this->db->join('jenis_surat', 'jnsrtId=srtJenisSuratId');
      $this->db->join('surat_status_ref', 'stId=srtStatusId');

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

      $this->db->where('dispIsBatal = "0"'); //yang tidak batal

      if ($unit_id != '' or !is_null($unit_id)) {
         $this->db->where('srtUnitTujuanUtama', $unit_id);
      }

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

   function get_disposisi_surat($id)
   {
      $this->db->select('d.*, srtId, srtNomorSurat, srtPerihal, srtTglDraft, srtFile, sifdisNama');
      $this->db->join('surat', 'srtId=dispSuratId');
      $this->db->join('sifat_disposisi', 'sifdisId=dispSifatDisposisiId');

      $this->db->where('dispSuratId', $id);

      $query = $this->db->get('surat_disposisi d');
      if ($query->num_rows() > 0) return $query->row_array();
      return NULL;
   }

   function get_disposisi_unit_surat($id)
   {
      $this->db->select('du.*, UnitName, insNama');
      $this->db->join('surat_disposisi', 'dispId=disunitDisposisiId', 'left');
      $this->db->join('sys_unit', 'UnitId=disunitUnitId', 'left');
      $this->db->join('instruksi_disposisi', 'instId=disunitInstruksiId', 'left');

      $this->db->where('diunitIsBatal = "0"'); //yang tidak batal
      $this->db->where('disunitDisposisiId', $id);

      $query = $this->db->get('surat_disposisi_unit du');
      if ($query->num_rows() > 0) return $query->result_array();
      return NULL;
   }
}
