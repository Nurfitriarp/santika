<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Admin / User</h6>
            <a href="<?= base_url('superadmin/tambah_user'); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Admin
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Perangkat Daerah</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach($all_users as $user): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $user->NAMA; ?></td>
                            <td><?= $user->USERNAME; ?></td>
                            <td><?= $user->PERANGKAT_DAERAH; ?></td>
                            <td>
                                <span class="badge <?= $user->ROLE == 'super_admin' ? 'badge-danger' : 'badge-info'; ?>">
                                    <?= strtoupper($user->ROLE ?? 'ADMIN'); ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= base_url('superadmin/edit_user/'.$user->ID); ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="<?= base_url('superadmin/hapus_user/'.$user->ID); ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Yakin hapus user ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>