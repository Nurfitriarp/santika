<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property M_superadmin M_superadmin
 */
class Superadmin extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->helper('activity'); // Pastikan nama helper sesuai (activity_helper.php)
        $this->load->model('M_superadmin', 'M_superadmin');

        // Proteksi: Harus login DAN harus role super_admin
        if (!$this->session->userdata('admin_id') || $this->session->userdata('role') !== 'super_admin') {
            $this->session->set_flashdata('error', 'Akses ditolak. Silakan login kembali.');
            redirect('auth');
        }
    }

    public function index()
    {
        $this->dashboard();
    }

    public function dashboard()
    {
        $admin_id = $this->session->userdata('admin_id');
        $data['admin'] = $this->db
            ->select("*, PERANGKAT_DAERAH")
            ->get_where('tbl_user', ['ID' => $admin_id])
            ->row();
        
        // Ambil activity logs (Gunakan model yang sama)
        $data['logs'] = $this->M_admin->get_activity_logs(null, 10);
        
        $this->load->view('superadmin/header');
        $this->load->view('superadmin/sidebar');
        $this->load->view('superadmin/dashboard', $data);
        $this->load->view('superadmin/footer');
    }

    // AJAX untuk Real-Time Update Activity Log
    public function get_latest_logs_ajax() {
        $logs = $this->M_admin->get_activity_logs(null, 10);
        $output = '';
        if(!empty($logs)) {
            foreach($logs as $log) {
                $nama = isset($log->nama_user) ? $log->nama_user : 'System';
                $waktu = date('d M Y, H:i', strtotime($log->created_at));
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

    public function kegiatan()
    {
        $admin_id = $this->session->userdata('admin_id');
        $data['superadmin'] = $this->db
            ->select("*, PERANGKAT_DAERAH")
            ->get_where('tbl_user', ['ID' => $admin_id])
            ->row();
        $data['kegiatan'] = $this->M_admin->get_data();
        
        $this->load->view('superadmin/header');
        $this->load->view('superadmin/sidebar');
        $this->load->view('superadmin/kegiatan', $data);
        $this->load->view('superadmin/footer');
    }

    public function simpan()
    {
        $input = $this->input->post();
        $input['qr_token'] = bin2hex(random_bytes(10));
        
        $insert_id = $this->M_admin->insert_kegiatan($input);

        if ($insert_id) {
            // CATAT LOG
            log_activity('ADD', 'Menambahkan kegiatan baru: ' . $input['NAMA']);
            $this->session->set_flashdata('success', 'Kegiatan berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan kegiatan.');
        }
        redirect('superadmin/kegiatan');
    }

    public function update()
    {
        $id = $this->input->post('ID_KEGIATAN');
        $nama_kegiatan = $this->input->post('NAMA');
        $data = [
            'NAMA' => $nama_kegiatan,
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
            // CATAT LOG
            log_activity('EDIT', 'Memperbarui data kegiatan: ' . $nama_kegiatan);
            $this->session->set_flashdata('success', 'Kegiatan berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui kegiatan.');
        }
        redirect('superadmin/kegiatan');
    }

    public function edit($id)
{
    $admin_id = $this->session->userdata('admin_id');
    $data['admin'] = $this->db
        ->select("*, PERANGKAT_DAERAH")
        ->get_where('tbl_user', ['ID' => $admin_id])
        ->row();

    // Ambil data kegiatan spesifik berdasarkan ID
    $data['kegiatan'] = $this->db->get_where('tbl_kegiatan', ['ID_KEGIATAN' => $id])->row();
    $data['opd'] = $this->M_admin->get_opd();

    // Jika data tidak ada, kembalikan ke daftar kegiatan
    if (!$data['kegiatan']) {
        $this->session->set_flashdata('error', 'Data kegiatan tidak ditemukan.');
        redirect('superadmin/kegiatan');
    }

    $this->load->view('superadmin/header');
    $this->load->view('superadmin/sidebar');
    $this->load->view('superadmin/edit_kegiatan', $data); // Pastikan file view ini ada di views/superadmin/
    $this->load->view('superadmin/footer');
}

    public function hapus($id)
    {
        // Ambil nama kegiatan dulu untuk keterangan log
        $kegiatan = $this->db->get_where('tbl_kegiatan', ['ID_KEGIATAN' => $id])->row();
        $nama = ($kegiatan) ? $kegiatan->NAMA : 'ID: '.$id;

        $ok = $this->M_admin->delete_kegiatan($id);
        if ($ok) {
            // CATAT LOG
            log_activity('DELETE', 'Menghapus kegiatan: ' . $nama);
            $this->session->set_flashdata('success', 'Kegiatan berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus kegiatan.');
        }
        redirect('superadmin/kegiatan');
    }

    public function tambah()
{
    $admin_id = $this->session->userdata('admin_id');
    $data['admin'] = $this->db
        ->select("*, PERANGKAT_DAERAH")
        ->get_where('tbl_user', ['ID' => $admin_id])
        ->row();
    $data['opd'] = $this->M_admin->get_opd();

    $this->load->view('superadmin/header');
    $this->load->view('superadmin/sidebar');
    $this->load->view('superadmin/tambah_kegiatan', $data);
    $this->load->view('superadmin/footer');
}

public function detail($id)
{
    $admin_id = $this->session->userdata('admin_id');
    $data['admin'] = $this->db
        ->select("*, PERANGKAT_DAERAH")
        ->get_where('tbl_user', ['ID' => $admin_id])
        ->row();

    $data['detail'] = $this->M_admin->get_detail($id);
    $data['peserta'] = $this->M_admin->get_peserta($id);

    if (!$data['detail']) {
        $this->session->set_flashdata('error', 'Data tidak ditemukan.');
        redirect('superadmin/kegiatan');
    }

    $this->load->view('superadmin/header');
    $this->load->view('superadmin/sidebar');
    $this->load->view('superadmin/rekap_detail', $data);
    $this->load->view('superadmin/footer');
}

    public function rekap()
    {
        $admin_id = $this->session->userdata('admin_id');
        $data['admin'] = $this->db
            ->select("*, PERANGKAT_DAERAH")
            ->get_where('tbl_user', ['ID' => $admin_id])
            ->row();
        $data['kegiatan'] = $this->M_admin->get_data();
        
        $this->load->view('superadmin/header');
        $this->load->view('superadmin/sidebar');
        $this->load->view('superadmin/rekap_kegiatan', $data);
        $this->load->view('superadmin/footer');
    }

    // UNTUK KELOLA USER
    public function kelola_user() {
        $admin_id = $this->session->userdata('admin_id');
    
        // Data untuk profil admin yang sedang login
        $data['admin'] = $this->db->get_where('tbl_user', ['ID' => $admin_id])->row();
    
        // Data semua user untuk isi tabel
        $data['all_users'] = $this->M_superadmin->get_all_users();

        $this->load->view('superadmin/header');
        $this->load->view('superadmin/sidebar');
        $this->load->view('superadmin/kelola_user', $data); // Halaman tabel user
        $this->load->view('superadmin/footer');
    }

    // Menampilkan form edit user
public function edit_user($id) {
    $admin_id = $this->session->userdata('admin_id');
    $data['admin'] = $this->db->get_where('tbl_user', ['ID' => $admin_id])->row();
    
    // Ambil data user yang akan diedit
    $data['user_item'] = $this->db->get_where('tbl_user', ['ID' => $id])->row();

    if (!$data['user_item']) {
        $this->session->set_flashdata('error', 'User tidak ditemukan.');
        redirect('superadmin/kelola_user');
    }

    $this->load->view('superadmin/header');
    $this->load->view('superadmin/sidebar');
    $this->load->view('superadmin/edit_user', $data);
    $this->load->view('superadmin/footer');
}

    // Proses update data user
    public function update_user() {
        $id = $this->input->post('id');
        $password_new = $this->input->post('password');

        $data = [
            'NAMA'             => $this->input->post('nama'),
            'PERANGKAT_DAERAH' => $this->input->post('pd'),
            'BIDANG'           => $this->input->post('bidang'),
            'USERNAME'         => $this->input->post('username'),
            'ROLE'             => $this->input->post('role')
        ];

        // Update password hanya jika diisi
        if (!empty($password_new)) {
            $data['PASSWORD'] = password_hash($password_new, PASSWORD_DEFAULT);
        }

        $this->db->where('ID', $id);

        if ($this->db->update('tbl_user', $data)) {
        // Catat Activity Log otomatis
            $this->log_activity('EDIT', 'Memperbarui data user: ' . $data['USERNAME']);
            $this->session->set_flashdata('success', 'Data user berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data.');
        }
        redirect('superadmin/kelola_user');
    }
   
    public function simpan_user() {
    // Konfigurasi Upload Gambar
    $config['upload_path']   = './assets/img/profile/';
    $config['allowed_types'] = 'gif|jpg|png|jpeg';
    $config['max_size']      = 2048; // 2MB
    $config['file_name']     = 'profile_' . time(); // Penamaan unik

    $this->load->library('upload', $config);

    if ($this->upload->do_upload('gambar')) {
        // Jika berhasil upload
        $upload_data = $this->upload->data();
        $file_name   = $upload_data['file_name'];
    } else {
        // Jika gagal atau tidak upload, gunakan default
        $file_name = 'default.svg';
    }

    $data = [
        'NAMA'             => $this->input->post('nama'),
        'PERANGKAT_DAERAH' => $this->input->post('pd'),
        'BIDANG'           => $this->input->post('bidang'),
        'USERNAME'         => $this->input->post('username'),
        'PASSWORD'         => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
        'ROLE'             => $this->input->post('role'),
        'GAMBAR'           => $file_name // Nama file yang disimpan di database
    ];

    if ($this->db->insert('tbl_user', $data)) {
        $this->log_activity('ADD', "Menambahkan user baru: " . $data['USERNAME']);
        $this->session->set_flashdata('success', 'User berhasil ditambahkan.');
    } else {
        $this->session->set_flashdata('error', 'Gagal menambahkan user.');
    }
    redirect('superadmin/kelola_user');
    }

    public function tambah_user() {
        $admin_id = $this->session->userdata('admin_id');
        $data['admin'] = $this->db
            ->select("*, PERANGKAT_DAERAH")
            ->get_where('tbl_user', ['ID' => $admin_id])
            ->row();

        $this->load->view('superadmin/header');
        $this->load->view('superadmin/sidebar');
        $this->load->view('superadmin/tambah_user', $data); // Pastikan nama file sesuai
        $this->load->view('superadmin/footer');
    }

    public function change_password()
    {
        $admin_id = $this->session->userdata('admin_id');
        $old = $this->input->post('old_password');
        $new = $this->input->post('new_password');

        $user = $this->db->get_where('tbl_user', ['ID' => $admin_id])->row();
        if (!(password_verify($old, $user->PASSWORD) || $old == $user->PASSWORD)) {
            $this->session->set_flashdata('error', 'Password lama tidak cocok.');
            redirect('superadmin/dashboard');
        }

        $this->db->where('ID', $admin_id);
        if ($this->db->update('tbl_user', ['PASSWORD' => password_hash($new, PASSWORD_DEFAULT)])) {
            // CATAT LOG
            log_activity('EDIT', 'User mengubah password profil');
            $this->session->set_flashdata('success', 'Password berhasil diubah.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengubah password.');
        }
        redirect('superadmin/dashboard');
    }

    public function logout()
    {
        log_activity('LOGOUT', 'User keluar dari sistem');
        $this->session->sess_destroy();
        redirect('auth');
    }
    
    // Fungsi Print Rekap (Pencatatan log ditambahkan)
    public function print_rekap($id)
    {
        $data['detail'] = $this->M_admin->get_detail($id);
        $data['peserta'] = $this->M_admin->get_peserta($id);

        if (!$data['detail']) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('superadmin/rekap');
        }

        // CATAT LOG PRINT
        log_activity('PRINT', 'Mencetak daftar hadir kegiatan: ' . $data['detail']->NAMA);
        $this->load->view('superadmin/print_rekap', $data);
    }
}