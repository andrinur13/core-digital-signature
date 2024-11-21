<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_Ppg extends CI_Model
{
    public function getDataPpg($limit = 25, $page = 1, $status = NULL) {
        // Calculate the offset for pagination
        $offset = ($page - 1) * $limit;

        // Start the query to get data from dokumen_ppg table
        // Apply limit and offset for pagination
        $this->db->limit($limit, $offset); 
        
        // If a status is provided, add a WHERE condition to filter by status
        if ($status !== NULL) {
            $this->db->where('status', $status);
        }

        // Execute the query
        $query = $this->db->get('dokumen_ppg');

        // Return the result set
        return $query->result();
    }

    public function getDataDetail($id) {
        $query = $this->db->where('dokumenPpgId', $id)->get('dokumen_ppg');

        if ($query->num_rows() > 0) {
            return $query->row();
        }
    
        return null;
    }


    public function getDataDetailWithDocumentId($id) {
        $query = $this->db->where('nomorDokumen', $id)->get('dokumen_ppg');

        if ($query->num_rows() > 0) {
            return $query->row();
        }
    
        return null;
    }


    public function getData() {
        $query = $this->db->where('pathDokumen is NULL')->get('dokumen_ppg');

        return $query->result();
    }

    public function update($id, $data) {
        $this->db->where('dokumenPpgId', $id);
        $this->db->update('dokumen_ppg', $data);

        $query = $this->db->where('dokumenPpgId', $id)->get('dokumen_ppg');
        if ($query->num_rows() > 0) {
            return $query->row();
        }
    
        return null;
    }

    public function insert_certificate($data) {
        return $this->db->insert('dokumen_ppg', $data); // Ganti 'ppg_certificates' dengan nama tabel Anda
    }
}