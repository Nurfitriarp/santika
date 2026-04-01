<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">

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
            <form action="<?= base_url('superadmin/simpan_user') ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Perangkat Daerah</label>
                    <select name="pd" id="select-opd" class="form-control" required>
                        <option value="">-- Cari & Pilih Perangkat Daerah --</option>
                        <?php foreach($list_opd as $opd): ?>
                            <option value="<?= $opd->NAMA_OPD ?>"><?= $opd->NAMA_OPD ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Bidang</label>
                    <input type="text" name="bidang" class="form-control">
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
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
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold">Foto Profil</label>
                        <div class="custom-file">
                            <input type="file" name="gambar" class="custom-file-input" id="customFile" accept="image/*">
                            <label class="custom-file-label" for="customFile">Pilih file foto...</label>
                        </div>
                        <small class="text-muted">Format: JPG, PNG, JPEG. Maks: 2MB.</small>
                    </div>
                </div> <hr>
                <button type="submit" class="btn btn-primary shadow-sm">
                    <i class="fas fa-save fa-sm"></i> Simpan User
                </button>
                <a href="<?= base_url('superadmin/kelola_user') ?>" class="btn btn-secondary shadow-sm">Batal</a>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // 1. Inisialisasi Select2 untuk Fitur Ketik & Filter
        $('#select-opd').select2({
            theme: 'bootstrap4',
            placeholder: "-- Cari Perangkat Daerah --",
            allowClear: true
        });

        // 2. Script Upload File Milik Anda (Tetap Dipertahankan)
        document.getElementById('customFile').addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                var fileName = e.target.files[0].name;
                var label = e.target.nextElementSibling;
                label.innerText = fileName;
            }
        });
    });
</script>