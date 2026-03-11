<!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <div class="d-none d-lg-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100">
                        <h1 class="h5 mb-0 text-gray-800 font-weight-bold">Jenis Perangkat Daerah</h1>
                    </div>
                    

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                        </li>
                        
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= isset($superadmin) ? $superadmin->USERNAME : 'User'; ?></span>
                                <img class="img-profile rounded-circle"
                                    src="<?= base_url('assets/img/profile/' . (isset($superadmin) && isset($superadmin->GAMBAR) ? $superadmin->GAMBAR : 'default.svg')); ?>">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Flashdata Alert -->
                    <?php $CI =& get_instance(); ?>
                    <?php if ($CI->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $CI->session->flashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"></h1>
                        <a href="<?= base_url('superadmin/tambahjenispd'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                            class="fas fa-plus fa-sm text-white-50"></i> Tambah Jenis Perangkat Daerah</a>
                    </div>

                    <!-- Content Row -->

                    <div class="row justify-content-center">
    <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    
                    <h6 class="m-0 font-weight-bold text-primary">Jenis Perangkat Daerah</h6>              
                    <form class="form-inline navbar-search" method="POST" action="<?= base_url('superadmin/jenispd/search'); ?>">
                        <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control bg-light border-5 small" placeholder="Cari Jenis Perangkat Daerah..."
                                aria-label="Search" aria-describedby="basic-addon2" value="<?= isset($keyword) ? $keyword : ''; ?>">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
                
                <div class="card-body">
                    <!-- Flashdata Error Alert -->
                    <?php if ($CI->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $CI->session->flashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($keyword) && !empty($keyword)): ?>
                        <div class="alert alert-info">
                            Hasil pencarian untuk: <strong><?= htmlspecialchars($keyword); ?></strong>
                            <a href="<?= base_url('superadmin/jeniaspd'); ?>" class="float-right">Bersihkan pencarian</a>
                        </div>
                    <?php endif; ?>
                    <?php if(!empty($kegiatan)): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Jenis Perangkat Daerah</th>
                                <th class="text-center" width="auto">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $no = 1; foreach($kegiatan as $row): ?>
                            <tr>
                                <th scope="row"><?= $no++; ?></th>
                                <td><?= $row->NAMA_OPD ?></td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <div class="d-flex justify-content-center align-items-center" style="gap: 5px; min-width: 120px;">
                                        <a href="<?= base_url('superadmin/editpd/'. $row->ID_J-OPD); ?>" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit text-white"></i>
                                        </a>
                                        <a href="<?= base_url('superadmin/hapuspd/'. $row->ID_KEGIATAN); ?>" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus kegiatan ini?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <?php if(isset($keyword) && !empty($keyword)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-search"></i> <strong>Tidak Ada Hasil!</strong><br>
                                Pencarian untuk "<strong><?= htmlspecialchars($keyword); ?></strong>" tidak menemukan data. Coba gunakan kata kunci yang berbeda.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="fas fa-info-circle"></i> <strong>Tidak Ada Data</strong><br>
                                Belum ada jenis perangkat daerah. Silakan <a href="<?= base_url('superadmin/tambahjenispd'); ?>">tambah jenis perangkat daerah baru</a>.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
</div>

                    
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        <!-- End of Main Content -->

        <script>
            // Auto dismiss alert setelah 3 detik
            document.addEventListener('DOMContentLoaded', function() {
                const alerts = document.querySelectorAll('.alert:not(.alert-warning)');
                alerts.forEach(function(alert) {
                    setTimeout(function() {
                        alert.style.transition = 'opacity 0.5s ease-out';
                        alert.style.opacity = '0';
                        setTimeout(function() {
                            alert.remove();
                        }, 500);
                    }, 3000);
                });
            });
        </script>
