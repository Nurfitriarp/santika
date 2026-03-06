<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_superadmin extends CI_Model {

    public function get_data() {
        // Ambil data kegiatan dengan urutan: data terbaru diinputkan muncul pertama
        $this->db->order_by('ID_KEGIATAN', 'DESC');
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
        // Ambil list peserta berdasarkan ID_KEGIATAN dan sertakan nama OPD
        $this->db->select('tbl_presensi.*, tbl_opd.NAMA_OPD');
        $this->db->from('tbl_presensi');
        $this->db->join('tbl_opd', 'tbl_opd.ID_OPD = tbl_presensi.ID_OPD', 'left');
        $this->db->where('ID_KEGIATAN', $id_kegiatan);
        return $this->db->get()->result();
    }

    public function search_kegiatan($keyword)
    {
        // Cari di kolom NAMA, TEMPAT, atau PIMPINAN_RAPAT
        $this->db->like('NAMA', $keyword, 'both');
        $this->db->or_like('TEMPAT', $keyword, 'both');
        $this->db->or_like('PIMPINAN_RAPAT', $keyword, 'both');
        $this->db->order_by('ID_KEGIATAN', 'DESC');
        return $this->db->get('tbl_kegiatan')->result();
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
            'qr_token'      => isset($data['qr_token']) ? $data['qr_token'] : null, // QR TOKEN
        ];
        
        $ok = $this->db->insert('tbl_kegiatan', $insert);
        if ($ok) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Hapus kegiatan beserta peserta yang terkait.
     * Menggunakan transaksi untuk konsistensi data.
     */
    public function delete_kegiatan($id)
    {
        $this->db->trans_start();
        // Hapus peserta yang terkait dengan kegiatan ini (jika ada)
        $this->db->where('ID_KEGIATAN', $id)->delete('tbl_presensi');
        // Hapus kegiatan
        $this->db->where('ID_KEGIATAN', $id)->delete('tbl_kegiatan');
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function get_activity_logs($limit = 10) {
        $this->db->select('activity_logs.*, tbl_user.NAMA as nama_user'); // Kita beri alias nama_user
        $this->db->from('activity_logs');
        $this->db->join('tbl_user', 'tbl_user.ID = activity_logs.user_id', 'left');
        $this->db->order_by('activity_logs.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }
    
    // UNTUK KELOLA USER
    public function get_all_users() {
    // Mengambil semua data dari tbl_user
    return $this->db->get('tbl_user')->result();
    }

    public function get_user_by_id($id) {
    return $this->db->get_where('tbl_user', ['ID' => $id])->row();
}
}