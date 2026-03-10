<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h4 class="mt-4 mb-4">Tambah Kegiatan</h4>

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
                    <label>Nama Kegiatan</label>
                    <input type="text" name="NAMA" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Tempat</label>
                    <input type="text" name="TEMPAT" class="form-control">
                </div>
                <div class="form-group">
                    <label>Jam</label>
                    <input type="text" name="JAM" class="form-control">
                </div>
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="TANGGAL" class="form-control">
                </div>
                <div class="form-group">
                    <label>Penyelenggara (SKPD)</label>
                    <input type="text" name="SKPD_PENYELENGGARA" class="form-control">
                </div>
                <div class="form-group">
                    <label>Pimpinan Rapat</label>
                    <input type="text" name="PIMPINAN_RAPAT" class="form-control">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>OPD</label>
                        <select name="ID_OPD" class="form-control">
                            <option value="">-- Pilih OPD --</option>
                            <?php if (!empty($opd)): ?>
                                <?php foreach ($opd as $o): ?>
                                    <option value="<?= $o->ID_OPD ?>"><?= $o->NAMA_OPD ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Jumlah Peserta</label>
                        <input type="number" name="JML_PESERTA" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Jam Pelajaran</label>
                        <input type="text" name="JAM_PELAJARAN" class="form-control">
                    </div>
                </div>

                <a href="<?= base_url('superadmin/kegiatan') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>