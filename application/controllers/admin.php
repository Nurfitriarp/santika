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
        $admin_id = $this->session->userdata('admin_id');
        $data['admin'] = $this->db->get_where('tbl_user', ['ID' => $admin_id])->row();

        $data['detail'] = $this->M_admin->get_detail($id);
        $data['peserta'] = $this->M_admin->get_peserta($id);

        if (!$data['detail']) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('admin/kegiatan');
        }

        // --- LOGIKA HITUNG STATISTIK (TAMBAHAN) ---
        $total_hadir = count($data['peserta']);
        $target_peserta = (int)$data['detail']->JML_PESERTA;
        
        // Hitung Persentase
        $data['persentase'] = ($target_peserta > 0) ? round(($total_hadir / $target_peserta) * 100, 1) : 0;
        
        // Hitung Gender (Logika aman untuk L/P atau 1/2)
        $laki = 0;
        $perempuan = 0;
        foreach ($data['peserta'] as $p) {
            $jk = strtoupper($p->JEN_KEL);
            if ($jk == 'L' || $jk == '1') {
                $laki++;
            } elseif ($jk == 'P' || $jk == '2') {
                $perempuan++;
            }
        }
        
        $data['count_l'] = $laki;
        $data['count_p'] = $perempuan;
        $data['total_hadir'] = $total_hadir;
        // ------------------------------------------

        $this->load->view('admin/header');
        $this->load->view('admin/sidebar', $data);
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

        // TAMBAHKAN BARIS INI
        $data['jenis_opd'] = $this->db->get('tbl_jenis_opd')->result();

        $this->load->view('admin/header');
        $this->load->view('admin/sidebar');
        // Pastikan $data dikirim ke view tambah_kegiatan
        $this->load->view('admin/tambah_kegiatan', $data); 
        $this->load->view('admin/footer');
    }

    // save kegiatan
    public function edit($id)
    {
        $admin_id = $this->session->userdata('admin_id');
        $data['admin'] = $this->db->get_where('tbl_user', ['ID' => $admin_id])->row();
        $data['kegiatan'] = $this->db->get_where('tbl_kegiatan', ['ID_KEGIATAN' => $id])->row();
        
        $data['opd'] = $this->M_admin->get_opd(); 
        // TAMBAHKAN BARIS INI:
        $data['jenis_opd'] = $this->db->get('tbl_jenis_opd')->result(); 

        if (!$data['kegiatan']) {
            $this->session->set_flashdata('error', 'Data kegiatan tidak ditemukan.');
            redirect('admin/kegiatan');
        }

        $this->load->view('admin/header');
        $this->load->view('admin/sidebar', $data);
        $this->load->view('admin/edit_kegiatan', $data); 
        $this->load->view('admin/footer');
    }

    public function simpan() {
        $pilihan_opd = $this->input->post('ID_OPD'); 
        $jml_input   = $this->input->post('JML_PESERTA'); 

        if (empty($pilihan_opd)) {
            $this->session->set_flashdata('error', 'Pilih minimal satu instansi.');
            redirect('admin/tambah');
            return;
        }

        $jml_array = explode(',', $jml_input);
        $jml_array = array_map('trim', $jml_array); 

        $data_gabungan = [];
        $total_peserta = 0;
        
        foreach ($pilihan_opd as $index => $val) {
            $jml_orang = (isset($jml_array[$index]) && is_numeric($jml_array[$index])) ? (int)$jml_array[$index] : 0;
            $data_gabungan[] = $val . ':' . $jml_orang;
            $total_peserta += $jml_orang;
        }

        $data = [
            'NAMA'               => $this->input->post('NAMA'),
            'TEMPAT'             => $this->input->post('TEMPAT'),
            'JAM'                => $this->input->post('JAM'),
            'TANGGAL'            => $this->input->post('TANGGAL'),
            'SKPD_PENYELENGGARA' => $this->input->post('SKPD_PENYELENGGARA'),
            'PIMPINAN_RAPAT'     => $this->input->post('PIMPINAN_RAPAT'),
            'ID_OPD'             => implode(',', $data_gabungan), 
            'JML_PESERTA'        => $total_peserta,
            'STS'                => 1, // Default aktif
            'qr_token'           => md5(uniqid(rand(), true))
        ];

        $this->db->insert('tbl_kegiatan', $data);
        log_activity('ADD', 'Menambahkan kegiatan: ' . $data['NAMA']);
        redirect('admin/kegiatan');
    }

    public function update() {
        $id = $this->input->post('ID_KEGIATAN');
        $pilihan_opd = $this->input->post('ID_OPD'); 
        $jml_input   = $this->input->post('JML_PESERTA'); 

        $jml_array = explode(',', $jml_input);
        $jml_array = array_map('trim', $jml_array);

        $data_gabungan = [];
        $total_peserta = 0;
        foreach ($pilihan_opd as $index => $val) {
            $jml_orang = (isset($jml_array[$index]) && is_numeric($jml_array[$index])) ? (int)$jml_array[$index] : 0;
            $data_gabungan[] = $val . ':' . $jml_orang;
            $total_peserta += $jml_orang;
        }

        $data = [
            'NAMA'               => $this->input->post('NAMA'),
            'TEMPAT'             => $this->input->post('TEMPAT'),
            'JAM'                => $this->input->post('JAM'),
            'TANGGAL'            => $this->input->post('TANGGAL'),
            'SKPD_PENYELENGGARA' => $this->input->post('SKPD_PENYELENGGARA'),
            'PIMPINAN_RAPAT'     => $this->input->post('PIMPINAN_RAPAT'),
            'ID_OPD'             => implode(',', $data_gabungan), 
            'JML_PESERTA'        => $total_peserta,
        ];

        $this->db->where('ID_KEGIATAN', $id);
        $this->db->update('tbl_kegiatan', $data);
        log_activity('EDIT', 'Memperbarui kegiatan: ' . $data['NAMA']);
        redirect('admin/kegiatan');
    }

    public function toggle_status($id, $status) {
    // Pastikan ID valid
    if (!$id) {
        redirect('admin/kegiatan');
    }

    $data_update = ['STS' => $status];
    $this->db->where('ID_KEGIATAN', $id);
    $this->db->update('tbl_kegiatan', $data_update);

    $msg = ($status == 1) ? "Absensi kegiatan berhasil diaktifkan." : "Absensi kegiatan telah dinonaktifkan.";
    $this->session->set_flashdata('success', $msg);
    
    // Log aktivitas
    log_activity('EDIT', $msg . " (ID Kegiatan: $id)");

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

    // Konfigurasi Library Upload
    $config['upload_path']   = $upload_path;
    $config['allowed_types'] = 'jpg|jpeg|png';
    $config['max_size']      = 2048; // 2MB
    $config['file_name']     = 'profile_' . $username . '_' . time();

    $this->load->library('upload', $config);

    // Pastikan 'foto_profil' sesuai dengan name="foto_profil" di input file modal Anda
    if (!$this->upload->do_upload('foto_profil')) {
        $this->session->set_flashdata('error', 'Gagal upload: ' . $this->upload->display_errors('', ''));
        redirect('admin/dashboard');
    } else {
        $upload_data = $this->upload->data();
        $new_image   = $upload_data['file_name'];

        // 2. Ambil data lama untuk hapus file fisik
        $old_user = $this->db->get_where('tbl_user', ['USERNAME' => $username])->row();
        
        // Periksa jika ada foto lama yang perlu dihapus (bukan default)
        if ($old_user && $old_user->GAMBAR != 'default.svg' && !empty($old_user->GAMBAR)) {
            $old_file = $upload_path . $old_user->GAMBAR;
            if (file_exists($old_file)) {
                unlink($old_file);
            }
        }

        // 3. Update database
        $data_update = [
            'GAMBAR'     => $new_image,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('USERNAME', $username);
        $update = $this->db->update('tbl_user', $data_update);

        if ($update) {
            $this->session->set_flashdata('success', 'Foto profil berhasil diperbarui!');
        } else {
            $this->session->set_flashdata('error', 'Database gagal diupdate.');
        }

        redirect('admin/dashboard');
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

    public function delete_peserta($id)
    {
        $this->db->where('ID_LOGIN', $id);
        $this->db->delete('tbl_presensi');

        if ($this->db->affected_rows() > 0) {
            // Set notifikasi sukses
            $this->session->set_flashdata('message', 'Data peserta berhasil dihapus!');
            $this->session->set_flashdata('type', 'success');
        } else {
            // Set notifikasi gagal
            $this->session->set_flashdata('message', 'Gagal menghapus data.');
            $this->session->set_flashdata('type', 'danger');
        }

        redirect($_SERVER['HTTP_REFERER']);
    }

}