<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_tracking extends CI_Model
{
   function __construct()
   {
      parent::__construct();
   }

   function get_daftar_surat($unit_id, $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL)
   {
      $this->db->select('srtId, srtNomorSurat, srtJenisSuratId, srtPerihal, srtIsiRingkasan, srtTglDraft, srtFile, jnsrtNama, srtUnitTujuanUtama, IF(srtUnitTujuanUtama!="", UnitName, srtTujuanSurat) as tujuan, sifdisNama, stColor, stNama');
      $this->db->join('jenis_surat', 'jnsrtId=srtJenisSuratId');
      $this->db->join('klasifikasi', 'klasId=srtKlasifikasiId');
      $this->db->join('sys_unit', 'UnitId=srtUnitTujuanUtama', 'left');
      $this->db->join('sifat_disposisi', 'sifdisId=srtSifatSurat', 'left');
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

      if ($unit_id != '' or !is_null($unit_id)) {
         $this->db->where('srtUnitAsalId', $unit_id);
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

   function get_detail_surat($id)
   {
      $this->db->select('srt.srtId, srt.srtNomorSurat, srt.srtJenisSuratId, srt.srtPerihal, srt.srtIsiRingkasan, srt.srtTglDraft, srt.srtFile, stColor, stNama, srt.srtStatusId , GROUP_CONCAT(srt_ref.srtNomorSurat SEPARATOR "<br>") AS referensi_surat, srt.srtTglBaca, jnsrtNama, srt.srtIsSigned');
      $this->db->select('logPjbIsDibaca, logPjbTglBaca, logPjbTglArahan, logCatatan, logTanggal');
      $this->db->select('tindId, tindTglCreate, tindCatatan');

      $this->db->join('jenis_surat', 'jnsrtId=srtJenisSuratId');
      $this->db->join('surat_status_ref', 'stId=srt.srtStatusId');
      $this->db->join('surat_ref_surat', 'surefSuratId=srtId', 'left');
      $this->db->join('surat srt_ref', 'srt_ref.srtId=surefSuratRefId', 'left');
      $this->db->join('surat_log', 'srt.srtId=logSuratId', 'left');
      $this->db->join('surat_tindakan', 'srt.srtId=tindSuratId', 'left');

      $user_id = $this->session->userdata('user_id');
      $this->db->where('srt.srtId', $id);
      $this->db->where('logUserId != ' . $user_id);

      $query = $this->db->get('surat srt');
      if ($query->num_rows() > 0) return $query->row_array();
      return NULL;
   }

   function get_log_surat($id)
   {
      $this->db->select('l.*');
      $this->db->join('surat', 'srtId=logSuratId');
      $this->db->where('logSuratId', $id);

      $query = $this->db->get('surat_log l');
      if ($query->num_rows() > 0) return $query->row_array();
      return NULL;
   }

   function get_log_status_surat($id)
   {
      $this->db->select('ls.*, stNama, stColor');
      $this->db->join('surat', 'srtId=logsSrtId');
      $this->db->join('surat_status_ref', 'stId=logsStatusId');
      $this->db->where('logsSrtId', $id);
      $this->db->order_by('logsTglUpdate');

      $query = $this->db->get('surat_log_status ls');
      if ($query->num_rows() > 0) return $query->result_array();
      return NULL;
   }

   # get_balasan_surat
   function get_balasan_surat($id)
   {
      $this->db->select('sr.*, srtId, srtNomorSurat, srtPejabatPtdId, srtTglDraft, logTglUpdate, srtIsSigned');
      $this->db->join('surat', 'srtId=surefSuratId');
      $this->db->join('surat_log', 'srtId=logSuratId');
      $this->db->where('surefSuratRefId', $id);

      $query = $this->db->get('surat_ref_surat sr');
      if ($query->num_rows() > 0) return $query->row_array();
      return NULL;
   }
}
