<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Superadmin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Proteksi: Hanya yang sudah login DAN role-nya super_admin yang bisa masuk
        if (!$this->session->userdata('admin_id')) {
            redirect('auth');
        }
        if ($this->session->userdata('role') !== 'super_admin') {
            $this->session->set_flashdata('error', 'Akses ditolak! Anda bukan Super Admin.');
            redirect('admin/dashboard');
        }
        $this->load->model('M_admin');
    }

    public function index() {
        $this->users();
    }

    // Menampilkan daftar semua admin dari tbl_user
    public function users() {
        $admin_id = $this->session->userdata('admin_id');
        $data['admin'] = $this->db->get_where('tbl_user', ['ID' => $admin_id])->row();
        $data['all_users'] = $this->db->get('tbl_user')->result();

        $this->load->view('admin/header');
        $this->load->view('admin/sidebar');
        $this->load->view('superadmin/user_list', $data); // Buat view baru nanti
        $this->load->view('admin/footer');
    }

    // Form tambah admin baru
    public function tambah_user() {
        $this->load->view('admin/header');
        $this->load->view('admin/sidebar');
        $this->load->view('superadmin/user_tambah');
        $this->load->view('admin/footer');
    }

    // Proses simpan user dengan Password Hash
    public function simpan_user() {
        $password_plain = $this->input->post('password');
        
        $data = [
            'NAMA'             => $this->input->post('nama'),
            'PERANGKAT DAERAH' => $this->input->post('pd'),
            'BIDANG'           => $this->input->post('bidang'),
            'USERNAME'         => $this->input->post('username'),
            'PASSWORD'         => password_hash($password_plain, PASSWORD_DEFAULT), // Keamanan Hash
            'ROLE'             => $this->input->post('role')
        ];

        if ($this->db->insert('tbl_user', $data)) {
            $this->session->set_flashdata('success', 'User admin berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan user.');
        }
        redirect('superadmin/users');
    }

    // Hapus User
    public function hapus_user($id) {
        if ($id == $this->session->userdata('admin_id')) {
            $this->session->set_flashdata('error', 'Anda tidak bisa menghapus akun sendiri!');
        } else {
            $this->db->where('ID', $id)->delete('tbl_user');
            $this->session->set_flashdata('success', 'User berhasil dihapus.');
        }
        redirect('superadmin/users');
    }


    public function __construct() {
    parent::__construct();
    // Jika data session admin_id tidak ada, tendang balik ke halaman login
    if (!$this->session->userdata('admin_id')) {
        $this->session->set_flashdata('error', 'Silakan login terlebih dahulu.');
        redirect('auth');
    }
}
}