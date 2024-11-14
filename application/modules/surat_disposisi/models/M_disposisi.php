<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_disposisi extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function get_list_($params = array(), $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL)
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
			,disunitTglBaca as baca
			,IF(srtAsalSurat is not null, srtAsalSurat,UnitName) as asal
			,dispId as dispoId
			,disunitUnitId as unitId
		");

		$this->db->join('sys_unit', 'UnitId=srtUnitAsalId', 'LEFT');
		$this->db->join('surat_disposisi', 'dispSuratId=srtId', 'LEFT');
		$this->db->join('sifat_disposisi', 'sifdisId=dispSifatDisposisiId', 'LEFT');
		$this->db->join("surat_disposisi_unit", "disunitDisposisiId=dispId AND disunitUnitId = $unit", "LEFT");

		$this->db->where_in('srtStatusId', [3, 4]);
		if ($params['isbaca'] == '1') {
			$this->db->where('disunitTglBaca IS NOT NULL');
		}

		if ($params['isbaca'] == '0') {
			$this->db->where('disunitTglBaca IS NULL');
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
		$this->db->where('dispIsBatal', 0);
		$this->db->where('diunitIsBatal', 0);

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
			,dispTglCreate as tanggal
			,disunitTglBaca as baca
			,IF(srtAsalSurat is not null, srtAsalSurat,UnitName) as asal
			,dispId as dispoId
			,disunitUnitId as unitId
		");

		$this->db->join('surat_disposisi', 'dispId=disunitDisposisiId', 'LEFT');
		$this->db->join('sifat_disposisi', 'sifdisId=dispSifatDisposisiId', 'LEFT');
		$this->db->join('surat', 'srtId=dispSuratId', 'LEFT');
		$this->db->join('sys_unit', 'UnitId=srtUnitAsalId', 'LEFT');
		// $this->db->join("surat_disposisi_unit", "disunitDisposisiId=dispId AND disunitUnitId = $unit", "LEFT");

		if ($params['isbaca'] == '1') {
			$this->db->where("disunitTglBaca !=''");
		}

		if ($params['isbaca'] == '0') {
			$this->db->where("disunitTglBaca IS NULL");
		}

		$this->db->where('disunitUnitId', $unit);
		$this->db->where('dispIsBatal', 0);
		$this->db->where('diunitIsBatal', 0);

		$this->db->where_in('srtStatusId', [3, 4]);

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
		$this->db->where('dispIsBatal', 0);
		$this->db->where('diunitIsBatal', 0);

		// $this->db->group_by('srtId');
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
			$query = $this->db->get("surat_disposisi_unit");
			if ($query->num_rows() > 0) return $query;
			return NULL;
		} else if ($status == 'counter') {
			return $this->db->count_all_results("surat_disposisi_unit");
		}
	}

	function list_disposisi_staff($params = array(), $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL)
	{
		$tanggal = $params['tanggal'];
		$kategori = $params['kategori'];

		$this->db->select("srtId as id
			,srtNomorSurat as nomor
			,srtPerihal as perihal
			,dispCatatan as catatan
			,srtFile as file
			,sifdisNama as sifat
			,dispTglCreate as tanggal
			,dispTglBaca as baca
			,IF(srtAsalSurat is not null, srtAsalSurat,UnitName) as asal
			,dispId as dispoId
		");

		// $this->db->join('surat_disposisi', 'dispId=disunitDisposisiId', 'LEFT');
		$this->db->join('sifat_disposisi', 'sifdisId=dispSifatDisposisiId', 'LEFT');
		$this->db->join('surat', 'srtId=dispSuratId', 'LEFT');
		$this->db->join('sys_unit', 'UnitId=srtUnitAsalId', 'LEFT');
		$this->db->where('dispIsBatal', 0);
		if ($params['isbaca'] == '1') {
			$this->db->where("dispTglBaca !=''");
		}

		if ($params['isbaca'] == '0') {
			$this->db->where("dispTglBaca IS NULL");
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

		$this->db->order_by('dispTglCreate ASC, dispTglBaca ASC');
		if (!is_null($object)) {
			foreach ($object as $row => $val) {
				if (preg_match("/(<=|>=|=|<|>)(\s*)(.+)/i", trim($val), $matches)) {
					$this->db->where($row . ' ' . $matches[1], $matches[3]);
				} elseif ($row == 'filter_key') {
					$where = "(srtNomorSurat LIKE '%" . $val . "%' or srtAsalSurat LIKE '%" . $val . "%' or srtPerihal LIKE '%" . $val . "%')";
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
			$query = $this->db->get("surat_disposisi");
			if ($query->num_rows() > 0) return $query;
			return NULL;
		} else if ($status == 'counter') {
			return $this->db->count_all_results("surat_disposisi");
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
			,IF(srtAsalSurat is not null, srtAsalSurat,su.UnitName) as asal
			,IF(srtAsalSurat is not null, 'Eksternal','Internal') as asal_surat
			,sifdisNama as sifat
			,dispCatatan as catatan_dispo
			,dispTujuan as tujuan
			,IF(sunt.UnitName is not null, sunt.UnitName,dispUserCreate) as asal_dispo
			,disunitTglBaca as baca
			,disunitCatatan as catatan_instruksi
			,insNama as instruksi
			,disunitInstruksiId as instruksiId
			,dispTglCreate as tgl_dispo
		");

		$this->db->join('sys_unit as su', 'su.UnitId=srtUnitAsalId', 'LEFT');
		$this->db->join('surat_disposisi', 'dispSuratId=srtId', 'LEFT');
		$this->db->join('sifat_disposisi', 'sifdisId=dispSifatDisposisiId', 'LEFT');
		$this->db->join("surat_disposisi_unit", "disunitDisposisiId=dispId", "LEFT");
		$this->db->join('sys_user', 'UserId=dispUserId', 'LEFT');
		$this->db->join('sys_unit as sunt', 'sunt.UnitId=UserUnitId', 'LEFT');
		$this->db->join('instruksi_disposisi', 'disunitInstruksiId=instId', 'LEFT');

		$this->db->where('dispId', $id);
		$this->db->where('disunitUnitId', $unitId);
		$this->db->where('diunitIsBatal', 0);

		// $this->db->group_by('srtId');
		$query = $this->db->get('surat');
		if ($query->num_rows() > 0) return $query->row_array();
		return NULL;
	}

	function detail_staff($id)
	{
		$this->db->select("
			srtId as id
			,srtNomorSurat as nomor
			,srtPerihal as perihal
			,srtFile as file
			,srtTglDraft as tanggal
			,srtIsiRingkasan as ringkasan
			,IF(srtAsalSurat is not null, srtAsalSurat,su.UnitName) as asal
			,IF(srtAsalSurat is not null, 'Eksternal','Internal') as asal_surat
			,sifdisNama as sifat
			,dispCatatan as catatan_dispo
			,dispTujuan as tujuan
			,UserRealName as asal_dispo
			,dispTglBaca as baca
			,dispTglCreate as tgl_dispo
		");
		$this->db->where('dispId', $id);
		$this->db->join('surat', 'srtId=dispSuratId', 'LEFT');
		$this->db->join('sys_unit as su', 'su.UnitId=srtUnitAsalId', 'LEFT');
		$this->db->join('sifat_disposisi', 'sifdisId=dispSifatDisposisiId', 'LEFT');
		$this->db->join("surat_disposisi_unit", "disunitDisposisiId=dispId", "LEFT");
		$this->db->join('sys_user', 'UserName=dispUserCreate', 'LEFT');
		$query = $this->db->get('surat_disposisi');
		if ($query->num_rows() > 0) return $query->row_array();
		return NULL;
	}

	function baca_disposisi($data)
	{
		$this->db->trans_begin();

		if ($data['tipe'] == 'unit') {
			$this->db->where('disunitDisposisiId', $data['id']);
			$this->db->where('disunitUnitId', $data['unit']);
			$this->db->where('disunitInstruksiId', $data['instruksi']);
			$this->db->update('surat_disposisi_unit', $data['data']);
		} elseif ($data['tipe'] == 'staff') {
			$this->db->where('dispId', $data['id']);
			$this->db->update('surat_disposisi', array('dispTglBaca' => date('Y-m-d H:i:s')));
		}

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
