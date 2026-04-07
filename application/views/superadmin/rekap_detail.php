<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <div class="d-none d-lg-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100">
                <h1 class="h5 mb-0 text-gray-800 font-weight-bold">Detail Rekap Kegiatan</h1>
            </div>
        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

    <?php if($this->session->flashdata('message')): ?>
        <div class="alert alert-<?= $this->session->flashdata('type'); ?> alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('message'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Informasi Detail Kegiatan</h6>
                        </div>

                        <div class="card-body">
                            <?php if($detail): ?>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Nama Kegiatan:</label>
                                    <p><?= $detail->NAMA ?? 'N/A' ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Tanggal:</label>
                                    <p><?= $detail->TANGGAL ?? 'N/A' ?></p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Tempat:</label>
                                    <p><?= $detail->TEMPAT ?? 'N/A' ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Pemimpin Rapat:</label>
                                    <p><?= $detail->PIMPINAN_RAPAT ?? 'N/A' ?></p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Perangkat Daerah Penyelenggara:</label>
                                    <p><?= $detail->SKPD_PENYELENGGARA ?? 'N/A' ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Statistik Kehadiran:</label>
                                    <div class="p-3 bg-light border-left-primary rounded">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Target Peserta: <?= $detail->JML_PESERTA ?> Orang</div>
                                                <div class="h6 mb-0 font-weight-bold text-gray-800">Telah Hadir: <?= $total_hadir ?> Orang (<?= $persentase ?>%)</div>
                                                
                                                <div class="progress progress-sm mr-2 mt-2">
                                                    <div class="progress-bar bg-primary" role="progressbar" 
                                                        style="width: <?= $persentase ?>%" aria-valuenow="<?= $persentase ?>" 
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>

                                                <div class="mt-2 small">
                                                    <span class="mr-2"><i class="fas fa-mars text-info"></i> Laki-laki: <b><?= $count_l ?></b></span>
                                                    <span><i class="fas fa-venus text-danger"></i> Perempuan: <b><?= $count_p ?></b></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <a href="<?= base_url('superadmin/kegiatan'); ?>" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                    <a href="<?= base_url('superadmin/print_rekap/' . (isset($detail) && isset($detail->ID_KEGIATAN) ? $detail->ID_KEGIATAN : '')); ?>" class="btn btn-primary" target="_blank">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
                                </div>
                            </div>

                            <?php else: ?>
                            <div class="alert alert-warning">
                                Data tidak ditemukan!
                            </div>
                            <a href="<?= base_url('superadmin/kegiatan'); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Peserta -->
            <div class="row justify-content-center mt-4">
                <div class="col-xl-12 col-lg-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Daftar Kehadiran Peserta </h6>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Jenis Kelamin</th>
                                            <th scope="col">No. HP</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Perangkat Daerah</th>
                                            <th scope="col">Jabatan</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($peserta && count($peserta) > 0): ?>
                                            <?php foreach($peserta as $key => $p): ?>
                                            <tr>
                                                <td><?= $key + 1 ?></td>
                                                <td><?= $p->NAMA ?? 'N/A' ?></td>
                                                <td>
                                                    <?php 
                                                        // Mengubah ke huruf besar untuk memastikan pengecekan akurat
                                                        $jk = strtoupper($p->JEN_KEL);

                                                        if ($jk == 'L' || $jk == '1') {
                                                            echo 'Laki-laki';
                                                        } elseif ($jk == 'P' || $jk == '2') {
                                                            echo 'Perempuan';
                                                        } else {
                                                            echo '-'; // Jika data kosong
                                                        }
                                                    ?>
                                                </td>
                                                <td><?= $p->NO_HP ?? 'N/A' ?></td>
                                                <td><?= $p->EMAIL ?? 'N/A' ?></td>
                                                <td>
                                                    <?php 
                                                        // Gunakan kolom SKPD karena data teks instansi tersimpan di situ
                                                        echo !empty($p->SKPD) ? $p->SKPD : 'N/A'; 
                                                    ?>
                                                </td>
                                                <td><?= $p->JABATAN ?? 'N/A' ?></td>
                                                <td class="text-center">
                                                    <a href="javascript:void(0);" 
                                                    class="btn btn-danger btn-sm btn-delete" 
                                                    data-toggle="modal" 
                                                    data-target="#deleteModal" 
                                                    data-url="<?= base_url('superadmin/delete_peserta/' . $p->ID_LOGIN); ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    <em>Belum ada peserta yang melakukan presensi</em>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Konfirmasi Hapus</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus peserta ini? Data yang dihapus tidak dapat dikembalikan.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <a id="confirm-delete" class="btn btn-danger" href="#">Hapus</a>
            </div>
        </div>
    </div>
</div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // 1. LOGIKA MODAL HAPUS
        $('.btn-delete').on('click', function() {
            // Ambil URL dari atribut data-url tombol sampah
            var deleteUrl = $(this).data('url');
            // Masukkan ke href tombol merah di dalam modal
            $('#confirm-delete').attr('href', deleteUrl);
        });

        // 2. LOGIKA AUTO-HIDE ALERT (Opsional tapi disarankan)
        // Membuat alert pesan berhasil hilang otomatis setelah 3 detik
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 3000);

        // 3. ALTERNATIF: JIKA PAKAI SWEETALERT2 (Lebih Modern)
        /* <?php if($this->session->flashdata('message')): ?>
            Swal.fire({
                icon: '<?= $this->session->flashdata('type'); ?>', // success atau danger
                title: 'Notifikasi',
                text: '<?= $this->session->flashdata('message'); ?>',
                showConfirmButton: false,
                timer: 2000
            });
        <?php endif; ?>
        */
    });
</script>
    <!-- End o Main Content -->

