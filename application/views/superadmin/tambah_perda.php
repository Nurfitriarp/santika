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
            <form action="<?= base_url('superadmin/simpan_perda') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="form-group">
                    <label class="font-weight-bold text-dark">Jenis Perangkat Daerah</label>
                        <select name="ID_J-OPD" class="form-control" required>
                            <option value="">-- Pilih Jenis OPD --</option>
                        <?php foreach($jenis_opd as $j): ?>
                            <option value="<?= $j->{'ID_J-OPD'}; ?>">
                            <?= $j->NAMA_OPD; ?>
                            </option>
                        <?php endforeach; ?>
                        </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">Nama Perangkat Daerah</label>
                            <input type="text" name="NAMA_OPD" class="form-control" required autofocus>
                        </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= base_url('superadmin/perda') ?>" class="btn btn-secondary">Batal</a>
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