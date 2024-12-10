<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_Validasi extends CI_Model
{
    public function getDataDetailWithDocumentId($id) {
        $query = $this->db->where('kodeEncrypt', $id)->get('dokumen_ppg');

        if ($query->num_rows() > 0) {
            return $query->row();
        }
    
        return null;
    }
}