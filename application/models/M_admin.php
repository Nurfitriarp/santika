<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_admin extends CI_Model {

    public function get_data() {
        // Ganti 'nama_tabel' dengan nama tabel asli di phpMyAdmin Anda
        return $this->db->get('tbl_kegiatan')->result();
    }
}