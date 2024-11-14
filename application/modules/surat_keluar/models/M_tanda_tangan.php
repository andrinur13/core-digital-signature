<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_tanda_tangan extends CI_Model
{
   function __construct()
   {
      parent::__construct();
   }

   function get_daftar_surat($tgl_awal, $tgl_akhir, $unit_id, $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL)
   {
      $this->db->select('srtId, srtNomorSurat, srtJenisSuratId, srtPerihal, srtIsiRingkasan, srtTglDraft, srtFile, jnsrtNama, srtUnitTujuanUtama, IF(srtUnitTujuanUtama!="", UnitName, srtTujuanSurat) as tujuan, sifdisNama, srtTglBaca, srtStatusId, stNama, stColor, log.logsCatatan, srtPejabatPtdNama, srtIsSigned');
      $this->db->join('jenis_surat', 'jnsrtId=srtJenisSuratId');
      $this->db->join('klasifikasi', 'klasId=srtKlasifikasiId');
      $this->db->join('sys_unit', 'UnitId=srtUnitTujuanUtama', 'left');
      $this->db->join('sifat_disposisi', 'sifdisId=srtSifatSurat', 'left');
      $this->db->join('surat_status_ref', 'stId=srtStatusId', 'left');
      $this->db->join('(SELECT logsId,logsSrtId, logsStatusId, logsCatatan FROM surat_log_status ORDER BY logsId DESC LIMIT 1) AS log', 'srtId=logsSrtId AND srtStatusId=logsStatusId', 'left');

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

      $this->db->where('srtStatusId', "4");

      // where data selama sepekan
      $tglawal = date('Y-m-d', strtotime(str_replace("/", "-", $tgl_awal)));
      $tglakhir = date('Y-m-d', strtotime(str_replace("/", "-", $tgl_akhir)));
      $this->db->where("(srtTglDraft BETWEEN '" . $tglawal . "' AND '" . $tglakhir . "')");

      // $this->db->where('srtStatusId', '3');

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
      $this->db->select('srtId, srtNomorSurat, srtJenisSuratId, srtPerihal, srtIsiRingkasan, srtTglDraft, srtFile, jnsrtNama, srtUnitTujuanUtama, srtTujuanSurat, IF(srtUnitTujuanUtama!="", ut.UnitName, srtTujuanSurat) as tujuan, sifdisNama, stNama, CONCAT(klasKode," - ",jnsklasNama," - ",klasNama) as klasifikasi, stColor, log.logsCatatan, srtUnitAsalId, ua.UnitKode as unit_asal_kode, klasKode, srtPejabatPtdId, jnsrtTemplate,srtPejabatPtdNama,srtPejabatPtdNipm,srtPejabatPtdJabatan, srtUseTemplate');
      $this->db->select('GROUP_CONCAT(DISTINCT(surlampFileLampiran) ORDER BY surlampId SEPARATOR "|") as lampiran');
      $this->db->select('GROUP_CONCAT(DISTINCT(surlampId) ORDER BY surlampId SEPARATOR "|") as lampiran_id');
      $this->db->select('GROUP_CONCAT(DISTINCT(IFNULL(ub.UnitName, tembTujuanEksternal)) ORDER BY tembTujuanEksternal asc, ut.UnitParent, ut.UnitName SEPARATOR "|") AS tembusan');
      $this->db->select('GROUP_CONCAT(DISTINCT(ub.UnitId) ORDER BY ut.UnitParent, ut.UnitName SEPARATOR ",") AS tembusan_internal', false);
      $this->db->select('GROUP_CONCAT(DISTINCT(tembTujuanEksternal) ORDER BY tembTujuanEksternal asc SEPARATOR "|") AS tembusan_eksternal', false);

      $this->db->join('sys_unit ua', 'ua.UnitId=srtUnitAsalId', 'left');
      $this->db->select('srtKlasifikasiId,srtSifatSurat,srtStatusId');
      $this->db->join('jenis_surat', 'jnsrtId=srtJenisSuratId');
      $this->db->join('surat_status_ref', 'stId=srtStatusId');
      $this->db->join('klasifikasi', 'klasId=srtKlasifikasiId');
      $this->db->join('klasifikasi_jenis_ref', 'jnsklasId=klasJenis');
      $this->db->join('sifat_disposisi', 'sifdisId=srtSifatSurat', 'left');
      $this->db->join('sys_unit ut', 'ut.UnitId=srtUnitTujuanUtama', 'left');
      $this->db->join('surat_file_lampiran', 'surlampSuratId=srtId', 'left');
      $this->db->join('surat_tembusan', 'tembSuratId=srtId', 'left');
      $this->db->join('sys_unit ub', 'ub.UnitId=tembUnitId', 'left');
      $this->db->join('(SELECT logsId,logsSrtId, logsStatusId, logsCatatan FROM surat_log_status ORDER BY logsId DESC LIMIT 1) AS log', 'srtId=logsSrtId AND srtStatusId=logsStatusId', 'left');

      $this->db->where('srtId', $id);
      $this->db->group_by('srtId');

      $query = $this->db->get('surat');
      if ($query->num_rows() > 0) return $query->row_array();
      return NULL;
   }

   function get_ref_status($role_id)
   {
      $this->db->select('stId as id, stNama as name');
      if ($role_id == '2') {
         $this->db->where('stRoleId = "2" OR stId != "4"');
      } else {
         $this->db->where('stRoleId != "2" OR stRoleId IS NULL');
      }
      $query = $this->db->get('surat_status_ref');
      if ($query->num_rows() > 0) return $query->result_array();
      return NULL;
   }

   function get_ref_pejabat()
   {
      $query = $this->db->select('pjbId as id, pjbJabatan as name')->from('pejabat_ref')->get();
      if ($query->num_rows() > 0) return $query->result_array();
      return NULL;
   }

   function get_data_referensi_surat($surat_id)
   {
      $this->db->select('srtId, srtNomorSurat, srtJenisSuratId, srtPerihal, srtIsiRingkasan, srtTglDraft, srtFile, jnsrtNama, srtUnitTujuanUtama, IF(srtUnitTujuanUtama!="", UnitName, srtTujuanSurat) as tujuan, sifdisNama');
      $this->db->join('surat', 'srtId=surefSuratRefId');
      $this->db->join('jenis_surat', 'jnsrtId=srtJenisSuratId');
      $this->db->join('klasifikasi', 'klasId=srtKlasifikasiId');
      $this->db->join('sifat_disposisi', 'sifdisId=srtSifatSurat', 'left');
      $this->db->join('sys_unit', 'UnitId=srtUnitTujuanUtama');

      $this->db->where('surefSuratId', $surat_id);

      $query = $this->db->get('surat_ref_surat');
      if ($query->num_rows() > 0) return $query->result_array();
      return NULL;
   }

   function get_detail_kolom_surat($id)
   {
      $this->db->select('sk.*, kolId, kolNama, kolTipe, surkolSuratId, surkolKonten, kolVariable');
      $this->db->join('jenis_surat_kolom sk', 'jnsurkolId=surkolJnsSuratKolomId');
      $this->db->join('kolom_ref', 'kolId=jnsurkolKolomId');
      $this->db->where('surkolSuratId', $id);

      $query = $this->db->get('surat_kolom');
      return $query->result_array();
   }

   function get_detail_pejabat($id)
   {
      $this->db->select('*');
      $this->db->where('pjbId', $id);

      $query = $this->db->get('pejabat_ref');
      return $query->row_array();
   }

   function get_data_last_number_surat($klasId, $tahun)
   {
      $this->db->select('
         srtNomorSurat as nomor
         , srtKlasifikasiId as klasifikasi
         , SUBSTRING_INDEX(SUBSTRING_INDEX(srtNomorSurat,"/",3),"/",-1) AS last_number
         , RIGHT(srtNomorSurat,4) AS tahun
      ');
      $this->db->where('srtKlasifikasiId', $klasId);
      $this->db->where('RIGHT(srtNomorSurat,4)', $tahun);
      $this->db->order_by('last_number', 'desc');
      $this->db->limit(1);

      $query = $this->db->get('surat');
      return $query->row_array();
   }

   // --- DO ----

   function do_update_nomor($params)
   {
      $this->db->trans_begin();

      $this->db->where('srtId', $params['id']);
      $this->db->update('surat', array('srtNomorSurat' => $params['nomor'], 'srtPejabatPtdId' => $params['penandatangan'], 'srtPejabatPtdNama' => $params['pejabat_nama'], 'srtPejabatPtdJabatan' => $params['pejabat_jabatan'], 'srtPejabatPtdNipm' => $params['pejabat_nipm']));

      #update log
      $this->db->where('logSuratId', $params['id']);
      $this->db->update('surat_log', array('logUserUpdate' => $params['user'], 'logTglUpdate' => $params['datetime']));

      if ($this->db->trans_status() === FALSE) {
         $this->db->trans_rollback();
         return array('status' => FALSE, 'data' => NULL);
      } else {
         $this->db->trans_commit();
         return array('status' => TRUE, 'data' => $params['nomor']);
      }
   }

   function do_signature($params)
   {
      $this->db->trans_begin();

      $this->db->where('srtId', $params['id']);
      $this->db->update('surat', array('srtFile' => $params['dokumen'], 'srtIsSigned' => '1'));

      #update log
      $this->db->where('logSuratId', $params['id']);
      $this->db->update('surat_log', array('logUserUpdate' => $params['user'], 'logTglUpdate' => $params['datetime']));

      if ($this->db->trans_status() === FALSE) {
         $this->db->trans_rollback();
         return FALSE;
      } else {
         $this->db->trans_commit();
         return TRUE;
      }
   }

   function do_update_filename_surat($id, $filename)
   {
      $this->db->trans_begin();

      $this->db->where('srtId', $id);
      $this->db->update('surat', array('srtFile' => $filename));

      if ($this->db->trans_status() === FALSE) {
         $this->db->trans_rollback();
         return FALSE;
      } else {
         $this->db->trans_commit();
         return TRUE;
      }
   }
}
