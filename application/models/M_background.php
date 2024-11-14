<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_background extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function list_berkas($id, $unitId)
	{
		$this->db->select("brksId as id");
		$this->db->where('brksKlasifikasiId', $id);
		$this->db->where('brksUnitId', $unitId);
		$this->db->order_by('brksId DESC');
		$query = $this->db->get('berkas');
		if ($query->num_rows() > 0) return $query->row_array();
		return NULL;
	}

	function getDetailKlaifikasi($id)
	{
		$this->db->select("klasKode as kode,jnsklasKode as jns_kode");
		$this->db->where('klasId', $id);
		$this->db->join('klasifikasi_jenis_ref', 'jnsklasId=klasJenis', 'LEFT');
		$query = $this->db->get('klasifikasi');
		if ($query->num_rows() > 0) return $query->row_array();
		return NULL;
	}

	function detail_surat($id)
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

		$this->db->group_by('srtId');
		$query = $this->db->get('surat');
		if ($query->num_rows() > 0) return $query->row_array();
		return NULL;
	}


	function insert_berkas($data)
	{
		$this->db->trans_begin();

		$this->db->insert('berkas', $data);
		$id = $this->db->insert_id();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return array('status' => FALSE);

			$this->db->close();
		} else {
			$this->db->trans_commit();
			return array('status' => TRUE, 'id' => $id);
			$this->db->close();
		}
	}


	function set_arsip($data)
	{
		$this->db->trans_begin();

		$id = '';
		if ($data['berkas'] != '') {
			$this->db->insert('berkas', $data['berkas']);
			$id = $this->db->insert_id();
		}

		$data['arsip']['arsBerkasId'] = ($data['berkas'] != '') ? $id : $data['berkasId'];
		$this->db->insert('arsip', $data['arsip']);

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
