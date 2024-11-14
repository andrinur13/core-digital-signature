<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_tembusan extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function get_list($params = array(), $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL)
	{
		$tanggal = $params['tanggal'];
		$kategori = $params['kategori'];
		$unit = $params['unit'];
		$this->db->select("srtId as id
			,srtNomorSurat as nomor
			,srtPerihal as perihal
			,srtFile as file
			,sifdisNama as sifat
			,srtTglDraft as tanggal
			,IF(srtAsalSurat is not null, srtAsalSurat,UnitName) as asal
		");

		$this->db->join('sys_unit', 'UnitId=srtUnitAsalId', 'LEFT');
		$this->db->join('sifat_disposisi', 'sifdisId=srtSifatSurat', 'LEFT');
		$this->db->join("surat_tembusan", "tembSuratId=srtId AND tembUnitId = $unit");

		// $this->db->where_in('srtStatusId', [3, 4]);
		$this->db->where('srtIsSigned', 1);

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

	function detail($id, $unitId)
	{
		$unitId = get_user_unit_id();
		$this->db->select("
			srtId as id
			,srtNomorSurat as nomor
			,srtPerihal as perihal
			,srtFile as file
			,srtTglDraft as tanggal
			,srtIsiRingkasan as ringkasan
			,IF(srtAsalSurat is not null, srtAsalSurat,UnitName) as asal
			,IF(srtAsalSurat is not null, 'Eksternal','Internal') as asal_surat
			,IF(srtAsalSurat is not null, srtAsalSurat,'srtUnitAsalId') as id_asal_surat
			,sifdisNama as sifat
			,CONCAT(klasKode,' - ',klasNama) as klasifikasi
			,CONCAT(jnsrtKode,' - ',jnsrtNama) as jenis_surat
			,arsJenisEksemplarId as eksemplar
			,eksNama as eksemplar_nama
			,arsBerkasId as berkas
			,brksNama as berkas_nama
			,arsId as is_arsip
		");

		$this->db->join('surat', 'srtId=tembSuratId');
		$this->db->join('sys_unit', 'UnitId=srtUnitAsalId', 'LEFT');
		$this->db->join('sifat_disposisi', 'sifdisId=srtSifatSurat', 'LEFT');
		$this->db->join('klasifikasi', 'klasId=srtKlasifikasiId');
		$this->db->join('jenis_surat', 'jnsrtId=srtJenisSuratId');
		$this->db->join("arsip", "arsSuratId=srtId AND arsTujuanUnitId = $unitId", "LEFT");
		$this->db->join('berkas', 'brksId=arsBerkasId', 'LEFT');
		$this->db->join('jenis_eksemplar', 'eksId=arsJenisEksemplarId', 'LEFT');

		$this->db->where('tembSuratId', $id);
		$this->db->where('tembUnitId', $unitId);

		// $this->db->group_by('srtId');
		$query = $this->db->get('surat_tembusan');
		if ($query->num_rows() > 0) return $query->row_array();
		return NULL;
	}
}
