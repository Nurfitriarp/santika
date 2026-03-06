<?php
class Presensi extends CI_Controller {
    
    public function isi($token, $nama = '') {
        // Cari kegiatan tetap berdasarkan token agar akurat
        $kegiatan = $this->db->get_where('tbl_kegiatan', ['qr_token' => $token])->row();

        if (!$kegiatan) {
            die("Link Presensi Tidak Valid atau Kadaluwarsa.");
        }

        $data['kegiatan'] = $kegiatan;
        $data['opd'] = $this->db->get('tbl_opd')->result();
    
        $this->load->view('publik/form_presensi', $data);
    }

    public function kirim() {
        $input = $this->input->post();
        
        // Simpan ke tbl_daftarhadir (sesuai struktur image_094656.png)
        $data_hadir = [
            'ID_KEGIATAN' => $input['ID_KEGIATAN'],
            'NAMA'        => $input['NAMA'],
            'JEN_KEL'     => $input['JEN_KEL'],
            'NO_HP'       => $input['NO_HP'],
            'EMAIL'       => $input['EMAIL'],
            'ID_OPD'      => $input['ID_OPD'],
            'JABATAN'     => $input['JABATAN'],
            'TTD'         => $input['TTD'] // Bisa berupa string base64 tanda tangan digital
        ];

        if($this->db->insert('tbl_presensi', $data_hadir)) {
            echo "Presensi Berhasil Terkirim. Terima kasih.";
        }
    }
}