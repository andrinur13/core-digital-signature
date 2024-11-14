<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_drafter extends CI_Model
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

	function detail($id)
	{
		$unitId = get_user_unit_id();
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

		");

		$this->db->join('sys_unit', 'UnitId=srtUnitAsalId', 'LEFT');
		$this->db->join('sifat_disposisi', 'sifdisId=srtSifatSurat', 'LEFT');
		$this->db->join("arsip", "arsSuratId=srtId AND arsTujuanUnitId = $unitId", "LEFT");
		$this->db->join('surat_disposisi', 'srtId=dispId', 'LEFT');
		$this->db->join('klasifikasi', 'klasId=srtKlasifikasiId');
		$this->db->join('jenis_surat', 'jnsrtId=srtJenisSuratId');
		$this->db->join('berkas', 'brksId=arsBerkasId', 'LEFT');
		$this->db->join('jenis_eksemplar', 'eksId=arsJenisEksemplarId', 'LEFT');
		$this->db->join('surat_log', 'logSuratId=srtId AND logCatatan IS NOT NULL', 'LEFT');

		$this->db->where('srtId', $id);
		$this->db->where('srtUnitTujuanUtama', $unitId);

		$this->db->group_by('srtId');
		$query = $this->db->get('surat');
		if ($query->num_rows() > 0) return $query->row_array();
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

	function surat_balasan($id)
	{
		$this->db->select("srtId as id
			,srtNomorSurat as nomor
			,srtPerihal as perihal
			,srtFile as file
			,sifdisNama as sifat
			,srtTglDraft as tanggal
			,srtIsiRingkasan as ringkasan
			,stNama as status
			,stColor as color
			,CONCAT(jnsrtKode,' - ',jnsrtNama) as jenis_surat
		");
		$this->db->join('surat', 'srtId=surefSuratId');
		$this->db->join('sifat_disposisi', 'sifdisId=srtSifatSurat');
		$this->db->join('jenis_surat', 'jnsrtId=srtJenisSuratId');
		$this->db->join('surat_status_ref', 'stId=srtStatusId');
		$this->db->where('surefSuratRefId', $id);
		$query = $this->db->get('surat_ref_surat');
		if ($query->num_rows() > 0) return $query->result_array();
		return NULL;
	}
}
