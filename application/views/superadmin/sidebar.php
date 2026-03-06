<!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('admin'); ?>">
                <div class="sidebar-brand-icon">
                    <img src="<?= base_url('assets/img/logo.png'); ?>" width="60" alt="Logo">
                </div>
                <div class="sidebar-brand-text mx-3">Santika</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <?php $current_segment = $this->uri->segment(2); ?>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?php echo ($current_segment == 'dashboard' || empty($current_segment)) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('superadmin'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Nav Item - Kelola Kegiatan -->
            <li class="nav-item <?php echo ($current_segment == 'kelola_user') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('superadmin/kelola_user'); ?>">
                    <i class="fas fa-fw fa-calendar-alt"></i>
                    <span>Kelola User</span></a>
            </li>

            <!-- Nav Item - Kegiatan -->
            <li class="nav-item <?php echo ($current_segment == 'kegiatan') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('superadmin/kegiatan'); ?>">
                    <i class="fas fa-fw fa-calendar-alt"></i>
                    <span>Kegiatan</span></a>
            </li>

            <!-- Nav Item - Rekap -->
            <li class="nav-item <?php echo ($current_segment == 'rekap') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('superadmin/rekap'); ?>">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Rekap</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            <!-- Nav Item - Logout -->
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('superadmin/logout'); ?>" onclick="return confirm('Apakah Anda yakin ingin logout?')">
                    <i class="fas fa-fw fa-sign-out-alt"></i>
                    <span>Logout</span></a>
            </li>
        </ul>
        <!-- End of Sidebar -->