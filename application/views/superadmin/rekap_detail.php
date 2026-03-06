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
                                    <label class="font-weight-bold">SKPD Penyelenggara:</label>
                                    <p><?= $detail->SKPD_PENYELENGGARA ?? 'N/A' ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Jumlah Peserta:</label>
                                    <p><?= $detail->JML_PESERTA ?? 'N/A' ?></p>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <a href="<?= base_url('admin/kegiatan'); ?>" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                    <a href="<?= base_url('admin/print_rekap/' . (isset($detail) && isset($detail->ID_KEGIATAN) ? $detail->ID_KEGIATAN : '')); ?>" class="btn btn-primary" target="_blank">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
                                </div>
                            </div>

                            <?php else: ?>
                            <div class="alert alert-warning">
                                Data tidak ditemukan!
                            </div>
                            <a href="<?= base_url('admin/kegiatan'); ?>" class="btn btn-secondary">
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
                            <h6 class="m-0 font-weight-bold text-primary">Daftar Peserta yang Login</h6>
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
                                            <th scope="col">OPD</th>
                                            <th scope="col">Jabatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($peserta && count($peserta) > 0): ?>
                                            <?php foreach($peserta as $key => $p): ?>
                                            <tr>
                                                <td><?= $key + 1 ?></td>
                                                <td><?= $p->NAMA ?? 'N/A' ?></td>
                                                <td><?= $p->JEN_KEL == 1 ? 'Laki-laki' : 'Perempuan' ?></td>
                                                <td><?= $p->NO_HP ?? 'N/A' ?></td>
                                                <td><?= $p->EMAIL ?? 'N/A' ?></td>
                                                <td><?= $p->NAMA_OPD ?? 'N/A' ?></td>
                                                <td><?= $p->JABATAN ?? 'N/A' ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    <em>Belum ada peserta yang login</em>
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
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

    <!-- End of Main Content -->

