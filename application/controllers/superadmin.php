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
    
    // Gunakan select * saja jika ingin semua, 
    // atau sebutkan satu per satu jika ingin sangat aman.
    $data['admin'] = $this->db
        ->get_where('tbl_user', ['ID' => $admin_id])
        ->row();
    
    // Debugging (Opsional: Hapus jika sudah jalan)
    // die(print_r($data['admin'])); 

    $data['logs'] = $this->M_admin->get_activity_logs(null, 5);
    
    $this->load->view('superadmin/header');
    $this->load->view('superadmin/sidebar', $data); // Pastikan data dikirim ke sidebar jika perlu
    $this->load->view('superadmin/dashboard', $data);
    $this->load->view('superadmin/footer');
}

    // AJAX untuk Real-Time Update Activity Log
    public function get_latest_logs_ajax() {
        $logs = $this->M_superadmin->get_activity_logs(5);
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

        // Contoh logika di Controller
    public function simpan() {
        $pilihan_opd = $this->input->post('ID_OPD'); // Isinya Array (Contoh: ["JENIS_1", "24", "25"])
        $jml_input   = $this->input->post('JML_PESERTA'); // Isinya String (Contoh: "50,10,15")

        if (empty($pilihan_opd)) {
            $this->session->set_flashdata('error', 'Silakan pilih minimal satu instansi.');
            redirect('superadmin/tambah');
            return;
        }

        // 1. Pecah input jumlah peserta (koma)
        $jml_array = explode(',', $jml_input);
        $jml_array = array_map('trim', $jml_array); 

        // 2. Gabungkan ID/Jenis dengan Jumlah (Format: ID:JUMLAH)
        $data_gabungan = [];
        $total_peserta = 0;
        
        foreach ($pilihan_opd as $index => $val) {
            // Ambil angka sesuai urutan, jika tidak ada set 0
            $jml_orang = (isset($jml_array[$index]) && is_numeric($jml_array[$index])) ? (int)$jml_array[$index] : 0;
            
            // Simpan format "IDENTITAS:JUMLAH"
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
            // TERSIMPAN SEPERTI: "JENIS_1:50,24:10,25:15"
            'ID_OPD'             => implode(',', $data_gabungan), 
            'JML_PESERTA'        => $total_peserta, // Hasil penjumlahan otomatis (Contoh: 75)
            'qr_token'           => md5(uniqid(rand(), true))
        ];

        if ($this->db->insert('tbl_kegiatan', $data)) {
            log_activity('ADD', 'Menambahkan kegiatan: ' . $data['NAMA']);
            $this->session->set_flashdata('success', 'Kegiatan berhasil disimpan.');
        }
        redirect('superadmin/kegiatan');
    }
    public function edit($id)
    {
        $admin_id = $this->session->userdata('admin_id');
        $data['admin'] = $this->db->get_where('tbl_user', ['ID' => $admin_id])->row();

        // Ambil data kegiatan
        $data['kegiatan'] = $this->db->get_where('tbl_kegiatan', ['ID_KEGIATAN' => $id])->row();
        
        // WAJIB: Memuat master data agar dropdown Jenis/Kolektif muncul
        $data['opd'] = $this->M_admin->get_opd(); 
        $data['jenis_opd'] = $this->db->get('tbl_jenis_opd')->result(); 

        if (!$data['kegiatan']) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('superadmin/kegiatan');
        }

        $this->load->view('superadmin/header');
        $this->load->view('superadmin/sidebar', $data);
        $this->load->view('superadmin/edit_kegiatan', $data); 
        $this->load->view('superadmin/footer');
    }

    public function update() {
        $id_kegiatan = $this->input->post('ID_KEGIATAN');
        $pilihan_input = $this->input->post('ID_OPD'); // Isinya Array
        $jml_input     = $this->input->post('JML_PESERTA'); // Isinya String koma

        if (empty($pilihan_input)) {
            $this->session->set_flashdata('error', 'Minimal satu instansi harus dipilih.');
            redirect($_SERVER['HTTP_REFERER']);
            return;
        }

        // 1. Pecah string jumlah peserta (koma)
        $jml_array = explode(',', $jml_input);
        $jml_array = array_map('trim', $jml_array);

        // 2. Gabungkan pilihan (Jenis/OPD) dengan Jumlahnya
        $data_gabungan = [];
        $total_peserta = 0;
        
        foreach ($pilihan_input as $index => $val) {
            $jml_orang = (isset($jml_array[$index]) && is_numeric($jml_array[$index])) ? (int)$jml_array[$index] : 0;
            $data_gabungan[] = $val . ':' . $jml_orang;
            $total_peserta += $jml_orang;
        }

        $data = [
            'NAMA'               => $this->input->post('NAMA'),
            'TEMPAT'             => $this->input->post('TEMPAT'),
            'JAM'                => $this->input->post('JAM'),
            'TANGGAL'            => $this->input->post('TANGGAL'),
            'PIMPINAN_RAPAT'     => $this->input->post('PIMPINAN_RAPAT'),
            'SKPD_PENYELENGGARA' => $this->input->post('SKPD_PENYELENGGARA'),
            // TERSIMPAN SEBAGAI STRING: "JENIS_1:50,24:10"
            'ID_OPD'             => implode(',', $data_gabungan), 
            'JML_PESERTA'        => $total_peserta,
        ];

        $this->db->where('ID_KEGIATAN', $id_kegiatan);
        if ($this->db->update('tbl_kegiatan', $data)) {
            log_activity('EDIT', 'Memperbarui kegiatan: ' . $data['NAMA']);
            $this->session->set_flashdata('success', 'Perubahan berhasil disimpan.');
        }
        redirect('superadmin/kegiatan');
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

    public function toggle_status($id, $status) {
    $this->db->where('ID_KEGIATAN', $id);
    $update = $this->db->update('tbl_kegiatan', ['STS' => $status]);

    if ($update) {
        $msg = ($status == 1) ? "Absensi diaktifkan." : "Absensi dinonaktifkan.";
        $this->session->set_flashdata('success', $msg);
        log_activity('EDIT', $msg . " ID: " . $id);
    } else {
        $this->session->set_flashdata('error', "Gagal mengubah status.");
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

        // TAMBAHKAN DUA BARIS INI:
        $data['opd'] = $this->M_admin->get_opd();
        $data['jenis_opd'] = $this->db->get('tbl_jenis_opd')->result(); // Ambil master jenis

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

        // 1. Data profil admin yang login (untuk header/sidebar)
        $data['admin'] = $this->db->get_where('tbl_user', ['ID' => $admin_id])->row();
        $data['superadmin'] = $data['admin']; // Sesuaikan jika view pakai variabel $superadmin

        // 2. Logika Pencarian
        $keyword = $this->input->post('keyword'); // Tangkap input dari form pencarian
        
        if (!empty($keyword)) {
            // Jika user sedang mencari sesuatu
            $this->db->like('NAMA', $keyword);
            $this->db->or_like('USERNAME', $keyword);
            $this->db->or_like('PERANGKAT_DAERAH', $keyword);
            $data['all_users'] = $this->db->get('tbl_user')->result();
            $data['keyword'] = $keyword; // Kirim kata kunci kembali ke view
        } else {
            // Jika tidak ada pencarian, tampilkan semua user
            $data['all_users'] = $this->M_superadmin->get_all_users();
            $data['keyword'] = null;
        }

        // 3. PENTING: Inisialisasi variabel $kegiatan agar View tidak error
        // Karena di View Anda ada pengecekan if(!empty($kegiatan)), 
        // kita beri nilai array kosong agar alert "Tidak Ada Data Kegiatan" tidak muncul secara paksa.
        $data['kegiatan'] = []; 

        // 4. Load View
        $this->load->view('superadmin/header');
        $this->load->view('superadmin/sidebar', $data); // Kirim $data ke sidebar agar foto profil muncul
        $this->load->view('superadmin/kelola_user', $data);
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
        $data['admin'] = $this->db->get_where('tbl_user', ['ID' => $admin_id])->row();

        // Ambil data OPD untuk dropdown
        $data['list_opd'] = $this->db->get('tbl_opd')->result(); 

        $this->load->view('superadmin/header');
        $this->load->view('superadmin/sidebar', $data);
        $this->load->view('superadmin/tambah_user', $data); 
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
        redirect('superadmin/dashboard');
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

        redirect('superadmin/dashboard');
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
    $this->load->view('superadmin/cetak_qr_view', $data);
}

// ==========================================
    // KELOLA JENIS PERANGKAT DAERAH (OPD)
    // ==========================================

    public function jenispd()
    {
        $admin_id = $this->session->userdata('admin_id');
        $data['superadmin'] = $this->db->get_where('tbl_user', ['ID' => $admin_id])->row();

        // Mengambil data dari tbl_jenis_opd
        // Variabel $kegiatan digunakan agar sinkron dengan foreach di view jenispd.php Anda
        $data['master'] = $this->db->get('tbl_jenis_opd')->result();
        $data['keyword'] = null;

        $this->load->view('superadmin/header');
        $this->load->view('superadmin/sidebar', $data);
        $this->load->view('superadmin/jenispd', $data);
        $this->load->view('superadmin/footer');
    }

    public function tambahjenispd()
    {
        $admin_id = $this->session->userdata('admin_id');
        $data['superadmin'] = $this->db->get_where('tbl_user', ['ID' => $admin_id])->row();

        $this->load->view('superadmin/header');
        $this->load->view('superadmin/sidebar', $data);
        $this->load->view('superadmin/tambahjenispd', $data); // Pastikan view ini tersedia
        $this->load->view('superadmin/footer');
    }

    public function simpan_jenispd() {
        $nama_opd = $this->input->post('NAMA_OPD');
        
        // Jika Anda ingin menginput ID_JOPD secara manual (bukan auto-increment)
        $data = [
            'ID_J-OPD' => $this->input->post('ID_J-OPD'), 
            'NAMA_OPD' => $nama_opd
        ];

        if ($this->M_superadmin->insert_jenis_opd($data)) {
            log_activity('ADD', 'Menambahkan jenis OPD: ' . $nama_opd);
            $this->session->set_flashdata('success', 'Jenis Perangkat Daerah berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data.');
        }
        redirect('superadmin/jenispd');
    }

    public function editpd($id)
    {
        $admin_id = $this->session->userdata('admin_id');
        $data['superadmin'] = $this->db->get_where('tbl_user', ['ID' => $admin_id])->row();
        
        // Ambil data berdasarkan ID_J-OPD (sesuai SS database)
        $data['jenispd'] = $this->db->get_where('tbl_jenis_opd', ['ID_J-OPD' => $id])->row();

        if (!$data['jenispd']) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('superadmin/jenispd');
        }

        $this->load->view('superadmin/header');
        $this->load->view('superadmin/sidebar', $data);
        $this->load->view('superadmin/edit_jenispd', $data); // Pastikan view ini tersedia
        $this->load->view('superadmin/footer');
    }

    public function update_jenispd()
    {
        $id = $this->input->post('ID_J-OPD');
        $nama_opd = $this->input->post('NAMA_OPD');

        $this->db->where('ID_J-OPD', $id);
        if ($this->db->update('tbl_jenis_opd', ['NAMA_OPD' => $nama_opd])) {
            log_activity('EDIT', 'Memperbarui jenis perangkat daerah: ' . $nama_opd);
            $this->session->set_flashdata('success', 'Data berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data.');
        }
        redirect('superadmin/jenispd');
    }

    public function search()
{
    $keyword = $this->input->post('keyword');
    $admin_id = $this->session->userdata('admin_id');
    
    $data['superadmin'] = $this->db->get_where('tbl_user', ['ID' => $admin_id])->row();

    if (!empty($keyword)) {
        $this->db->select('*');
        $this->db->from('tbl_kegiatan');
        $this->db->group_start();
        $this->db->like('NAMA', $keyword);
        $this->db->or_like('TEMPAT', $keyword);
        $this->db->or_like('PIMPINAN_RAPAT', $keyword);
        $this->db->group_end();
        $data['kegiatan'] = $this->db->get()->result();
    } else {
        // Gunakan alias model yang benar (M_superadmin)
        $data['kegiatan'] = $this->M_superadmin->get_data(); 
    }

    $data['keyword'] = $keyword; 

    $this->load->view('superadmin/header');
    $this->load->view('superadmin/sidebar', $data);
    $this->load->view('superadmin/kegiatan', $data); // Pastikan view-nya tetap 'kegiatan'
    $this->load->view('superadmin/footer');
}

    public function rekap_search()
{
    $keyword = $this->input->post('keyword');
    $admin_id = $this->session->userdata('admin_id');
    
    $data['admin'] = $this->db->get_where('tbl_user', ['ID' => $admin_id])->row();

    if (!empty($keyword)) {
        $this->db->select('*');
        $this->db->from('tbl_kegiatan');
        $this->db->group_start();
        $this->db->like('NAMA', $keyword);
        $this->db->or_like('TEMPAT', $keyword);
        $this->db->or_like('PIMPINAN_RAPAT', $keyword);
        $this->db->group_end();
        $data['kegiatan'] = $this->db->get()->result();
    } else {
        $data['kegiatan'] = $this->M_admin->get_data(); 
    }

    $data['keyword'] = $keyword; 

    $this->load->view('superadmin/header');
    $this->load->view('superadmin/sidebar', $data);
    // PERBAIKAN: Ubah 'rekap' menjadi 'rekap_kegiatan' agar sesuai nama file aslinya
    $this->load->view('superadmin/rekap_kegiatan', $data); 
    $this->load->view('superadmin/footer');
}

    public function hapuspd($id)
    {
        // Ambil data dulu untuk log
        $item = $this->db->get_where('tbl_jenis_opd', ['ID_J-OPD' => $id])->row();
        $nama = ($item) ? $item->NAMA_OPD : 'ID: ' . $id;

        $this->db->where('ID_J-OPD', $id);
        if ($this->db->delete('tbl_jenis_opd')) {
            log_activity('DELETE', 'Menghapus jenis perangkat daerah: ' . $nama);
            $this->session->set_flashdata('success', 'Data berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data.');
        }
        redirect('superadmin/jenispd');
    }

    public function search_jenispd()
    {
        $keyword = $this->input->post('keyword');
        $admin_id = $this->session->userdata('admin_id');
        $data['superadmin'] = $this->db->get_where('tbl_user', ['ID' => $admin_id])->row();

        // Logika Pencarian
        $this->db->like('NAMA_OPD', $keyword);
        $this->db->or_like('`ID_J-OPD`', $keyword); // Mencari berdasarkan kode juga
        
        // PERBAIKAN: Namakan index 'master' agar sinkron dengan View
        $data['master'] = $this->db->get('tbl_jenis_opd')->result();
        $data['keyword'] = $keyword;

        $this->load->view('superadmin/header');
        $this->load->view('superadmin/sidebar', $data);
        $this->load->view('superadmin/jenispd', $data);
        $this->load->view('superadmin/footer');
    }

    
    // ==========================================
    // KELOLA PERANGKAT DAERAH (OPD)
    // ==========================================

    public function perda() {
        $admin_id = $this->session->userdata('admin_id');
        $data['superadmin'] = $this->db->get_where('tbl_user', ['ID' => $admin_id])->row();

        // --- BAGIAN PENTING: QUERY JOIN ---
        $this->db->select('tbl_opd.*, tbl_jenis_opd.NAMA_OPD as JENIS'); // Kita buat alias 'JENIS' di sini
        $this->db->from('tbl_opd');
        $this->db->join('tbl_jenis_opd', 'tbl_opd.ID_J-OPD = tbl_jenis_opd.ID_J-OPD', 'left');
        
        $data['master'] = $this->db->get()->result();
        $data['keyword'] = null;

        $this->load->view('superadmin/header');
        $this->load->view('superadmin/sidebar', $data);
        $this->load->view('superadmin/perda', $data);
        $this->load->view('superadmin/footer');
    }

    public function tambah_perda() {
        $admin_id = $this->session->userdata('admin_id');
        $data['superadmin'] = $this->db->get_where('tbl_user', ['ID' => $admin_id])->row();

        // Ambil data semua jenis OPD untuk pilihan dropdown
        $data['jenis_opd'] = $this->db->get('tbl_jenis_opd')->result();

        $this->load->view('superadmin/header');
        $this->load->view('superadmin/sidebar', $data);
        $this->load->view('superadmin/tambah_perda', $data);
        $this->load->view('superadmin/footer');
    }

    public function simpan_perda() {
        
        $data = [
            'ID_J-OPD' => $this->input->post('ID_J-OPD'), 
            'NAMA_OPD' => $this->input->post('NAMA_OPD')
        ];

        if ($this->db->insert('tbl_opd', $data)) {
            log_activity('ADD', 'Menambahkan OPD: ' . $data['NAMA_OPD']);
            $this->session->set_flashdata('success', 'Perangkat daerah berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan data.');
        }
        redirect('superadmin/perda');
    }

    public function edit_perda($id)
    {
        $admin_id = $this->session->userdata('admin_id');
        $data['superadmin'] = $this->db->get_where('tbl_user', ['ID' => $admin_id])->row();
        
        // Ambil data OPD yang akan diedit
        $data['opd_item'] = $this->db->get_where('tbl_opd', ['ID_OPD' => $id])->row();
        // Ambil data Jenis OPD untuk dropdown
        $data['jenis_opd'] = $this->db->get('tbl_jenis_opd')->result();

        $this->load->view('superadmin/header');
        $this->load->view('superadmin/sidebar', $data);
        $this->load->view('superadmin/edit_perda', $data); // Pastikan view ini tersedia
        $this->load->view('superadmin/footer');
    }

    public function update_perda()
    {
        $id = $this->input->post('ID_OPD');
        $data = [
            'ID_J-OPD' => $this->input->post('ID_J_OPD'),
            'NAMA_OPD' => $this->input->post('NAMA_OPD')
        ];

        $this->db->where('ID_OPD', $id);
        if ($this->db->update('tbl_opd', $data)) {
            log_activity('EDIT', 'Memperbarui OPD: ' . $data['NAMA_OPD']);
            $this->session->set_flashdata('success', 'Data berhasil diperbarui.');
        }
        redirect('superadmin/perda');
    }

    public function hapus_perda($id)
    {
        // Ambil data dulu untuk log
        $item = $this->db->get_where('tbl_opd', ['ID_OPD' => $id])->row();
        $nama = ($item) ? $item->NAMA_OPD : 'ID: ' . $id;

        $this->db->where('ID_OPD', $id);
        if ($this->db->delete('tbl_jenis_opd')) {
            log_activity('DELETE', 'Menghapus jenis perangkat daerah: ' . $nama);
            $this->session->set_flashdata('success', 'Data berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data.');
        }
        redirect('superadmin/perda');
    }

    public function search_perda()
    {
        $keyword = $this->input->post('keyword');
        $admin_id = $this->session->userdata('admin_id');
        $data['superadmin'] = $this->db->get_where('tbl_user', ['ID' => $admin_id])->row();

        // --- LOGIKA PENCARIAN DENGAN JOIN ---
        $this->db->select('tbl_opd.*, tbl_jenis_opd.NAMA_OPD as JENIS'); // Ambil alias JENIS
        $this->db->from('tbl_opd');
        $this->db->join('tbl_jenis_opd', 'tbl_opd.ID_J-OPD = tbl_jenis_opd.ID_J-OPD', 'left');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('tbl_opd.NAMA_OPD', $keyword); // Cari di nama Perangkat Daerah
            $this->db->or_like('tbl_jenis_opd.NAMA_OPD', $keyword); // Cari di nama Jenis OPD
            $this->db->group_end();
        }

        $data['master'] = $this->db->get()->result();
        $data['keyword'] = $keyword;

        // Load views agar CSS tetap terjaga
        $this->load->view('superadmin/header');
        $this->load->view('superadmin/sidebar', $data);
        $this->load->view('superadmin/perda', $data);
        $this->load->view('superadmin/footer');
    }
    
}