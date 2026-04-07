<!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
           <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('superadmin'); ?>">
            <div class="sidebar-brand-icon">
                <img src="<?= base_url('assets/img/logokabmal.png'); ?>" width="40" alt="Logo">
            </div>
            <div class="sidebar-brand-text mx-3 text-left">
                <div style="font-size: 16px; font-weight: 700;">DAHAR</div>
                <div style="font-size: 11px; font-weight: 300; margin-bottom: -5px;">Daftar hadir</div>
            </div>
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
            
              <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Master</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?= base_url('superadmin/jenispd'); ?>">Jenis Perangkat Daerah</a>
                        <a class="collapse-item" href="<?= base_url('superadmin/perda'); ?>">Perangkat Daerah</a>
                </div>
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