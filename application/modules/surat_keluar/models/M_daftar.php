<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_daftar extends CI_Model
{
   function __construct()
   {
      parent::__construct();
   }

   function get_daftar_surat($tujuan = NULL, $status_baca = NULL, $tgl_awal, $tgl_akhir, $unit_id, $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL)
   {
      $this->db->select('srtId, srtNomorSurat, srtJenisSuratId, srtPerihal, srtIsiRingkasan, srtTglDraft, srtFile, jnsrtNama, srtUnitTujuanUtama, IF(srtUnitTujuanUtama!="", UnitName, srtTujuanSurat) as tujuan, sifdisNama, srtTglBaca, arsId, srtPejabatPtdNama, srtIsSigned');
      $this->db->select('IF(srtUnitTujuanUtama!="", "Internal", "Eksternal") as kategori');

      $this->db->join('jenis_surat', 'jnsrtId=srtJenisSuratId');
      $this->db->join('klasifikasi', 'klasId=srtKlasifikasiId');
      $this->db->join('sys_unit', 'UnitId=srtUnitTujuanUtama', 'left');
      $this->db->join('sifat_disposisi', 'sifdisId=srtSifatSurat', 'left');
      $this->db->join('arsip', 'arsSuratId=srtId', 'left');
      // $this->db->join('surat_disposisi_unit', 'disunitUnitId=srtUnitAsalId', 'left');
      // $this->db->join('surat_disposisi', 'dispId=disunitDisposisiId', 'left');

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

      $this->db->where('srtIsSigned', '1');

      if (!is_null($tujuan)) {
         $this->db->where('(srtUnitTujuanUtama = ' . $tujuan . ' OR srtTujuanSurat = ' . $tujuan . ')');
      }

      if (!is_null($status_baca)) {
         if ($status_baca == 'belum') {
            $this->db->where('srtTglBaca IS NULL');
         } elseif ($status_baca == 'sudah') {
            $this->db->where('srtTglBaca IS NOT NULL');
         }
      }

      // where data selama sepekan
      $tglawal = date('Y-m-d', strtotime(str_replace("/", "-", $tgl_awal)));
      $tglakhir = date('Y-m-d', strtotime(str_replace("/", "-", $tgl_akhir)));
      $this->db->where("(srtTglDraft BETWEEN '" . $tglawal . "' AND '" . $tglakhir . "')");

      $this->db->where('srtStatusId', '4');

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
      $this->db->select('srtId, srtNomorSurat, srtJenisSuratId, srtPerihal, srtIsiRingkasan, srtTglDraft, srtFile, jnsrtNama, srtUnitTujuanUtama, srtTujuanSurat, IF(srtUnitTujuanUtama!="", ut.UnitName, srtTujuanSurat) as tujuan, sifdisNama, stNama, CONCAT(klasKode," - ",jnsklasNama," - ",klasNama) as klasifikasi, stColor, log.logsCatatan, srtUnitAsalId, ua.UnitKode as unit_asal_kode, klasKode, srtPejabatPtdId, jnsrtTemplate,srtPejabatPtdNama,srtPejabatPtdNipm,srtPejabatPtdJabatan, srtUseTemplate, srtTglBaca');
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

   function get_ref_sifat()
   {
      $query = $this->db->select('sifdisId as id, sifdisNama as name')->from('sifat_disposisi')->get();
      if ($query->num_rows() > 0) return $query->result_array();
      return NULL;
   }

   function get_ref_tujuan()
   {
      $query = $this->db->select('IFNULL(srtUnitTujuanUtama,srtTujuanSurat) AS id, IFNULL(UnitName,srtTujuanSurat) AS name')->join('sys_unit', 'UnitId=srtUnitTujuanUtama', 'left')->group_by('id')->from('surat')->get();
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

   // --- DO ----
   function do_update_status_baca($data, $id)
   {
      $this->db->trans_begin();

      $this->db->where('srtId', $id);
      $this->db->update('surat', $data);

      if ($this->db->trans_status() === FALSE) {
         $this->db->trans_rollback();
         return FALSE;
      } else {
         $this->db->trans_commit();
         return TRUE;
      }
   }
}
