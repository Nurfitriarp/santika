<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property M_admin $M_admin
 */
class Admin extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        // load library session untuk flashdata
        $this->load->library('session');
        // load form helper
        $this->load->helper('form');
        // load activity helper
        $this->load->helper('activity');
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
        // Profile page - fetch admin data
        $admin_id = $this->session->userdata('admin_id');
        
        $data['admin'] = $this->db
            ->select("*, PERANGKAT_DAERAH")
            ->get_where('tbl_user', ['ID' => $admin_id])
            ->row();
        
        // SESUAIKAN: Pastikan variabelnya 'logs' agar cocok dengan view Anda
        $data['logs'] = $this->M_admin->get_activity_logs(null, 5);
        
        $this->load->view('admin/header');
        $this->load->view('admin/sidebar');
        $this->load->view('admin/dashboard', $data);
        $this->load->view('admin/footer');
    }

   // AJAX untuk Real-Time Update Activity Log
    public function get_latest_logs_ajax() {
        // PERBAIKAN: Ambil data ke variabel $logs terlebih dahulu
        $logs = $this->M_admin->get_activity_logs(null, 5);
        $output = '';
        
        if(!empty($logs)) {
            foreach($logs as $log) {
                // Gunakan nama_user jika ada, jika tidak pakai NAMA dari tabel user, default System
                $nama = isset($log->nama_user) ? $log->nama_user : (isset($log->NAMA) ? $log->NAMA : 'System');
                $waktu = date('d M Y, H:i', strtotime($log->created_at));
                
                // Pastikan helper activity sudah di-load di __construct
                $warna = get_badge_color($log->activity_type);
                $tipe = strtoupper($log->activity_type);

                $output .= "
                <tr>
                    <td><small class='text-muted'><i class='fas fa-clock mr-1'></i> $waktu</small></td>
                    <td><strong>$nama</strong></td>
                    <td>
                        <span class='badge badge-$warna mr-2'>$tipe</span>
                        <span class='text-dark'>{$log->description}</span>
                    </td>
                </tr>";
            }
        } else {
            $output = "<tr><td colspan='3' class='text-center text-muted'>Belum ada riwayat aktivitas.</td></tr>";
        }
        echo $output;
    }


    public function rekap()
    {
        // Tampilkan halaman rekap kegiatan
        $admin_id = $this->session->userdata('admin_id') ?: 1;
        $data['admin'] = $this->db
            ->select("*, PERANGKAT_DAERAH")
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
            ->select("*, PERANGKAT_DAERAH")
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
            ->select("*, PERANGKAT_DAERAH")
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
            ->select("*, PERANGKAT_DAERAH")
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
    // show tambah form
    public function tambah()
    {
        // 1. Ambil ID dari session
        $admin_id = $this->session->userdata('admin_id');

        // 2. Ambil data admin agar input Penyelenggara otomatis terisi
        $data['admin'] = $this->db
            ->select("*, PERANGKAT_DAERAH")
            ->get_where('tbl_user', ['ID' => $admin_id])
            ->row();

        // 3. Ambil data OPD untuk isi dropdown
        $data['opd'] = $this->M_admin->get_opd();

        $this->load->view('admin/header');
        $this->load->view('admin/sidebar');
        // Pastikan $data dikirim ke view tambah_kegiatan
        $this->load->view('admin/tambah_kegiatan', $data); 
        $this->load->view('admin/footer');
    }

    // save kegiatan
    public function simpan() {
    $pilihan_opd = $this->input->post('ID_OPD'); // Tangkap array dari Select2
    $jml_input   = $this->input->post('JML_PESERTA'); // Tangkap string "12,14,18"

    if (empty($pilihan_opd)) {
        $this->session->set_flashdata('error', 'Pilih minimal satu instansi.');
        redirect('superadmin/tambah');
        return;
    }

    // 1. Bersihkan ID dari kategori kolektif [SEMUA]
    $final_ids = [];
    foreach ($pilihan_opd as $id) {
        if (strpos($id, 'JENIS_') === false) {
            $final_ids[] = $id;
        }
    }
    $final_ids = array_unique($final_ids);

    // 2. Pecah input jumlah peserta
    $jml_array = explode(',', $jml_input);
    $jml_array = array_map('trim', $jml_array); 

    // 3. JODOHKAN ID dengan JUMLAHNYA
    $data_gabungan = [];
    $total_peserta = 0;
    
    foreach ($final_ids as $index => $id) {
        // Ambil angka sesuai urutan, jika tidak ada set 0
        $jml_orang = isset($jml_array[$index]) && is_numeric($jml_array[$index]) ? (int)$jml_array[$index] : 0;
        
        // Format: "ID:JUMLAH"
        $data_gabungan[] = $id . ':' . $jml_orang;
        $total_peserta += $jml_orang;
    }

    // 4. Masukkan ke Array Data
    $data = [
        'NAMA'               => $this->input->post('NAMA'),
        'TEMPAT'             => $this->input->post('TEMPAT'),
        'JAM'                => $this->input->post('JAM'),
        'TANGGAL'            => $this->input->post('TANGGAL'),
        'SKPD_PENYELENGGARA' => $this->input->post('SKPD_PENYELENGGARA'),
        'PIMPINAN_RAPAT'     => $this->input->post('PIMPINAN_RAPAT'),
        // INI BAGIAN TERPENTING: Simpan sebagai string dipisah koma
        'ID_OPD'             => implode(',', $data_gabungan), 
        'JML_PESERTA'        => $total_peserta,
        'qr_token'           => md5(uniqid(rand(), true))
    ];

    $this->db->insert('tbl_kegiatan', $data);
    redirect('admin/kegiatan');
}

    // Fungsi untuk menampilkan halaman edit
    public function edit($id)
    {
        $data['kegiatan'] = $this->db->get_where('tbl_kegiatan', ['ID_KEGIATAN' => $id])->row();
        $data['opd'] = $this->M_admin->get_opd();

        if (!$data['kegiatan']) {
            $this->session->set_flashdata('error', 'Data kegiatan tidak ditemukan.');
            redirect('admin/kegiatan');
        }

        $this->load->view('admin/header');
        $this->load->view('admin/sidebar');
        $this->load->view('admin/edit_kegiatan', $data);
        $this->load->view('admin/footer');
    }

    // update data
    public function update()
    {
        $id = $this->input->post('ID_KEGIATAN');
        $nama_kegiatan = $this->input->post('NAMA'); // Ambil nama untuk log
        
        $data = [
            'NAMA' => $nama_kegiatan,
            'TEMPAT' => $this->input->post('TEMPAT'),
            'JAM' => $this->input->post('JAM'),
            'TANGGAL' => $this->input->post('TANGGAL'),
            'SKPD_PENYELENGGARA' => $this->input->post('SKPD_PENYELENGGARA'),
            'PIMPINAN_RAPAT' => $this->input->post('PIMPINAN_RAPAT'),
            'ID_OPD' => $this->input->post('ID_OPD'),
            'JML_PESERTA' => $this->input->post('JML_PESERTA'),
        ];

        $this->db->where('ID_KEGIATAN', $id);
        if ($this->db->update('tbl_kegiatan', $data)) {
            // CATAT LOG: Tipe 'EDIT'
            log_activity('EDIT', "Memperbarui data kegiatan: " . $nama_kegiatan);
            
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

        // Ambil nama kegiatan sebelum dihapus agar bisa dicatat di log
        $kegiatan = $this->db->get_where('tbl_kegiatan', ['ID_KEGIATAN' => $id])->row();
        $nama_kegiatan = ($kegiatan) ? $kegiatan->NAMA : "ID: " . $id;

        $ok = $this->M_admin->delete_kegiatan($id);
        if ($ok) {
            // CATAT LOG: Tipe 'DELETE'
            log_activity('DELETE', "Menghapus kegiatan: " . $nama_kegiatan);
            
            $this->session->set_flashdata('success', 'Kegiatan berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus kegiatan.');
        }
        redirect('admin/kegiatan');
    }

    // Fungsi logout
    public function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url());
    }

    // Ubah password admin
    public function change_password()
    {
        $admin_id = $this->session->userdata('admin_id');
        if (!$admin_id) {
            redirect('auth');
        }

        $old = $this->input->post('old_password');
        $new = $this->input->post('new_password');

        // Ambil user saat ini
        $user = $this->db->get_where('tbl_user', ['ID' => $admin_id])->row();
        if (!$user) {
            $this->session->set_flashdata('error', 'User tidak ditemukan.');
            redirect('admin');
        }

        // Verifikasi password lama (mendukung hash & plain untuk transisi)
        if (!(password_verify($old, $user->PASSWORD) || $old == $user->PASSWORD)) {
            $this->session->set_flashdata('error', 'Password lama tidak cocok.');
            redirect('admin');
        }

        // Update dengan hash
        $this->db->where('ID', $admin_id);
        if ($this->db->update('tbl_user', ['PASSWORD' => password_hash($new, PASSWORD_DEFAULT)])) {
            $this->session->set_flashdata('success', 'Password berhasil diubah.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengubah password.');
        }

        redirect('admin');
    }

    // Print daftar hadir rekap kegiatan
    public function print_rekap($id)
    {
        $data['detail'] = $this->M_admin->get_detail($id);
        $data['peserta'] = $this->M_admin->get_peserta($id);

        if (!$data['detail']) {
            $this->session->set_flashdata('error', 'Data kegiatan tidak ditemukan.');
            redirect('admin/rekap');
        }

        $this->load->view('admin/print_rekap', $data);
    }

    public function update_foto()
    {
    // Ambil username dari session
    $username = $this->session->userdata('username'); 

    // 1. Gunakan FCPATH agar path mengarah ke root folder proyek (localhost/santika/)
    // Ini lebih aman daripada menggunakan './'
    $upload_path = FCPATH . 'assets/img/profile/';

    // CEK OTOMATIS: Jika folder belum ada, buat foldernya sekarang
    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0777, TRUE);
    }
    }

    public function cetak_qr($id)
{
    // Query disesuaikan dengan nama kolom ID_KEGIATAN
    $kegiatan = $this->db->get_where('tbl_kegiatan', ['ID_KEGIATAN' => $id])->row();

    if (!$kegiatan) {
        show_404();
    }

    $data['kegiatan'] = $kegiatan;
    $this->load->view('admin/cetak_qr_view', $data);
}
}