<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_dashboard extends CI_Model
{
	function __construct()
	{
		parent::__construct();

		$ci =& get_instance();
	}


	function getCountSuratMasuk(){
		$this->db->select("COUNT(srtId) AS total_surat_masuk");

		$this->db->where('srtUnitTujuanUtama',get_user_unit_id());
		$this->db->where('srtTglBaca IS NULL', null, false);

		$query = $this->db->get('surat');
		$response = $query->row();

		return $response->total_surat_masuk;
	}
	
	function getCountSuratMasukBelumProses(){
		$this->db->select("COUNT(srtId) AS total_surat_masuk");

		$this->db->where('srtUnitTujuanUtama',get_user_unit_id());
		$this->db->where('srtPejabatPtdId IS NULL', null, false);

		$query = $this->db->get('surat');
		$response = $query->row();

		return $response->total_surat_masuk;
	}

	function getCountSuratKeluar(){
		$this->db->select("COUNT(srtId) as total_surat_keluar");

		$this->db->where('srtUnitAsalId',get_user_unit_id());

		$query = $this->db->get('surat');
		$response = $query->row();

		return $response->total_surat_keluar;
	}
}
