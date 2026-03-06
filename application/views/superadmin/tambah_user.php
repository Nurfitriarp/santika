<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h4 class="mt-4 mb-4">Tambah User</h4>

    <?php $CI =& get_instance(); ?>
    <?php if ($CI->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $CI->session->flashdata('success') ?></div>
    <?php endif; ?>
    <?php if ($CI->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $CI->session->flashdata('error') ?></div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="<?= base_url('superadmin/simpan') ?>" method="post">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="NAMA" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Perangkat Daerah</label>
                    <input type="text" name="PERANGKAT_DAERAH" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Bidang</label>
                    <input type="text" name="BIDANG" class="form-control">
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="USERNAME" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="PASSWORD" class="form-control" required>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold">Role</label>
                        <select name="role" class="form-control" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin">Admin</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Foto Profil</label>
                    <div class="custom-file">
                        <input type="file" name="gambar" class="custom-file-input" id="customFile" accept="image/*">
                        <label class="custom-file-label" for="customFile">Pilih file foto...</label>
                    </div>
                        <small class="text-muted">Format: JPG, PNG, JPEG. Maks: 2MB.</small>
                    </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= base_url('superadmin/kelola_user') ?>" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>