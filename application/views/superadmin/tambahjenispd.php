<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">
        
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h4 class="mt-4 mb-4">Tambah Perangkat Daerah</h4>

    <?php $CI =& get_instance(); ?>
    <?php if ($CI->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $CI->session->flashdata('success') ?></div>
    <?php endif; ?>
    <?php if ($CI->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $CI->session->flashdata('error') ?></div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="<?= base_url('superadmin/simpan_jenispd'); ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label >ID Jenis OPD (Kode)</label>
                    <input type="text" name="ID_J-OPD" class="form-control" required>
                </div>

                <div class="form-group">
                    <label >Nama Jenis Perangkat Daerah</label>
                    <input type="text" name="NAMA_OPD" class="form-control" required>
                </div>
                                
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= base_url('superadmin/jenispd') ?>" class="btn btn-secondary">Batal</a>

            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('customFile').addEventListener('change', function(e) {
        var fileName = e.target.files[0].name;
        var label = e.target.nextElementSibling;
        label.innerText = fileName;
    });
</script>