<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit User: <?= $user_item->USERNAME; ?></h6>
        </div>
        <div class="card-body">
            <form action="<?= base_url('superadmin/update_user'); ?>" method="POST">
                <input type="hidden" name="id" value="<?= $user_item->ID; ?>">
                
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="<?= $user_item->NAMA; ?>" required>
                </div>

                <div class="form-group">
                    <label>Perangkat Daerah</label>
                    <input type="text" name="pd" class="form-control" value="<?= $user_item->{'PERANGKAT_DAERAH'}; ?>" required>
                </div>

                <div class="form-group">
                    <label>Bidang</label>
                    <input type="text" name="bidang" class="form-control" value="<?= $user_item->BIDANG; ?>" required>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="<?= $user_item->USERNAME; ?>" required>
                </div>

                <div class="form-group">
                    <label>Password (Kosongkan jika tidak ingin diubah)</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password baru...">
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select name="role" class="form-control">
                        <option value="admin" <?= $user_item->ROLE == 'admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="super_admin" <?= $user_item->ROLE == 'super_admin' ? 'selected' : ''; ?>>Super Admin</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="<?= base_url('superadmin/kelola_user'); ?>" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>