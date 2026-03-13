<div class="container-fluid">
    <div class="card shadow mb-4 mt-4">
        <div class="card-header py-3">
            <h1 class="h3 mb-0 text-gray-800">Edit Jenis Perangkat Daerah</h1>
        </div>
        <div class="card-body">
        <form action="<?= base_url('superadmin/update_opd'); ?>" method="POST">
            <input type="hidden" name="ID_OPD" value="<?= $opd_item->ID_OPD; ?>">

            <div class="form-group">
                <label>Nama Perangkat Daerah</label>
                <input type="text" name="NAMA_OPD" class="form-control" value="<?= $opd_item->NAMA_OPD; ?>" required>
            </div>

            <div class="form-group">
                <label>Jenis Perangkat Daerah</label>
                <select name="ID_J_OPD" class="form-control" required>
                    <?php foreach($jenis_opd as $j): ?>
                        <option value="<?= $j->{'ID_J-OPD'}; ?>" <?= ($j->{'ID_J-OPD'} == $opd_item->{'ID_J-OPD'}) ? 'selected' : ''; ?>>
                            <?= $j->NAMA_OPD; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="<?= base_url('superadmin/perda'); ?>" class="btn btn-secondary">Batal</a>
        </form>
        </div>
    </div>
</div>
</div>
</div>