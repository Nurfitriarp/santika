<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property M_admin $M_admin
 */
class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // load library session untuk flashdata
        $this->load->library('session');
        // Gunakan alias jika Anda ingin memanggil dengan huruf kapital 'M_admin'
        $this->load->model('M_admin', 'M_admin');
    }

    public function dashboard()
    {
        // Pastikan nama model di sini sama dengan alias di atas
        // ambil data kegiatan dari model
        $data['kegiatan'] = $this->M_admin->get_data();
        
        $this->load->view('admin/header');
        $this->load->view('admin/sidebar');
        $this->load->view('admin/dashboard', $data);
        $this->load->view('admin/footer');
    }
    
    public function detail($id)
    {
        $data['detail'] = $this->M_admin->get_detail($id);
        $data['peserta'] = $this->M_admin->get_peserta($id);
        
        $this->load->view('admin/header');
        $this->load->view('admin/sidebar');
        $this->load->view('admin/rekap_detail', $data);
        $this->load->view('admin/footer');
    }

    // Search kegiatan
    public function search()
    {
        $keyword = $this->input->post('keyword');
        if ($keyword) {
            $data['kegiatan'] = $this->M_admin->search_kegiatan($keyword);
        } else {
            $data['kegiatan'] = $this->M_admin->get_data();
        }
        $data['keyword'] = $keyword;
        
        $this->load->view('admin/header');
        $this->load->view('admin/sidebar');
        $this->load->view('admin/dashboard', $data);
        $this->load->view('admin/footer');
    }

    // show tambah form
    public function tambah()
    {
        $data['opd'] = $this->M_admin->get_opd();

        $this->load->view('admin/header');
        $this->load->view('admin/sidebar');
        $this->load->view('admin/tambah_kegiatan', $data);
        $this->load->view('admin/footer');
    }

    // save kegiatan
    public function simpan()
    {
        $input = $this->input->post();
        $insert_id = $this->M_admin->insert_kegiatan($input);
        if ($insert_id) {
            $this->session->set_flashdata('success', 'Kegiatan berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan kegiatan.');
        }
        redirect('admin/dashboard');
    }

    // Hapus kegiatan
    public function hapus($id)
    {
        if (!$id) {
            $this->session->set_flashdata('error', 'ID kegiatan tidak valid.');
            redirect('admin/dashboard');
        }

        $ok = $this->M_admin->delete_kegiatan($id);
        if ($ok) {
            $this->session->set_flashdata('success', 'Kegiatan berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus kegiatan.');
        }
        redirect('admin/dashboard');
    }
}