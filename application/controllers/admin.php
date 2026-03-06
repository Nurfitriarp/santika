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
        // load form helper
        $this->load->helper('form');
        // Gunakan alias jika Anda ingin memanggil dengan huruf kapital 'M_admin'
        $this->load->model('M_admin', 'M_admin');

        if (!$this->session->userdata('admin_id')) {
        redirect('auth');
}
    }

    public function index()
    {
        $this->dashboard();
    }

    public function dashboard()
    {
        // Profile page - fetch admin data from tbl_user
        $admin_id = $this->session->userdata('admin_id') ?: 1; // default to 1 if not set
        // include spaces in column name by aliasing it to a valid property
        $data['admin'] = $this->db
            ->select("*, `PERANGKAT DAERAH` AS PERANGKAT_DAERAH")
            ->get_where('tbl_user', ['ID' => $admin_id])
            ->row();
        
        $this->load->view('admin/header');
        $this->load->view('admin/sidebar');
        $this->load->view('admin/dashboard', $data);
        $this->load->view('admin/footer');
    }

    public function rekap()
    {
        // Tampilkan halaman rekap kegiatan
        $admin_id = $this->session->userdata('admin_id') ?: 1;
        $data['admin'] = $this->db
            ->select("*, `PERANGKAT DAERAH` AS PERANGKAT_DAERAH")
            ->get_where('tbl_user', ['ID' => $admin_id])
            ->row();
        $data['kegiatan'] = $this->M_admin->get_data();
        
        $this->load->view('admin/header');
        $this->load->view('admin/sidebar');
        $this->load->view('admin/rekap_kegiatan', $data);
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
        $this->load->view('admin/rekap_kegiatan', $data);
        $this->load->view('admin/footer');
    }

    // Search kegiatan di halaman rekap
    public function rekap_search()
    {
        $admin_id = $this->session->userdata('admin_id') ?: 1;
        $data['admin'] = $this->db
            ->select("*, `PERANGKAT DAERAH` AS PERANGKAT_DAERAH")
            ->get_where('tbl_user', ['ID' => $admin_id])
            ->row();
        
        $keyword = $this->input->post('keyword');
        if ($keyword) {
            $data['kegiatan'] = $this->M_admin->search_kegiatan($keyword);
            // Jika data tidak ditemukan, redirect dengan pesan error
            if (empty($data['kegiatan'])) {
                $this->session->set_flashdata('error', 'Data kegiatan dengan keyword "' . htmlspecialchars($keyword) . '" tidak ditemukan.');
                redirect('admin/rekap');
            }
        } else {
            $data['kegiatan'] = $this->M_admin->get_data();
        }
        $data['keyword'] = $keyword;
        
        $this->load->view('admin/header');
        $this->load->view('admin/sidebar');
        $this->load->view('admin/dashboard', $data);
        $this->load->view('admin/footer');
    }

    public function kegiatan()
    {
        // Tampilkan halaman kegiatan
        $admin_id = $this->session->userdata('admin_id') ?: 1;
        $data['admin'] = $this->db
            ->select("*, `PERANGKAT DAERAH` AS PERANGKAT_DAERAH")
            ->get_where('tbl_user', ['ID' => $admin_id])
            ->row();
        $data['kegiatan'] = $this->M_admin->get_data();
        
        $this->load->view('admin/header');
        $this->load->view('admin/sidebar');
        $this->load->view('admin/kegiatan', $data);
        $this->load->view('admin/footer');
    }

    // Search kegiatan di halaman kegiatan
    public function kegiatan_search()
    {
        $admin_id = $this->session->userdata('admin_id') ?: 1;
        $data['admin'] = $this->db
            ->select("*, `PERANGKAT DAERAH` AS PERANGKAT_DAERAH")
            ->get_where('tbl_user', ['ID' => $admin_id])
            ->row();
        
        $keyword = $this->input->post('keyword');
        if ($keyword) {
            $data['kegiatan'] = $this->M_admin->search_kegiatan($keyword);
            // Jika data tidak ditemukan, redirect dengan pesan error
            if (empty($data['kegiatan'])) {
                $this->session->set_flashdata('error', 'Data kegiatan dengan keyword "' . htmlspecialchars($keyword) . '" tidak ditemukan.');
                redirect('admin/kegiatan');
            }
        } else {
            $data['kegiatan'] = $this->M_admin->get_data();
        }
        $data['keyword'] = $keyword;
        
        $this->load->view('admin/header');
        $this->load->view('admin/sidebar');
        $this->load->view('admin/kegiatan', $data);
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
        // generate token otomatis sebelum simpan
        $input['qr_token'] = bin2hex(random_bytes(10));
        
        $insert_id = $this->M_admin->insert_kegiatan($input);

        if ($insert_id) {
            $this->session->set_flashdata('success', 'Kegiatan berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan kegiatan.');
        }
        redirect('admin/kegiatan');
    }

    // Fungsi untuk menampilkan halaman edit
public function edit($id)
{
    // Ambil data kegiatan berdasarkan ID
    $data['kegiatan'] = $this->db->get_where('tbl_kegiatan', ['ID_KEGIATAN' => $id])->row();
    $data['opd'] = $this->M_admin->get_opd();

    // Jika data tidak ditemukan
    if (!$data['kegiatan']) {
        $this->session->set_flashdata('error', 'Data kegiatan tidak ditemukan.');
        redirect('admin/kegiatan');
    }

    $this->load->view('admin/header');
    $this->load->view('admin/sidebar');
    $this->load->view('admin/edit_kegiatan', $data); // Nama file view kita
    $this->load->view('admin/footer');
}

    // update data
    public function update()
    {
        $id = $this->input->post('ID_KEGIATAN');
        $data = [
            'NAMA' => $this->input->post('NAMA'),
            'TEMPAT' => $this->input->post('TEMPAT'),
            'JAM' => $this->input->post('JAM'),
            'TANGGAL' => $this->input->post('TANGGAL'),
            'SKPD_PENYELENGGARA' => $this->input->post('SKPD_PENYELENGGARA'),
            'PIMPINAN_RAPAT' => $this->input->post('PIMPINAN_RAPAT'),
            'ID_OPD' => $this->input->post('ID_OPD'),
            'JML_PESERTA' => $this->input->post('JML_PESERTA'),
            'JAM_PELAJARAN' => $this->input->post('JAM_PELAJARAN'),
        ];

        $this->db->where('ID_KEGIATAN', $id);
        if ($this->db->update('tbl_kegiatan', $data)) {
            $this->session->set_flashdata('success', 'Kegiatan berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui kegiatan.');
        }
        redirect('admin/kegiatan');
    }

    // Hapus kegiatan
    public function hapus($id)
    {
        if (!$id) {
            $this->session->set_flashdata('error', 'ID kegiatan tidak valid.');
            redirect('admin/kegiatan');
        }

        $ok = $this->M_admin->delete_kegiatan($id);
        if ($ok) {
            $this->session->set_flashdata('success', 'Kegiatan berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus kegiatan.');
        }
        redirect('admin/kegiatan');
    }

}