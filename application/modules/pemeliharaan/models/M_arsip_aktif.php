<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_arsip_aktif extends CI_Model
{
    function get_data( $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL ){


		$this->db->select("berkas.*, IFNULL(COUNT(DISTINCT arsId), 0) AS total_arsip, CONCAT(klasKode,' - ',klasNama,' | ',jnsklasNama) as klasifikasi");
		$this->db->join('arsip','arsBerkasId=brksId AND arsTujuan IS NULL','LEFT');
		$this->db->join('klasifikasi','klasId=brksKlasifikasiId');
        $this->db->join('klasifikasi_jenis_ref','jnsklasId=klasJenis');
		
		if(!is_null($object)) {
			foreach($object as $row => $val)
			{
				if(preg_match("/(<=|>=|=|<|>|!=)(\s*)(.+)/i", trim($val), $matches)){
					$this->db->where($row . ' ' . $matches[1], $matches[3]);
				} elseif ($row == 'filter_key') {
				  $where = "(brksNama LIKE '%" . $val . "%' AND brksNomor '%" . $val . "%' AND brksKeterangan '%" . $val . "%' )";
				  $this->db->where($where);
				} else {
					$this->db->where( $row .' LIKE', '%'.$val.'%');
				}
			}
		}	

        
		
		if(!is_null($limit) && !is_null($offset)){
			$this->db->limit($limit, $offset );
		} 

        $this->db->group_by('brksId');
		

		if(!empty($order)){
			foreach($order as $row => $val)
			{
				$ordered = (isset($val)) ? $val : 'ASC';
				$this->db->order_by($row, $val);
			}
		}

       
		
		if(is_null($status)){
			$query = $this->db->get( 'berkas' );
			if ( $query->num_rows() > 0 ) return $query;
			return NULL;
		} else if($status == 'counter'){
			return $this->db->count_all_results('berkas');
		}
	}

    function get_data_arsip( $object = array(), $limit = NULL, $offset = NULL, $order = array(), $status = NULL ){


		$this->db->select("arsId,
                        brksNama,
                        brksNomor AS nomor_berkas,
                        jnsrtNama,
                        arsPerihal AS perhial_arsip,
                        srtPerihal AS perihal_surat,
                        srtNomorSurat AS nomor_surat,
                        srtTglDraft,
                        
        ");
        
		$this->db->join('berkas','arsBerkasId=brksId');
		$this->db->join('jenis_surat','arsJenis=jnsrtId');
        $this->db->join('surat','arsSuratId=srtId');
		
		if(!is_null($object)) {
			foreach($object as $row => $val)
			{
				if(preg_match("/(<=|>=|=|<|>|!=)(\s*)(.+)/i", trim($val), $matches)){
					$this->db->where($row . ' ' . $matches[1], $matches[3]);
				} elseif ($row == 'filter_key') {
				  $where = "(arsPerihal LIKE '%" . $val . "%' AND brksNomor '%" . $val . "%' AND arsPerihal '%" . $val . "%' )";
				  $this->db->where($where);
				} else {
					$this->db->where( $row .' LIKE', '%'.$val.'%');
				}
			}
		}	
		
		if(!is_null($limit) && !is_null($offset)){
			$this->db->limit($limit, $offset );
		} 

		

		if(!empty($order)){
			foreach($order as $row => $val)
			{
				$ordered = (isset($val)) ? $val : 'ASC';
				$this->db->order_by($row, $val);
			}
		}

       
		
		if(is_null($status)){
			$query = $this->db->get( 'arsip' );
			if ( $query->num_rows() > 0 ) return $query;
			return NULL;
		} else if($status == 'counter'){
			return $this->db->count_all_results('arsip');
		}
	}   

    function get_list_arsip_internal(){

		$this->db->select("arsId,
                        jnsrtNama,
                        arsPerihal AS perhial_arsip,
                        srtPerihal AS perihal_surat,
                        srtNomorSurat AS nomor_surat,
                        srtTglDraft,
        ");
        
		$this->db->join('jenis_surat','arsJenis=jnsrtId');
        $this->db->join('surat','arsSuratId=srtId');

        $this->db->where('arsBerkasId IS NULL', null, false);

        // Ketika Surat Ini Internal
        $this->db->where('srtTujuanSurat IS NULL', null, false);
		
		
        $query = $this->db->get( 'arsip' );
        
        return $query->result();
	}   



    function get_klasifikasi($params = array()){


        $this->db->select("
            CONCAT(klasKode,' - ',klasNama,' | ',jnsklasNama) as klasifikasi,
            klasId
        ");
        
        
        $this->db->join('klasifikasi_jenis_ref','jnsklasId=klasJenis');

        $this->db->order_by('klasKode','ASC');

        $query = $this->db->get('klasifikasi');

        return $query->result();

    }

    function cari_surat_by_nomor($where){

        $this->db->select("srtId as surat_id,
                        jnsrtNama AS jenis_surat,
                        srtPerihal AS perihal_surat,
                        srtIsiRingkasan AS ringkasan,
                        klasNama AS klasifikasi,
                        srtTujuanSurat AS tujuan_surat,
                        sifdisNama AS sifat_surat_nama,
                        srtFile AS file_surat,
                        CASE
                            WHEN srtTujuanSurat IS NULL THEN 'internal'
                            ELSE 'external'
                        END AS kategori
        ");

        $this->db->like('srtNomorSurat', $where, 'both');

        $this->db->join('jenis_surat','srtJenisSuratId=jnsrtId');
        $this->db->join('klasifikasi','srtKlasifikasiId=klasId');
        $this->db->join('sifat_disposisi','srtSifatSurat=sifdisId');

        $query = $this->db->get('surat');

        return $query->row_array();
    }

    function getDetailKlaifikasi($params){
        if(!empty($params['filter'])){
            $this->db->where($params['filter']);
        }

        $this->db->join('klasifikasi_jenis_ref','jnsklasId=klasJenis');


        $query = $this->db->get('klasifikasi');

        return $query->row();
    }
    
    function getLastNumberBerkas($params){
        if(!empty($params['filter'])){
            $this->db->where($params['filter']);
        }

        $query = $this->db->get('berkas');

        return $query->row();
    }

    function getDataSurat($params){
        if(!empty($params['filter'])){
            $this->db->where($params['filter']);
        }

        $query = $this->db->get('arsip');

        return $query->row();
    }

    function get_berkas($unitId){
        $this->db->select("CONCAT(brksNomor,' - ',brksNama) as nama_berkas, brksId");

        $this->db->where('brksUnitId',$unitId);

        $query = $this->db->get('berkas');

        return $query->result();

    }

    function getDetailBerkas($brksId){
        $this->db->select("brksId,
                        brksNama,
                        klasNama,
                        IFNULL(COUNT(DISTINCT arsId), 0) AS total_arsip,
                        MIN(srtTglDraft) as tanggal_minimal,
                        MAX(srtTglDraft) as tanggal_maximal,
        ");

        $this->db->join('klasifikasi','brksKlasifikasiId=klasId');
        $this->db->join('arsip','arsBerkasId=brksId AND arsTujuan IS NULL');
        $this->db->join('surat','arsSuratId=srtId');

        $this->db->where('brksId', $brksId);

        $query = $this->db->get('berkas');

        return $query->row_array();
    }



    function get_jenis_exemplar(){
      

        $query = $this->db->get('jenis_eksemplar');

        return $query->result();
    }

    function get_detail_arsip_surat($id)
   {
      $this->db->select('srtId, srtNomorSurat, srtJenisSuratId, srtPerihal, srtIsiRingkasan, srtTglDraft, srtFile, jnsrtNama, srtUnitTujuanUtama, srtTujuanSurat, IF(srtUnitTujuanUtama!="", UnitName, srtTujuanSurat) as tujuan, sifdisNama, stNama, CONCAT(jnsklasNama," - ",klasNama) as klasifikasi, stColor, srtTglBaca');
      $this->db->select('srtKlasifikasiId,srtSifatSurat,srtStatusId');
      $this->db->join('surat','arsSuratId=srtId');
      $this->db->join('jenis_surat', 'jnsrtId=srtJenisSuratId');
      $this->db->join('surat_status_ref', 'stId=srtStatusId');
      $this->db->join('klasifikasi', 'klasId=srtKlasifikasiId');
      $this->db->join('klasifikasi_jenis_ref', 'jnsklasId=klasJenis');
      $this->db->join('sifat_disposisi', 'sifdisId=srtSifatSurat', 'left');
      $this->db->join('sys_unit', 'UnitId=srtUnitTujuanUtama');

      $this->db->where('arsId', $id);

      $query = $this->db->get('arsip');
      if ($query->num_rows() > 0) return $query->row_array();
      return NULL;
   }

    function get_data_referensi_arsip_surat($arsipId)
   {
      $this->db->select('srtId, srtNomorSurat, srtJenisSuratId, srtPerihal, srtIsiRingkasan, srtTglDraft, srtFile, jnsrtNama, srtUnitTujuanUtama, IF(srtUnitTujuanUtama!="", UnitName, srtTujuanSurat) as tujuan, sifdisNama');
      $this->db->join('surat', 'srtId=surefSuratRefId');
      $this->db->join('arsip', 'srtId=arsSuratId');
      $this->db->join('jenis_surat', 'jnsrtId=srtJenisSuratId');
      $this->db->join('klasifikasi', 'klasId=srtKlasifikasiId');
      $this->db->join('sifat_disposisi', 'sifdisId=srtSifatSurat', 'left');
      $this->db->join('sys_unit', 'UnitId=srtUnitTujuanUtama');

      $this->db->where('arsId', $arsipId);

      $query = $this->db->get('surat_ref_surat');
      if ($query->num_rows() > 0) return $query->result_array();
      return NULL;
   }
    


}
