<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_konsep extends CI_Model
{
   function __construct()
   {
      parent::__construct();
   }

   function get_konsep_surat($tgl_awal, $tgl_akhir, $kategori = NULL, $unit_id, $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL)
   {
      $this->db->select('srtId, srtNomorSurat, srtJenisSuratId, srtPerihal, srtIsiRingkasan, srtTglDraft, srtFile, jnsrtNama, srtUnitTujuanUtama, IF(srtUnitTujuanUtama!="", UnitName, srtTujuanSurat) as tujuan, sifdisNama, stId, stColor, stNama, log.logsCatatan, srtIsSigned');
      $this->db->select('IF(srtUnitTujuanUtama!="", "Internal", "Eksternal") as kategori');
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
               $where = "(srtNomorSurat LIKE '%" . $val . "%' OR jnsrtNama LIKE '%" . $val . "%')";
               $this->db->where($where);
            } else {
               $this->db->where($row . ' LIKE', '%' . $val . '%');
            }
         }
      }

      if (!is_null($kategori)) {
         if ($kategori == 'internal') {
            $this->db->where('srtUnitTujuanUtama IS NOT NULL');
         } elseif ($kategori == 'eksternal') {
            $this->db->where('srtTujuanSurat IS NOT NULL');
         }
      }

      $this->db->where('srtIsSigned !="1"');

      // where data selama sepekan
      $tglawal = date('Y-m-d', strtotime(str_replace("/", "-", $tgl_awal)));
      $tglakhir = date('Y-m-d', strtotime(str_replace("/", "-", $tgl_akhir)));
      $this->db->where("(srtTglDraft BETWEEN '" . $tglawal . "' AND '" . $tglakhir . "')");

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

   function get_referensi_surat($unit_id, $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL)
   {
      $this->db->select('srtId, srtNomorSurat, srtPerihal, srtTglDraft, arsId');
      $this->db->join('jenis_surat', 'jnsrtId=srtJenisSuratId');
      $this->db->join('arsip', 'arsSuratId=srtId AND srtUnitTujuanUtama=arsTujuanUnitId', 'left');

      if (!is_null($object)) {
         foreach ($object as $row => $val) {
            if (preg_match("/(<=|>=|=|<|>|!=)(\s*)(.+)/i", trim($val), $matches))
               $this->db->where($row . ' ' . $matches[1], $matches[3]);
            else
               $this->db->or_where($row . ' LIKE', '%' . $val . '%');
         }
      }

      // $this->db->where('arsId IS NOT NULL');

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

   function get_ref_jenis_surat()
   {
      $query = $this->db->select('jnsrtId as id,CONCAT(jnsrtNama," (",jnsrtKode,")") as name')->from('jenis_surat')->get();
      if ($query->num_rows() > 0) return $query->result_array();
      return NULL;
   }

   function get_ref_klasifikasi()
   {
      $query = $this->db->select('klasId as id, klasKode as kode, CONCAT(klasKode," - ",jnsklasNama," - ",klasNama) as name')
         ->from('klasifikasi')
         ->join('klasifikasi_jenis_ref', 'jnsklasId=klasJenis')
         ->get();
      if ($query->num_rows() > 0) return $query->result_array();
      return NULL;
   }

   function get_ref_unit()
   {
      $query = $this->db->select('UnitId as id, UnitName as name')->from('sys_unit')->get();
      if ($query->num_rows() > 0) return $query->result_array();
      return NULL;
   }

   function get_detail_surat($id)
   {
      $this->db->select('srtId, srtNomorSurat, srtJenisSuratId, srtPerihal, srtIsiRingkasan, srtTglDraft, srtFile, jnsrtNama, srtUnitTujuanUtama, srtTujuanSurat, IF(srtUnitTujuanUtama!="", ut.UnitName, srtTujuanSurat) as tujuan, sifdisNama, stNama, CONCAT(klasKode," - ",jnsklasNama," - ",klasNama) as klasifikasi, stColor, log.logsCatatan, jnsrtTemplate, srtUseTemplate');
      $this->db->select('GROUP_CONCAT(DISTINCT(surlampFileLampiran) ORDER BY surlampId SEPARATOR "|") as lampiran');
      $this->db->select('GROUP_CONCAT(DISTINCT(surlampId) ORDER BY surlampId SEPARATOR "|") as lampiran_id');
      $this->db->select('GROUP_CONCAT(DISTINCT(IFNULL(ub.UnitName, tembTujuanEksternal)) ORDER BY tembTujuanEksternal asc, ut.UnitParent, ut.UnitName SEPARATOR "|") AS tembusan');
      $this->db->select('GROUP_CONCAT(DISTINCT(ub.UnitId) ORDER BY ut.UnitParent, ut.UnitName SEPARATOR ",") AS tembusan_internal', false);
      $this->db->select('GROUP_CONCAT(DISTINCT(tembTujuanEksternal) ORDER BY tembTujuanEksternal asc SEPARATOR "|") AS tembusan_eksternal', false);

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

   function get_ref_status()
   {
      $query = $this->db->select('stId as id, stNama as name')->from('surat_status_ref')->get();
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

   function get_kolom_surat($jenis)
   {
      $this->db->select('sk.*, kolId, kolNama, kolTipe');
      $this->db->join('kolom_ref', 'kolId=jnsurkolKolomId');
      $this->db->where('jnsurkolJenisSuratId', $jenis);

      $query = $this->db->get('jenis_surat_kolom sk');
      return $query->result_array();
   }

   function get_kolom_surat_bysurat_jenis($id, $jenis)
   {
      $this->db->select('sk.*, kolId, kolNama, kolTipe, surkolSuratId, surkolKonten');
      $this->db->join('surat_kolom', 'jnsurkolId=surkolJnsSuratKolomId AND surkolSuratId = "' . $id . '"', 'left');
      $this->db->join('kolom_ref', 'kolId=jnsurkolKolomId', 'left');
      $this->db->where('jnsurkolJenisSuratId', $jenis);

      $query = $this->db->get('jenis_surat_kolom sk');
      return $query->result_array();
   }

   function get_detail_kolom_surat($id)
   {
      $this->db->select('sk.*, kolId, kolNama, kolTipe, surkolSuratId, surkolKonten');
      $this->db->join('jenis_surat_kolom sk', 'jnsurkolId=surkolJnsSuratKolomId');
      $this->db->join('kolom_ref', 'kolId=jnsurkolKolomId');
      $this->db->where('surkolSuratId', $id);

      $query = $this->db->get('surat_kolom');
      return $query->result_array();
   }

   function get_template_surat($jenis)
   {
      $this->db->select('jnsrtTemplate');
      $this->db->where('jnsrtId', $jenis);

      $query = $this->db->get('jenis_surat');
      return $query->row_array();
   }

   //  DO
   function input_surat_keluar($params)
   {
      $this->db->trans_begin();

      if ($params['kategori'] == 'internal') {
         $field_tujuan = 'srtUnitTujuanUtama';
      } elseif ($params['kategori'] == 'eksternal') {
         $field_tujuan = 'srtTujuanSurat';
      }
      $data  = array(
         'srtJenisSuratId' => $params['jenis'],
         'srtSifatSurat' => $params['sifat'],
         'srtPerihal' => $params['perihal'],
         // 'srtNomorSurat' => $params['no_surat'],
         'srtIsiRingkasan' => $params['ringkasan'],
         'srtKlasifikasiId' => $params['klasifikasi'],
         'srtUnitAsalId' => $params['asal'],
         $field_tujuan => $params['tujuan'],
         'srtFile' => $params['dokumen'],
         'srtUserDrafter' => $params['user'],
         'srtTglDraft' => $params['tanggal'] . ' ' . date('H:i:s'),
         'srtStatusId' => '1',
         'srtUseTemplate' => $params['use_template'],
      );

      // print_r($data);
      // die;
      $this->db->insert('surat', $data);
      $id = $this->db->insert_id();

      // insert kolom surat
      $surat_kolom_id = $params['surat_kolom_id'];
      if (!empty($surat_kolom_id)) {
         $surat_kolom = $params['surat_kolom'];
         $jenis_kolom_id = $params['jenis_kolom_id'];
         for ($l = 0; $l < count($surat_kolom_id); $l++) {
            if ($surat_kolom[$surat_kolom_id[$l]] != '') {
               $this->db->insert('surat_kolom', array('surkolSuratId' => $id, 'surkolJnsSuratKolomId' => $jenis_kolom_id[$surat_kolom_id[$l]], 'surkolKonten' => $surat_kolom[$surat_kolom_id[$l]]));
            }
         }
      }

      // isi tembusan internal jika ada
      $arr_tembusan_int = $params['tembusan_internal'];
      if (!empty($arr_tembusan_int)) {
         for ($t = 0; $t < count($arr_tembusan_int); $t++) {
            $this->db->insert('surat_tembusan', array('tembUnitId' => $arr_tembusan_int[$t], 'tembSuratId' => $id));
         }
      }

      // isi tembusan eksternal jika ada
      $arr_tembusan_eks = $params['tembusan_eksternal'];
      if (!empty($arr_tembusan_eks)) {
         for ($e = 0; $e < count($arr_tembusan_eks); $e++) {
            $this->db->insert('surat_tembusan', array('tembTujuanEksternal' => $arr_tembusan_eks[$e], 'tembSuratId' => $id));
         }
      }

      // isi referensi surat jika ada
      $ref_surat_id = $params['ref_surat_id'];
      if (!empty($ref_surat_id)) {
         for ($i = 0; $i < count($ref_surat_id); $i++) {
            $arr_surat = explode("~", $ref_surat_id[$i]); // [0]: srtId; [1]: asrId;
            $ref_id = decode($arr_surat[1]);
            $this->db->insert('surat_ref_surat', array('surefSuratId' => $id, 'surefSuratRefId' => decode($arr_surat[0]), 'surefArsipRefId' => ($ref_id != '') ? $ref_id : NULL));
         }
      }

      # insert lampiran jika ada
      $file_lampiran = $params['lampiran'];
      if (!is_null($file_lampiran) or $file_lampiran != '') {
         for ($n = 0; $n < count($file_lampiran); $n++) {
            if ($file_lampiran[$n] != '') {
               $this->db->insert('surat_file_lampiran', array('surlampSuratId' => $id, 'surlampFileLampiran' => $file_lampiran[$n]));
            }
         }
      }

      # insert log surat --
      $this->db->insert('surat_log', array('logSuratId' => $id, 'logUserId' => $params['user'], 'logTanggal' => $params['datetime'], 'logTglCreate' => $params['datetime'], 'logUserCreate' => $params['username']));
      $this->db->insert('surat_log_status', array('logsSrtId' => $id, 'logsStatusId' => '1', 'logsUserUpdate' => $params['username'], 'logsTglUpdate' => $params['datetime']));

      if ($this->db->trans_status() === FALSE) {
         $this->db->trans_rollback();
         return FALSE;
      } else {
         $this->db->trans_commit();
         return array('status' => TRUE, 'id' => $id);
      }
   }

   function update_surat_keluar($params)
   {
      $this->db->trans_begin();

      if ($params['kategori'] == 'internal') {
         $field_tujuan = 'srtUnitTujuanUtama';
      } elseif ($params['kategori'] == 'eksternal') {
         $field_tujuan = 'srtTujuanSurat';
      }
      $data  = array(
         'srtSifatSurat' => $params['sifat'],
         'srtPerihal' => $params['perihal'],
         'srtIsiRingkasan' => $params['ringkasan'],
         'srtKlasifikasiId' => $params['klasifikasi'],
         'srtUnitAsalId' => $params['asal'],
         $field_tujuan => $params['tujuan'],
         'srtFile' => $params['dokumen'],
         'srtUserDrafter' => $params['user'],
         'srtStatusId' => '1',
      );

      // print_r($data);
      // die;
      $this->db->where('srtId', $params['id']);
      $this->db->update('surat', $data);

      // insert kolom surat
      # delete older kolom surat
      $this->db->delete('surat_kolom', array('surkolSuratId' => $params['id']));
      $surat_kolom_id = $params['surat_kolom_id'];
      if (!empty($surat_kolom_id)) {
         $surat_kolom = $params['surat_kolom'];
         $jenis_kolom_id = $params['jenis_kolom_id'];
         for ($l = 0; $l < count($surat_kolom_id); $l++) {
            if ($surat_kolom[$surat_kolom_id[$l]] != '') {
               $this->db->insert('surat_kolom', array('surkolSuratId' => $params['id'], 'surkolJnsSuratKolomId' => $jenis_kolom_id[$surat_kolom_id[$l]], 'surkolKonten' => $surat_kolom[$surat_kolom_id[$l]]));
            }
         }
      }


      // isi tembusan internal jika ada
      # delete older tembusan internal
      $this->db->where('tembSuratId', $params['id']);
      $this->db->where('tembUnitId IS NOT NULL');
      $this->db->delete('surat_tembusan');
      $arr_tembusan_int = $params['tembusan_internal'];
      if (!empty($arr_tembusan_int)) {
         for ($t = 0; $t < count($arr_tembusan_int); $t++) {
            $this->db->insert('surat_tembusan', array('tembUnitId' => $arr_tembusan_int[$t], 'tembSuratId' => $params['id']));
         }
      }

      // isi tembusan eksternal jika ada
      # delete older tembusan eksternal
      $this->db->where('tembSuratId', $params['id']);
      $this->db->where('tembUnitId IS NULL');
      $this->db->delete('surat_tembusan');
      $arr_tembusan_eks = $params['tembusan_eksternal'];
      if (!empty($arr_tembusan_eks)) {
         for ($e = 0; $e < count($arr_tembusan_eks); $e++) {
            $this->db->insert('surat_tembusan', array('tembTujuanEksternal' => $arr_tembusan_eks[$e], 'tembSuratId' => $params['id']));
         }
      }

      // delete older referensi surat
      $this->db->where('surefSuratId', $params['id']);
      $this->db->delete('surat_ref_surat');
      // isi referensi surat jika ada
      $ref_surat_id = $params['ref_surat_id'];
      if (!empty($ref_surat_id)) {
         // insert new referensi 
         for ($i = 0; $i < count($ref_surat_id); $i++) {
            $arr_surat = explode("~", $ref_surat_id[$i]); // [0]: srtId; [1]: asrId;
            $ref_id = decode($arr_surat[1]);
            $this->db->insert('surat_ref_surat', array('surefSuratId' => $params['id'], 'surefSuratRefId' => decode($arr_surat[0]), 'surefArsipRefId' => ($ref_id != '') ? $ref_id : NULL));
         }
      }

      # insert lampiran jika ada
      $file_lampiran = $params['lampiran'];
      if (!is_null($file_lampiran) or $file_lampiran != '') {
         for ($n = 0; $n < count($file_lampiran); $n++) {
            if ($file_lampiran[$n] != '') {
               $this->db->insert('surat_file_lampiran', array('surlampSuratId' => $params['id'], 'surlampFileLampiran' => $file_lampiran[$n]));
            }
         }
      }

      # update log
      $this->db->where('logSuratId', $params['id']);
      $this->db->update('surat_log', array('logTglUpdate' => $params['datetime'], 'logUserUpdate' => $params['username']));

      # insert log status jika selain proses
      if ($params['status'] != '1') {
         $catatan = ($params['catatan'] != '') ? $params['catatan'] : NULL;
         $this->db->insert('surat_log_status', array('logsSrtId' => $params['id'], 'logsStatusId' => '1', 'logsCatatan' => $catatan, 'logsUserUpdate' => $params['username'], 'logsTglUpdate' => $params['datetime']));
      }

      if ($this->db->trans_status() === FALSE) {
         $this->db->trans_rollback();
         return FALSE;
      } else {
         $this->db->trans_commit();
         return TRUE;
      }
   }

   function delete_file_lampiran($id)
   {
      $this->db->trans_begin();

      $this->db->delete('surat_file_lampiran', array('surlampId' => $id));

      if ($this->db->trans_status() === FALSE) {
         $this->db->trans_rollback();
         return FALSE;
      } else {
         $this->db->trans_commit();
         return TRUE;
      }
   }

   function delete_tembusan_eksternal($value, $id)
   {
      $this->db->trans_begin();

      $this->db->delete('surat_tembusan', array('tembTujuanEksternal' => $value, 'tembSuratId' => $id));

      if ($this->db->trans_status() === FALSE) {
         $this->db->trans_rollback();
         return FALSE;
      } else {
         $this->db->trans_commit();
         return TRUE;
      }
   }

   function update_filename_surat($id, $filename)
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
