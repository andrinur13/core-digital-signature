<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_surat extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function get_list($params = array(), $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL)
	{
		$tanggal = $params['tanggal'];
		$tanggal_akhir = $params['tanggal_akhir'];
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
			,tindId as tindakanId
			,surefSuratRefId as balas_draft
		");

		$this->db->join('sys_unit', 'UnitId=srtUnitAsalId', 'LEFT');
		$this->db->join('sifat_disposisi', 'sifdisId=srtSifatSurat', 'LEFT');
		$this->db->join('surat_tindakan', 'tindSuratId=srtId', 'LEFT');
		$this->db->join('surat_ref_surat', 'surefSuratRefId=srtId', 'LEFT');

		$this->db->where('srtIsSigned', 1);
		if ($params['isbaca'] == '1') {
			$this->db->where('srtUserBaca IS NOT NULL');
		}

		if ($params['isbaca'] == '0') {
			$this->db->where('srtUserBaca IS NULL');
		}

		if ($params['tanggal'] != '') {
			$this->db->where("srtTglDraft BETWEEN '$tanggal' AND '$tanggal_akhir'");
		}

		// if ($params['tanggal'] != '') {
		// 	$this->db->where("srtTglDraft BETWEEN '$tanggal 00:00:00' AND '$tanggal 23:59:59'");
		// }

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

	function get_disposisi($object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL)
	{
		$this->db->select("dispId as id
			,sifdisNama as sifat
			,dispCatatan as catatan
			,dispTujuan as tujuan
			,GROUP_CONCAT(distinct '- ', UnitName SEPARATOR '<br>') as unit
			,UserRealName as staff
		");

		$this->db->join("surat_disposisi_unit", "disunitDisposisiId=dispId AND diunitIsBatal = 0", "LEFT");
		$this->db->join('sys_unit', 'UnitId=disunitUnitId', 'LEFT');
		$this->db->join('sys_user', 'UserId=dispUserId', 'LEFT');
		$this->db->join('instruksi_disposisi', 'disunitInstruksiId=instId', 'LEFT');
		$this->db->join('sifat_disposisi', 'sifdisId=dispSifatDisposisiId', 'LEFT');

		$this->db->where('dispIsBatal', 0);

		$this->db->group_by('dispId', 'dispSuratId');
		// $this->db->group_by('dispId');

		if (!is_null($object)) {
			foreach ($object as $row => $val) {
				if (preg_match("/(<=|>=|=|<|>)(\s*)(.+)/i", trim($val), $matches)) {
					$this->db->where($row . ' ' . $matches[1], $matches[3]);
				} elseif ($row == 'filter_key') {
					// $where = "(sifdisNama LIKE '%" . $val . "%')";
					$where = "(sifdisNama LIKE '%" . $val . "%' or UnitName LIKE '%" . $val . "%' or dispTujuan LIKE '%" . $val . "%')";
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

	function detail_disposisi($id)
	{
		$this->db->select("dispId as id
			,dispSuratId as suratId
			,dispSifatDisposisiId as sifat
			,sifdisNama as sifat_nama
			,dispCatatan as catatan
			,dispTujuan as tujuan
			,srtTglDraft as tanggal
			,srtNomorSurat as nomor
			,srtPerihal as perihal
			,IF(srtAsalSurat is not null, srtAsalSurat,UnitName) as asal
			,UserRealName as staff
			,dispTglBaca as tgl_baca
			,IF(dispTglBaca IS NULL,'Belum','Sudah') as isbaca
		");
		$this->db->join('surat', 'srtId=dispSuratId');
		$this->db->join('sys_unit', 'UnitId=srtUnitAsalId', 'LEFT');
		$this->db->join('sifat_disposisi', 'sifdisId=dispSifatDisposisiId', 'LEFT');
		$this->db->join('sys_user', 'UserId=dispUserId', 'LEFT');

		$this->db->where('dispId', $id);
		$query = $this->db->get('surat_disposisi');
		if ($query->num_rows() > 0) return $query->row_array();
		return NULL;
	}

	function detail_disposisi_staff($id)
	{
		$this->db->select("dispId as id
			,dispSuratId as suratId
			,UserRealName as staff
			,UserId as staffId
			,dispCatatan as catatan
			,sifdisNama as sifat_nama
			,dispSifatDisposisiId as sifatId
			,dispTglBaca as tgl_baca
			,dispIsBatal as isbatal
			,IF(dispTglBaca IS NULL,'Belum','Sudah') as isbaca
		");
		$this->db->join('sys_user', 'UserId=dispUserId', 'LEFT');
		$this->db->join('sifat_disposisi', 'sifdisId=dispSifatDisposisiId', 'LEFT');
		$this->db->where('dispId', $id);
		$query = $this->db->get('surat_disposisi');
		if ($query->num_rows() > 0) return $query->row_array();
		return NULL;
	}

	function detail_disposisi_staffs($id)
	{
		$this->db->select("dispId as id
			,dispSuratId as suratId
			,UserRealName as staff
			,UserId as staffId
			,dispCatatan as catatan
			,sifdisNama as sifat_nama
			,dispSifatDisposisiId as sifatId
			,dispTglBaca as tgl_baca
			,dispIsBatal as isbatal
			,IF(dispTglBaca IS NULL,'Belum','Sudah') as isbaca
		");
		$this->db->join('sys_user', 'UserId=dispUserId', 'LEFT');
		$this->db->join('sifat_disposisi', 'sifdisId=dispSifatDisposisiId', 'LEFT');
		$this->db->where('dispSuratId', $id);
		$this->db->where('dispUserId IS NOT NULL');
		$this->db->where('dispIsBatal', 0);
		$query = $this->db->get('surat_disposisi');
		if ($query->num_rows() > 0) return $query->result_array();
		return NULL;
	}

	function detail_disposisi_units($id)
	{
		$this->db->select("disunitDisposisiId as id
			,disunitUnitId as unitId
			,UnitName as unit
			,disunitInstruksiId as instruksiId
			,insNama as instruksi
			,disunitCatatan as catatan
			,disunitTglBaca as tgl_baca
			,diunitIsBatal as isbatal
			,IF(disunitTglBaca IS NULL,'Belum','Sudah') as isbaca
		");
		$this->db->join('sys_unit', 'UnitId=disunitUnitId', 'LEFT');
		$this->db->join('instruksi_disposisi', 'disunitInstruksiId=instId', 'LEFT');
		$this->db->where('disunitDisposisiId', $id);
		$this->db->where('diunitIsBatal', 0);
		$query = $this->db->get('surat_disposisi_unit');
		if ($query->num_rows() > 0) return $query->result_array();
		return NULL;
	}

	function detail_disposisi_unit($id, $unitId)
	{
		$this->db->select("disunitDisposisiId as id
			,disunitUnitId as unitId
			,UnitName as unit
			,disunitInstruksiId as instruksiId
			,insNama as instruksi
			,disunitCatatan as catatan
			,disunitTglBaca as tgl_baca
			,diunitIsBatal as isbatal
			,IF(disunitTglBaca IS NULL,'Belum','Sudah') as isbaca
		");
		$this->db->join('sys_unit', 'UnitId=disunitUnitId', 'LEFT');
		$this->db->join('instruksi_disposisi', 'disunitInstruksiId=instId', 'LEFT');
		$this->db->where('disunitDisposisiId', $id);
		$this->db->where('disunitUnitId', $unitId);
		$query = $this->db->get('surat_disposisi_unit');
		if ($query->num_rows() > 0) return $query->row_array();
		return NULL;
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
			,IF(srtAsalSurat is not null, srtAsalSurat,srtUnitAsalId) as id_asal_surat
		");

		$this->db->join('sys_unit', 'UnitId=srtUnitAsalId', 'LEFT');
		$this->db->join('sifat_disposisi', 'sifdisId=srtSifatSurat', 'LEFT');
		$this->db->join("arsip", "arsSuratId=srtId AND arsTujuanUnitId = $unitId", "LEFT");
		$this->db->join('surat_disposisi', 'srtId=dispId', 'LEFT');
		$this->db->join('klasifikasi', 'klasId=srtKlasifikasiId');
		$this->db->join('jenis_surat', 'jnsrtId=srtJenisSuratId');
		$this->db->join('berkas', 'brksId=arsBerkasId', 'LEFT');
		$this->db->join('jenis_eksemplar', 'eksId=arsJenisEksemplarId', 'LEFT');

		$this->db->where('srtId', $id);
		$this->db->where('srtUnitTujuanUtama', $unitId);

		$this->db->group_by('srtId');
		$query = $this->db->get('surat');
		if ($query->num_rows() > 0) return $query->row_array();
		return NULL;
	}

	function arahan_surat($id)
	{

		$this->db->select("logId as id, logCatatan as catatan, logPjbUserArahan as pejabat, logPjbId as pejabatId, logPjbIsDibaca as isBaca, logPjbTglBaca as tglBaca, logPjbArahan as jawaban");
		$this->db->where('logSuratId', $id);
		$this->db->where('logCatatan IS NOT NULL');
		$query = $this->db->get('surat_log');
		if ($query->num_rows() > 0) return $query->result_array();
		return NULL;
	}


	function ref_pejabat()
	{
		$this->db->select("UserId as id, UserRealName as nama");
		$this->db->join('sys_user_group', 'UserId=UserGroupUserId AND UserGroupGroupId = 4');
		// $this->db->where('UserRoleId', 1);
		$query = $this->db->get('sys_user');
		if ($query->num_rows() > 0) return $query->result_array();
		return NULL;
	}

	function user_tindakan()
	{
		$this->db->select("UserId as id, UserRealName as nama");
		$this->db->join('sys_user_group', 'UserId=UserGroupUserId AND UserGroupGroupId IN (4,5)');
		$query = $this->db->get('sys_user');
		if ($query->num_rows() > 0) return $query->result_array();
		return NULL;
	}

	function tanggapan_surat($id)
	{
		$this->db->select("tindId as id, tindSuratId as suratId,tindCatatan as catatan,jnstindNama as tindakan, UserRealName as nama, UserId, tindJenisTindakanId as tindakanId");
		$this->db->join('jenis_tindakan', 'jnstindId=tindJenisTindakanId', 'LEFT');
		$this->db->join('sys_user', 'UserId=tindUserPjbId', 'LEFT');
		$this->db->where('tindSuratId', $id);
		$this->db->order_by('tindId DESC');
		$query = $this->db->get('surat_tindakan');
		if ($query->num_rows() > 0) return $query->result_array();
		return NULL;
	}

	function detail_tanggapan_surat($id)
	{
		$this->db->select("tindId as id, tindSuratId as suratId,tindCatatan as catatan,jnstindNama as tindakan, UserRealName as nama, UserId, tindJenisTindakanId as tindakanId");
		$this->db->join('jenis_tindakan', 'jnstindId=tindJenisTindakanId');
		$this->db->join('sys_user', 'UserId=tindUserPjbId', 'LEFT');
		$this->db->where('tindId', $id);
		$query = $this->db->get('surat_tindakan');
		if ($query->num_rows() > 0) return $query->row_array();
		return NULL;
	}

	function detail_arahan($id)
	{
		$this->db->select("logId as id, logSuratId as suratId, logCatatan as catatan, logPjbUserArahan as pejabat, logPjbId as pejabatId, logPjbIsDibaca as isBaca, logPjbTglBaca as tglBaca, logPjbArahan as jawaban");
		$this->db->where('logId', $id);
		$query = $this->db->get('surat_log');
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

	function ref_jenis_surat()
	{
		$this->db->select("jnsrtId as id, CONCAT(IF(jnsrtKode IS NULL,'', CONCAT(`jnsrtKode`,' - ')),jnsrtNama) as nama");
		$query = $this->db->get('jenis_surat');
		if ($query->num_rows() > 0) return $query->result_array();
		return NULL;
	}

	function ref_jenis_klasifikasi()
	{
		$this->db->select("klasId as id, CONCAT(klasKode,' - ',klasNama) as nama");
		$this->db->where('klasIsAktif', 1);
		$query = $this->db->get('klasifikasi');
		if ($query->num_rows() > 0) return $query->result_array();
		return NULL;
	}

	function ref_eksemplar()
	{
		$this->db->select("eksId as id, eksNama as nama");
		$query = $this->db->get('jenis_eksemplar');
		if ($query->num_rows() > 0) return $query->result_array();
		return NULL;
	}

	function ref_tindakan()
	{
		$this->db->select("jnstindId as id, jnstindNama as nama");
		$query = $this->db->get('jenis_tindakan');
		if ($query->num_rows() > 0) return $query->result_array();
		return NULL;
	}

	function ref_instruksi()
	{
		$this->db->select("instId as id, insNama as nama");
		$query = $this->db->get('instruksi_disposisi');
		if ($query->num_rows() > 0) return $query->result_array();
		return NULL;
	}

	function ref_berkas($unit, $klasifikasi = '')
	{
		$this->db->select("brksId as id, brksNama as nama");
		$this->db->where('brksUnitId', $unit);
		if ($klasifikasi != '') {
			$this->db->where('brksKlasifikasiId', $klasifikasi);
		}
		$query = $this->db->get('berkas');
		if ($query->num_rows() > 0) return $query->result_array();
		return NULL;
	}

	function ref_sifat()
	{
		$this->db->select("sifdisId as id, sifdisNama as nama");
		$query = $this->db->get('sifat_disposisi');
		if ($query->num_rows() > 0) return $query->result_array();
		return NULL;
	}

	function ref_unit()
	{
		$this->db->select("UnitId as id, UnitName as nama");
		$query = $this->db->get('sys_unit');
		if ($query->num_rows() > 0) return $query->result_array();
		return NULL;
	}

	function detail_unit($id)
	{
		$this->db->select("UnitId as id, UnitName as nama");
		$this->db->where('UnitId', $id);
		$query = $this->db->get('sys_unit');
		if ($query->num_rows() > 0) return $query->row_array();
		return NULL;
	}

	function staff_unit($id)
	{
		$this->db->select("UserId AS id,UserRealName AS nama");
		$this->db->where('UserUnitId', $id);
		$this->db->where('UserRoleId', 0);
		$query = $this->db->get('sys_user');
		if ($query->num_rows() > 0) return $query->result_array();
		return NULL;
	}

	function ref_kolom($id)
	{
		$this->db->select("kolId as id, jnsurkolId as jenis_kol_id, kolNama as nama,kolTipe as tipe, kolVariable as variabel");
		$this->db->join('jenis_surat_kolom', 'jnsurkolKolomId=kolId');
		$this->db->where('jnsurkolJenisSuratId', $id);
		$this->db->order_by('koltipe', 'text', 'DESC');
		$query = $this->db->get('kolom_ref');
		if ($query->num_rows() > 0) return $query->result_array();
		return NULL;
	}

	// proses

	function add($params, $arsip, $log, $surat_kolom)
	{
		$this->db->trans_begin();

		#insert surat
		$this->db->insert('surat', $params);
		$id = $this->db->insert_id();

		#insert arsip
		if ($arsip != '') {
			$arsip['arsSuratId'] = $id;
			$this->db->insert('arsip', $arsip);
		}

		#insert log
		$log['logSuratId'] = $id;
		$this->db->insert('surat_log', $log);

		#insert log status
		/*
		$this->db->insert('surat_log_status', array(
			'logsSrtId' => $id,
			'logsStatusId' => 3,
			'logsUserUpdate' => get_user_name(),
			'logsTglUpdate' => date('Y-m-d H:i:s'),
		));
		*/

		#insert surat kolom
		if ($surat_kolom != '') {
			foreach ($surat_kolom as $val) {
				$this->db->insert('surat_kolom', array(
					'surkolJnsSuratKolomId' => $val['surkolJnsSuratKolomId'],
					'surkolKonten' => $val['surkolKonten'],
					'surkolSuratId' => $id
				));
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
			$this->db->close();
		} else {
			$this->db->trans_commit();
			return array('status' => TRUE);
			$this->db->close();
		}
	}

	function update($id, $params, $arsip, $is_arsip)
	{
		$this->db->trans_begin();

		#update surat
		$this->db->where('srtId', $id);
		$this->db->update('surat', $params);

		#update arsip
		if ($arsip != '' && $is_arsip != '') {
			$this->db->where('arsSuratId', $id);
			$this->db->update('arsip', $arsip);
		}

		#insert arsip
		if ($arsip != '' && $is_arsip == '') {
			$arsip['arsSuratId'] = $id;
			$this->db->insert('arsip', $arsip);
		}

		#update log surat
		/*
		$this->db->where('logSuratId', $id);
		$this->db->update('surat_log', array(
			'logUserUpdate' => get_user_name(),
			'logTglUpdate' => date('Y-m-d H:i:s'),
		));
		*/

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

	function hapus_surat($id)
	{
		$this->db->trans_begin();
		$this->db->where('srtId', $id);
		$this->db->update('surat', array(
			'srtIsDelete' => 1,
			'srtUserDelete' => get_user_name(),
			'srtTglDelete' => date('Y-m-d H:i:s')
		));

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

	function proses_disposisi($data)
	{
		$this->db->trans_begin();
		$id = '';

		#insert catatan dan sifat disposisi (unit)
		if ($data['mode'] == 'add') {
			if ($data['data'] != '') {
				$this->db->insert('surat_disposisi', $data['data']);
				$id = $this->db->insert_id();
			}
			/*
				if ($data['dispo_unit'] != '') {
					foreach ($data['dispo_unit'] as $val) {
						$this->db->insert('surat_disposisi_unit', array(
							'disunitDisposisiId' => $id,
							'disunitUnitId' => $val['disunitUnitId'],
							'disunitInstruksiId' => $val['disunitInstruksiId'],
							'disunitCatatan' => $val['disunitCatatan'],
						));
					}
				}
			*/
		}

		#update catatan dan sifat disposisi (unit)
		if ($data['mode'] == 'update') {
			$this->db->where('dispId', $data['id']);
			$this->db->update('surat_disposisi', $data['data']);
		}

		#insert disposisi unit
		if ($data['dispo_unit'] != '') {
			foreach ($data['dispo_unit'] as $val) {
				$this->db->insert('surat_disposisi_unit', array(
					'disunitDisposisiId' => ($data['mode'] == 'add') ? $id : $data['id'],
					'disunitUnitId' => $val['disunitUnitId'],
					'disunitInstruksiId' => $val['disunitInstruksiId'],
					'disunitCatatan' => $val['disunitCatatan'],
				));
			}
		}

		#insert disposisi staff
		if ($data['dispo_staff'] != '') {
			foreach ($data['dispo_staff'] as $val) {
				$this->db->insert('surat_disposisi', array(
					'dispSuratId' => $val['dispSuratId'],
					'dispUserId' => $val['dispUserId'],
					'dispSifatDisposisiId' => $val['dispSifatDisposisiId'],
					'dispCatatan' => $val['dispCatatan'],
					'dispUserCreate' => $val['dispUserCreate'],
					'dispTglCreate' => $val['dispTglCreate'],
				));
			}
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

	function update_disposisi_staff($data)
	{
		$this->db->trans_begin();
		$this->db->where('dispId', $data['id']);
		$this->db->update('surat_disposisi', $data['data']);

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

	function update_disposisi_unit($data)
	{
		$this->db->trans_begin();

		$this->db->select('disunitUnitId as unitId');
		$this->db->where('disunitDisposisiId', $data['id']);
		$this->db->where('disunitUnitId', $data['unitId']);
		$this->db->where('diunitIsBatal', 0);
		$query = $this->db->get('surat_disposisi_unit');

		if ($query->num_rows() > 0) {
			$unitId = $query->row_array();
			$this->db->where('disunitDisposisiId', $data['id']);
			$this->db->where('disunitUnitId', $unitId['unitId']);
			$this->db->update('surat_disposisi_unit', $data['data']);
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

	function hapus_disposisi_unit($dispoId, $unitId)
	{
		$this->db->trans_begin();
		$this->db->where('disunitDisposisiId', $dispoId);
		$this->db->where('disunitUnitId', $unitId);
		$this->db->update('surat_disposisi_unit', array(
			'diunitIsBatal' => 1
		));
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

	function hapus_disposisi($id)
	{
		$this->db->trans_begin();
		$this->db->where('dispId', $id);
		$this->db->update('surat_disposisi', array(
			'dispIsBatal' => 1
		));

		$this->db->where('disunitDisposisiId', $id);
		$this->db->update('surat_disposisi_unit', array(
			'diunitIsBatal' => 1
		));
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

	function set_arahan($data)
	{
		$this->db->trans_begin();

		if ($data['mode'] == 'add') {
			$this->db->insert('surat_log', $data['data']);
		}

		if ($data['mode'] == 'update') {
			$this->db->where('logId', $data['id']);
			$this->db->update('surat_log', $data['data']);
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

	function delete_arahan($id)
	{
		$this->db->trans_begin();

		$this->db->where('logId', $id);
		$this->db->delete('surat_log');

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


	function set_tanggapan($data)
	{
		$this->db->trans_begin();

		if ($data['mode'] == 'add') {
			$this->db->insert('surat_tindakan', $data['data']);
		}

		if ($data['mode'] == 'update') {
			$this->db->where('tindId', $data['id']);
			$this->db->update('surat_tindakan', $data['data']);
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


	function delete_tanggapan($id)
	{
		$this->db->trans_begin();

		$this->db->where('tindId', $id);
		$this->db->delete('surat_tindakan');

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

	function set_arsip($data)
	{
		$this->db->trans_begin();

		if ($data['mode'] == 'add') {
			$this->db->insert('arsip', $data['data']);
		}

		if ($data['mode'] == 'update') {
			$this->db->where('arsId', $data['id']);
			$this->db->update('arsip', $data['data']);
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

	function baca_surat($data)
	{
		$this->db->trans_begin();

		$this->db->where('srtId', $data['id']);
		$this->db->update('surat', $data['data']);

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

	function add_draft($data)
	{
		$this->db->trans_begin();

		#insert surat
		$this->db->insert('surat', $data['surat']);
		$id = $this->db->insert_id();

		#insert surat_ref_surat
		$this->db->insert('surat_ref_surat', array(
			'surefSuratId' => $id,
			'surefSuratRefId' => $data['surat_ref']['surefSuratRefId'],
			'surefArsipRefId' => ($data['surat_ref']['surefArsipRefId'] == '') ? NULL : $data['surat_ref']['surefArsipRefId'],
		));

		#insert log
		$data['log']['logSuratId'] = $id;
		$this->db->insert('surat_log', $data['log']);

		#insert log status
		$data['log_status']['logsSrtId'] = $id;
		$this->db->insert('surat_log_status', $data['log_status']);

		#insert surat kolom
		if ($data['surat_kolom'] != '') {
			foreach ($data['surat_kolom'] as $val) {
				$this->db->insert('surat_kolom', array(
					'surkolJnsSuratKolomId' => $val['surkolJnsSuratKolomId'],
					'surkolKonten' => $val['surkolKonten'],
					'surkolSuratId' => $id
				));
			}
		}

		#insert surat tembusan internal
		if ($data['tembusan_internal'] != '') {
			foreach ($data['tembusan_internal'] as $val) {
				$this->db->insert('surat_tembusan', array(
					'tembUnitId' => $val['tembUnitId'],
					'tembSuratId' => $id
				));
			}
		}

		#insert surat tembusan eksternal
		if ($data['tembusan_eksternal'] != '') {
			foreach ($data['tembusan_eksternal'] as $key => $val) {
				$this->db->insert('surat_tembusan', array(
					'tembTujuanEksternal' => $val,
					'tembSuratId' => $id
				));
			}
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
