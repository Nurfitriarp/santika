<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Presensi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->database();
    }

    public function isi($token, $identifier = '') {
        // 1. Cari kegiatan berdasarkan token
        $kegiatan = $this->db->get_where('tbl_kegiatan', ['qr_token' => $token])->row();

        if (!$kegiatan) {
            die("Link Presensi Tidak Valid.");
        }

        // 2. Cari data peserta terakhir jika ada identifier
        $peserta_lama = null;
        if (!empty($identifier)) {
            $peserta_lama = $this->db->order_by('ID_LOGIN', 'DESC')
                                    ->get_where('tbl_presensi', ['NAMA' => $identifier])
                                    ->row();
        }

        $data['kegiatan'] = $kegiatan;
        $data['opd'] = $this->db->get('tbl_opd')->result();
        // --- AMBIL DATA KATEGORI UNTUK DROPDOWN ---
        $data['jenis_opd'] = $this->db->get('tbl_jenis_opd')->result(); 
        // ------------------------------------------
        $data['lama'] = $peserta_lama;

        $this->load->view('publik/form_presensi', $data);
    }

    public function kirim() {
    $input = $this->input->post();
    
    $data_hadir = [
        'ID_KEGIATAN' => $input['ID_KEGIATAN'],
        'NAMA'        => $input['NAMA'],
        'JEN_KEL'     => $input['JEN_KEL'],
        'NO_HP'       => $input['NO_HP'],
        'EMAIL'       => $input['EMAIL'],
        'ID_OPD'      => $input['ID_OPD'], // Langsung ambil nilainya
        'JABATAN'     => $input['JABATAN'],
        'TTD'         => $input['TTD']
    ];

    if($this->db->insert('tbl_presensi', $data_hadir)) {
        $this->session->set_flashdata('sukses_presensi', 'Terima kasih, data kehadiran Anda telah kami simpan.');
        redirect('presensi/sukses');
    }
}

    public function get_peserta_by_nama() {
    // 1. Ambil input term
    $term = $this->input->get('term', TRUE);
    
    if (empty($term)) {
        echo json_encode([]);
        return;
    }

    // 2. Kueri manual untuk memastikan kolom KAPITAL terbaca
    $sql = "SELECT NAMA, JEN_KEL, NO_HP, EMAIL, JABATAN 
            FROM tbl_presensi 
            WHERE NAMA LIKE ? 
            GROUP BY NAMA 
            LIMIT 10";
    
    $query = $this->db->query($sql, array('%' . $term . '%'));

    // 3. Kirim hasil sebagai JSON murni
    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode($query->result_array()));
}

    public function sukses() {
        $this->load->view('publik/sukses_presensi');
    }

}