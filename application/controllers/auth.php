<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
    }

    public function index() {
        // Jika sudah ada session, langsung ke dashboard
        if ($this->session->userdata('admin_id')) {
            redirect('admin/dashboard');
        }
        $this->load->view('auth/login');
    }

    public function login_process() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // Ambil data user dari tbl_user
        $user = $this->db->get_where('tbl_user', ['USERNAME' => $username])->row();

        if ($user) {
            // Verifikasi Password (Mendukung Hash & Plain Text untuk transisi)
            if (password_verify($password, $user->PASSWORD) || $password == $user->PASSWORD) {
                
                // Set Session berdasarkan struktur tbl_user Anda
                $session_data = [
                    'admin_id' => $user->ID,
                    'username' => $user->USERNAME,
                    'nama'     => $user->NAMA,
                    'role'     => $user->ROLE,
                    'foto'     => $user->GAMBAR
                ];
                
                $this->session->set_userdata($session_data);
                
                // Redirect berdasarkan Role (Admin/SuperAdmin)
                redirect('admin/dashboard');
            } else {
                $this->session->set_flashdata('error', 'Password yang Anda masukkan salah.');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('error', 'Username tidak terdaftar.');
            redirect('auth');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth');
    }
}