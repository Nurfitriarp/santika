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
                <h1 class="h5 mb-0 text-gray-800 font-weight-bold">Rekap Kegiatan</h1>
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
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                        <img class="img-profile rounded-circle"
                            src="img/undraw_profile.svg">
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Profile
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                            Settings
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                            Activity Log
                        </a>
                        <div class="dropdown-divider"></div>
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
            <?php if ($CI->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $CI->session->flashdata('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800"></h1>
            </div>

            <!-- Content Row -->

            <div class="row justify-content-center">
                <div class="col-xl-12 col-lg-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            
                            <h6 class="m-0 font-weight-bold text-primary">REKAP KEGIATAN</h6>              
                            <form class="form-inline navbar-search" method="POST" action="<?= base_url('admin/search'); ?>">
                                <div class="input-group">
                                    <input type="text" name="keyword" class="form-control bg-light border-5 small" placeholder="Cari Kegiatan..."
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
                            <?php if(isset($keyword) && !empty($keyword)): ?>
                                <div class="alert alert-info">
                                    Hasil pencarian untuk: <strong><?= htmlspecialchars($keyword); ?></strong>
                                    <a href="<?= base_url('admin/rekap'); ?>" class="float-right">Bersihkan pencarian</a>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($kegiatan)): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Tanggal</th>
                                        <th>Tempat</th>
                                        <th>Pemimpin Rapat</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $no = 1; foreach($kegiatan as $row): ?>
                                    <tr>
                                        <th scope="row"><?= $no++; ?></th>
                                        <td><?= $row->NAMA ?></td>
                                        <td><?= $row->TANGGAL ?></td>
                                        <td><?= $row->TEMPAT ?></td>
                                        <td><?= $row->PIMPINAN_RAPAT ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                                <div class="alert alert-warning text-center">Data tidak ditemukan.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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