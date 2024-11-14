<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_arahan extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function get_list($params = array(), $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL)
	{
		$tanggal = $params['tanggal'];
		$kategori = $params['kategori'];
		$this->db->select("srtId as id
			,srtNomorSurat as nomor
			,srtPerihal as perihal
			,srtFile as file
			,sifdisNama as sifat
			,srtTglDraft as tanggal
			,srtTglBaca as baca
			,IF(srtAsalSurat is not null, srtAsalSurat,UnitName) as asal
			,IF(srtAsalSurat is not null, 'Eksternal','Internal') as asal_surat
			,logCatatan as arahan
		");

		$this->db->join('sys_unit', 'UnitId=srtUnitAsalId', 'LEFT');
		$this->db->join('sifat_disposisi', 'sifdisId=srtSifatSurat', 'LEFT');
		$this->db->join('surat_log', 'logSuratId=srtId AND logCatatan IS NOT NULL', 'LEFT');

		$this->db->where_in('srtStatusId', [3, 4]);
		if ($params['isbaca'] == '1') {
			$this->db->where('srtUserBaca IS NOT NULL');
		}

		if ($params['isbaca'] == '0') {
			$this->db->where('srtUserBaca IS NULL');
		}

		if ($params['tanggal'] != '') {
			$this->db->where("srtTglDraft BETWEEN '$tanggal 00:00:00' AND '$tanggal 23:59:59'");
		}

		if ($kategori == 'Internal') {
			$this->db->where('srtAsalSurat is NULL');
		}

		if ($kategori == 'Eksternal') {
			$this->db->where('srtAsalSurat is NOT NULL');
		}

		$this->db->where('srtIsDelete', 0);

		$this->db->group_by('srtId');
		if (!is_null($object)) {
			foreach ($object as $row => $val) {
				if (preg_match("/(<=|>=|=|<|>)(\s*)(.+)/i", trim($val), $matches)) {
					$this->db->where($row . ' ' . $matches[1], $matches[3]);
				} elseif ($row == 'filter_key') {
					$where = "(srtNomorSurat LIKE '%" . $val . "%' or srtAsalSurat LIKE '%" . $val . "%' or srtPerihal LIKE '%" . $val . "%' or UnitName LIKE '%" . $val . "%')";
					$this->db->where($where);
				} else {
					$this->db->where($row . ' LIKE', '%' . $val . '%');
				}
			}
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
			$query = $this->db->get("surat");
			if ($query->num_rows() > 0) return $query;
			return NULL;
		} else if ($status == 'counter') {
			return $this->db->count_all_results("surat");
		}
	}

	function get_list_arahan($params = array(), $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL)
	{
		$tanggal = $params['tanggal'];
		$kategori = $params['kategori'];
		$this->db->select("srtId as id
			,srtNomorSurat as nomor
			,srtPerihal as perihal
			,srtFile as file
			,sifdisNama as sifat
			,srtTglDraft as tanggal
			,srtTglBaca as baca
			,IF(srtAsalSurat is not null, srtAsalSurat,UnitName) as asal
			,IF(srtAsalSurat is not null, 'Eksternal','Internal') as asal_surat
			,logCatatan as arahan
			,logPjbTglBaca as tglBacaArahan
			,logPjbArahan as jawaban
		");

		$this->db->join('sys_unit', 'UnitId=srtUnitAsalId', 'LEFT');
		$this->db->join('sifat_disposisi', 'sifdisId=srtSifatSurat', 'LEFT');
		$this->db->join('surat_log', 'logSuratId=srtId');

		$this->db->where_in('srtStatusId', [3, 4]);
		if ($params['isbaca'] == '1') {
			$this->db->where('logPjbTglBaca IS NOT NULL');
		}

		if ($params['isbaca'] == '0') {
			$this->db->where('logPjbTglBaca IS NULL');
		}

		if ($params['tanggal'] != '') {
			$this->db->where("srtTglDraft BETWEEN '$tanggal 00:00:00' AND '$tanggal 23:59:59'");
		}

		if ($kategori == 'Internal') {
			$this->db->where('srtAsalSurat is NULL');
		}

		if ($kategori == 'Eksternal') {
			$this->db->where('srtAsalSurat is NOT NULL');
		}

		$this->db->where('srtIsDelete', 0);

		$this->db->order_by('logPjbTglBaca ASC, srtTglDraft ASC');
		$this->db->group_by('srtId');
		if (!is_null($object)) {
			foreach ($object as $row => $val) {
				if (preg_match("/(<=|>=|=|<|>)(\s*)(.+)/i", trim($val), $matches)) {
					$this->db->where($row . ' ' . $matches[1], $matches[3]);
				} elseif ($row == 'filter_key') {
					$where = "(srtNomorSurat LIKE '%" . $val . "%' or srtAsalSurat LIKE '%" . $val . "%' or srtPerihal LIKE '%" . $val . "%' or UnitName LIKE '%" . $val . "%')";
					$this->db->where($where);
				} else {
					$this->db->where($row . ' LIKE', '%' . $val . '%');
				}
			}
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
			$query = $this->db->get("surat");
			if ($query->num_rows() > 0) return $query;
			return NULL;
		} else if ($status == 'counter') {
			return $this->db->count_all_results("surat");
		}
	}

	function detail($id, $isPejabat)
	{
		$unitId = get_user_unit_id();
		$pejabatId = get_user_id();
		$this->db->select("
			srtId as id
			,srtNomorSurat as nomor
			,srtPerihal as perihal
			,srtFile as file
			,sifdisNama as sifat
			,srtTglDraft as tanggal
			,srtIsiRingkasan as ringkasan
			,srtTglBaca as baca
			,arsId as is_arsip
			,dispId as is_disposisi
			,arsBerkasId as berkas
			,brksNama as berkas_nama
			,srtJenisSuratId as jenisId
			,srtKlasifikasiId as klasifikasiId
			,srtSifatSurat as sifatId
			,CONCAT(klasKode,' - ',klasNama) as klasifikasi
			,CONCAT(jnsrtKode,' - ',jnsrtNama) as jenis_surat
			,srtJenisSuratId as jenis_id
			,arsJenisEksemplarId as eksemplar
			,eksNama as eksemplar_nama
			,IF(srtAsalSurat is not null, srtAsalSurat,UnitName) as asal
			,IF(srtAsalSurat is not null, 'Eksternal','Internal') as asal_surat
			,IF(srtAsalSurat is not null, srtAsalSurat,'srtUnitAsalId') as id_asal_surat
			,logId
			,logCatatan as arahan
			,logPjbUserArahan as pejabat
			,UserRealName as pemohon
			,logPjbArahan as jawaban
			,logPjbTglBaca as tglBacaArahan
			,logTanggal as tgl_permohonan
		");

		$this->db->join('sys_unit', 'UnitId=srtUnitAsalId', 'LEFT');
		$this->db->join('sifat_disposisi', 'sifdisId=srtSifatSurat', 'LEFT');
		if ($isPejabat == TRUE) {
			$this->db->join("arsip", "arsSuratId=srtId", "LEFT");
		} else {
			$this->db->join("arsip", "arsSuratId=srtId AND arsTujuanUnitId = $unitId", "LEFT");
		}
		$this->db->join('surat_disposisi', 'srtId=dispId', 'LEFT');
		$this->db->join('klasifikasi', 'klasId=srtKlasifikasiId');
		$this->db->join('jenis_surat', 'jnsrtId=srtJenisSuratId');
		$this->db->join('berkas', 'brksId=arsBerkasId', 'LEFT');
		$this->db->join('jenis_eksemplar', 'eksId=arsJenisEksemplarId', 'LEFT');
		if ($isPejabat == TRUE) {
			$this->db->join('surat_log', "logSuratId=srtId AND logPjbId=$pejabatId", 'LEFT');
		} else {
			$this->db->join('surat_log', 'logSuratId=srtId', 'LEFT');
		}
		$this->db->join('sys_user', 'UserId=logUserId', 'LEFT');

		$this->db->where('srtId', $id);
		if ($isPejabat == FALSE) {
			$this->db->where('srtUnitTujuanUtama', $unitId);
		}
		$this->db->group_by('srtId');
		$query = $this->db->get('surat');
		if ($query->num_rows() > 0) return $query->row_array();
		return NULL;
	}

	function arahan_surat($id)
	{
		$this->db->select("logId as id, logCatatan as catatan");
		$this->db->where('logSuratId', $id);
		$this->db->where('logCatatan IS NOT NULL');
		$query = $this->db->get('surat_log');
		if ($query->num_rows() > 0) return $query->result_array();
		return NULL;
	}


	function status_tindakan($id)
	{
		$this->db->select("jnstindNama as nama, jnsColor as color");
		$this->db->join('jenis_tindakan', 'tindJenisTindakanId=jnstindId');
		$this->db->where('tindSuratId', $id);
		$this->db->group_by('jnstindId');
		$query = $this->db->get('surat_tindakan');
		if ($query->num_rows() > 0) return $query->result_array();
		return NULL;
	}

	function detail_arahan($id)
	{
		$this->db->select("logId as id, logCatatan as catatan");
		$this->db->where('logId', $id);
		$query = $this->db->get('surat_log');
		if ($query->num_rows() > 0) return $query->row_array();
		return NULL;
	}

	function baca_arahan($data)
	{
		$this->db->trans_begin();

		$this->db->where('logId', $data['id']);
		$this->db->update('surat_log', $data['data']);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return array('status' => FALSE);

			$this->db->close();
		} else {
			$this->db->trans_commit();
			return array('status' => TRUE);
			$this->db->close();
		}
	}

	function beri_arahan($data)
	{
		$this->db->trans_begin();

		$this->db->where('logId', $data['id']);
		$this->db->update('surat_log', $data['data']);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return array('status' => FALSE);

			$this->db->close();
		} else {
			$this->db->trans_commit();
			return array('status' => TRUE);
			$this->db->close();
		}
	}
}
