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
                        <h1 class="h5 mb-0 text-gray-800 font-weight-bold">My Profile</h1>
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
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= isset($admin) ? $admin->USERNAME : 'User'; ?></span>
                                <img class="img-profile rounded-circle"
                                    src="<?= base_url('assets/img/profile/' . (isset($admin) && isset($admin->FOTO) ? $admin->FOTO : 'default.svg')); ?>">
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

                    <!-- Flash messages -->
                    <?php if($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $this->session->flashdata('success'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $this->session->flashdata('error'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Password Edit Modal -->
                    <div class="modal fade" id="editPasswordModal" tabindex="-1" role="dialog" aria-labelledby="editPasswordModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="editPasswordModalLabel">Ubah Password</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form method="post" action="<?= base_url('admin/change_password'); ?>">
                              <div class="modal-body">
                                  <div class="form-group">
                                      <label for="old_password">Password Lama</label>
                                      <input type="password" class="form-control" name="old_password" id="old_password" required>
                                  </div>
                                  <div class="form-group">
                                      <label for="new_password">Password Baru</label>
                                      <input type="password" class="form-control" name="new_password" id="new_password" required>
                                  </div>
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                  <button type="submit" class="btn btn-primary">Ubah</button>
                              </div>
                          </form>
                        </div>
                      </div>
                    </div>

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"></h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Profile Card -->
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <img src="<?= base_url('assets/img/admin.jpg'); ?>" class="rounded-circle" width="80" height="80" style="object-fit: cover;" alt="Foto Admin">
                                        <div class="ml-4">
                                            <h5 class="mb-1"><?= isset($admin) ? $admin->NAMA : 'Admin Name'; ?></h5>
                                            <p class="text-muted mb-0"><strong><?= isset($admin) ? $admin->ROLE : 'Admin'; ?></strong></p>
                                        </div>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editPasswordModal"><i class="fas fa-key"></i> Edit Password</a>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information Card -->
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Personal Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-3"><strong>ID:</strong> <?= isset($admin) ? $admin->ID : '-'; ?></p>
                                            <p class="mb-3"><strong>Nama:</strong> <?= isset($admin) ? $admin->NAMA : '-'; ?></p>
                                            <p class="mb-3"><strong>Perangkat Daerah:</strong> <?= isset($admin) && isset($admin->PERANGKAT_DAERAH) ? $admin->PERANGKAT_DAERAH : '-'; ?></p>
                                            <p class="mb-3"><strong>Bidang:</strong> <?= isset($admin) ? $admin->BIDANG : '-'; ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-3"><strong>Username:</strong> <?= isset($admin) ? $admin->USERNAME : '-'; ?></p>
                                            <p class="mb-3"><strong>Role:</strong> <span class="badge badge-<?= isset($admin) && $admin->ROLE == 'super_admin' ? 'danger' : 'primary'; ?>"><?= isset($admin) ? strtoupper($admin->ROLE) : '-'; ?></span></p>
                                            <p class="mb-3"><strong>Created At:</strong> <?= isset($admin) ? date('d-m-Y H:i:s', strtotime($admin->created_at)) : '-'; ?></p>
                                            <p class="mb-3"><strong>Updated At:</strong> <?= isset($admin) ? date('d-m-Y H:i:s', strtotime($admin->updated_at)) : '-'; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Log Card -->
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Activity Log</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Activity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>2026-03-05 14:30</td>
                                                    <td><span class="badge badge-success">LOGIN</span> Logged in to admin panel</td>
                                                </tr>
                                                <tr>
                                                    <td>2026-03-05 14:15</td>
                                                    <td><span class="badge badge-info">ADD</span> Added new kegiatan "Sosialisasi Keamanan Informasi"</td>
                                                </tr>
                                                <tr>
                                                    <td>2026-03-04 10:45</td>
                                                    <td><span class="badge badge-warning">EDIT</span> Edited kegiatan "Workshop Teknologi"</td>
                                                </tr>
                                                <tr>
                                                    <td>2026-03-03 16:20</td>
                                                    <td><span class="badge badge-danger">DELETE</span> Deleted kegiatan "Rapat Koordinasi"</td>
                                                </tr>
                                                <tr>
                                                    <td>2026-03-02 11:00</td>
                                                    <td><span class="badge badge-primary">PRINT</span> Printed activity report</td>
                                                </tr>
                                                <tr>
                                                    <td>2026-03-01 09:30</td>
                                                    <td><span class="badge badge-success">LOGIN</span> Logged in to admin panel</td>
                                                </tr>
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
