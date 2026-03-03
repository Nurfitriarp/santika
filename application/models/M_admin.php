<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_admin extends CI_Model {

    public function get_data() {
        // Ganti 'nama_tabel' dengan nama tabel asli di phpMyAdmin Anda
        return $this->db->get('tbl_kegiatan')->result();
    }
    
    public function get_detail($id) {
        // Ambil detail kegiatan berdasarkan ID
        $this->db->where('ID_KEGIATAN', $id);
        $this->db->select('tbl_kegiatan.*, tbl_opd.NAMA_OPD');
        $this->db->from('tbl_kegiatan');
        $this->db->join('tbl_opd', 'tbl_opd.ID_OPD = tbl_kegiatan.ID_OPD', 'left'); // Menggabungkan tabel
        return $this->db->get()->row();
    }
    
    public function get_peserta($id_kegiatan) {
        // Ambil list peserta berdasarkan ID_KEGIATAN
        return $this->db->where('ID_KEGIATAN', $id_kegiatan)->get('tbl_login')->result();
    }

    public function search_kegiatan($keyword)
    {
        return $this->db->like('NAMA', $keyword, 'both')
                        ->get('tbl_kegiatan')
                        ->result();
    }

    public function get_opd()
    {
        return $this->db->select('ID_OPD, NAMA_OPD')
                        ->order_by('NAMA_OPD', 'asc')
                        ->get('tbl_opd')
                        ->result();
    }

    public function insert_kegiatan($data)
    {
        $insert = [
            'NAMA' => isset($data['NAMA']) ? $data['NAMA'] : null,
            'TEMPAT' => isset($data['TEMPAT']) ? $data['TEMPAT'] : null,
            'JAM' => isset($data['JAM']) ? $data['JAM'] : null,
            'TANGGAL' => isset($data['TANGGAL']) ? $data['TANGGAL'] : null,
            'SKPD_PENYELENGGARA' => isset($data['SKPD_PENYELENGGARA']) ? $data['SKPD_PENYELENGGARA'] : null,
            'PIMPINAN_RAPAT' => isset($data['PIMPINAN_RAPAT']) ? $data['PIMPINAN_RAPAT'] : null,
            'ID_OPD' => isset($data['ID_OPD']) ? $data['ID_OPD'] : 0,
            'JML_PESERTA' => isset($data['JML_PESERTA']) ? $data['JML_PESERTA'] : 0,
            'STS' => isset($data['STS']) ? $data['STS'] : 0,
            'SERTIFIKAT' => isset($data['SERTIFIKAT']) ? $data['SERTIFIKAT'] : 0,
            'JAM_PELAJARAN' => isset($data['JAM_PELAJARAN']) ? $data['JAM_PELAJARAN'] : null,
        ];
        
        $ok = $this->db->insert('tbl_kegiatan', $insert);
        if ($ok) {
            return $this->db->insert_id();
        }
        return false;
    }
}