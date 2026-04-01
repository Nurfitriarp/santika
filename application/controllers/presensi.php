<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Presensi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->database();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function isi($token, $identifier = '') {
        $kegiatan = $this->db->get_where('tbl_kegiatan', ['qr_token' => $token])->row();
        
        if (!$kegiatan) {
            show_error("Link Presensi Tidak Valid.", 404);
        }

        // CEK STATUS AKTIF/NONAKTIF
        if ($kegiatan->STS == 0) {
            // Tampilkan pesan bahwa absensi sudah ditutup
            $data['judul'] = "Absensi Ditutup";
            $data['pesan'] = "Maaf, pengisian daftar hadir untuk kegiatan <b>".$kegiatan->NAMA."</b> telah dinonaktifkan oleh admin.";
            $this->load->view('publik/absensi_tutup', $data); // Buat file view baru ini
            return;
        }

        $data['kegiatan']  = $kegiatan;
        $data['opd']       = $this->db->get('tbl_opd')->result_array(); 
        $data['jenis_opd'] = $this->db->get('tbl_jenis_opd')->result(); 
        
        $this->load->view('publik/form_presensi', $data);
    }

    public function kirim() {
        $input = $this->input->post();
        $opd = $this->db->get_where('tbl_opd', ['ID_OPD' => $input['ID_OPD']])->row();
        $nama_skpd = ($opd) ? $opd->NAMA_OPD : '-';

        $data_simpan = [
            'ID_KEGIATAN' => $input['ID_KEGIATAN'],
            'NAMA'        => strtoupper($input['NAMA']),
            'JEN_KEL'     => $input['JEN_KEL'],
            'SKPD'        => $nama_skpd,
            'JABATAN'     => $input['JABATAN'],
            'NO_HP'       => $input['NO_HP'],
            'EMAIL'       => $input['EMAIL'],
            'TTD'         => $input['TTD'],
            'DATE_TIME'   => date('Y-m-d H:i:s'),
            'STS'         => 1,
            'URUT_CETAK'  => 0
        ];

        $this->db->insert('tbl_presensi', $data_simpan);
        $simpan_kedua = $this->db->insert('tbl_tanda_tangan', $data_simpan);

        if($simpan_kedua) {
            $this->session->set_flashdata('sukses_presensi', 'Data Anda telah berhasil tersimpan.');
            redirect('presensi/sukses');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan data.');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    // NAMA FUNGSI DISESUAIKAN UNTUK AJAX
    public function get_saran_nama() {
    $term = $this->input->get('term', TRUE);
    if (empty($term)) {
        echo json_encode([]);
        return;
    }

    // Menggunakan query builder yang lebih kompatibel dengan sql_mode
    $this->db->select('NAMA, JEN_KEL, SKPD, JABATAN, NO_HP, EMAIL');
    $this->db->from('tbl_tanda_tangan');
    $this->db->like('NAMA', $term);
    
    // Trik: Jangan gunakan GROUP BY jika sql_mode ketat. 
    // Gunakan ORDER BY DESC agar data yang paling baru yang muncul pertama.
    $this->db->order_by('ID', 'DESC'); 
    $this->db->limit(10); // Ambil 10 data terakhir yang mirip
    
    $query = $this->db->get()->result();

    // Opsional: Buat data unik di sisi PHP jika ada nama duplikat
    // Namun biasanya data terakhir sudah cukup mewakili.

    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode($query));
}

    public function sukses() {
        $this->load->view('publik/sukses_presensi');
    }
}