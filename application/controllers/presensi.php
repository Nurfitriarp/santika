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

        // 2. Cari data peserta terakhir jika ada identifier (No HP atau Email)
        $peserta_lama = null;
        if (!empty($identifier)) {
            $peserta_lama = $this->db->order_by('ID_LOGIN', 'DESC')
                                    ->get_where('tbl_presensi', ['NAMA' => $identifier])
                                    ->row();
        }

        $data['kegiatan'] = $kegiatan;
        $data['opd'] = $this->db->get('tbl_opd')->result();
        $data['lama'] = $peserta_lama; // Kirim data lama ke view

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
            'ID_OPD'      => $input['ID_OPD'],
            'JABATAN'     => $input['JABATAN'],
            'TTD'         => $input['TTD']
        ];

        if($this->db->insert('tbl_presensi', $data_hadir)) {
            $this->session->set_flashdata('sukses_presensi', 'Terima kasih, data kehadiran Anda telah kami simpan.');
            redirect('presensi/sukses');
        }
    }

    public function sukses() {
        $this->load->view('publik/sukses_presensi');
    }

} // <-- Ini adalah penutup CLASS, harus berada di paling bawah file