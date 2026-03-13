<div class="container-fluid">
    <div class="card shadow mb-4 mt-4">
        <div class="card-header py-3">
            <h1 class="h3 mb-0 text-gray-800">Edit Jenis Perangkat Daerah</h1>
        </div>
        <div class="card-body">
            <form action="<?= base_url('superadmin/update_jenispd'); ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                    <div class="form-group">
                        <label >ID Jenis OPD (Kode)</label>
                        <input type="text" name="ID_J-OPD" class="form-control" value="<?= $jenispd->{'ID_J-OPD'}; ?>" readonly required>
                    </div>

                    <div class="form-group">
                        <label >Nama Jenis Perangkat Daerah</label>
                        <input type="text" name="NAMA_OPD" class="form-control" value="<?= $jenispd->{'NAMA_OPD'}; ?>"required>
                    </div>
    
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="<?= base_url('superadmin/jenispd'); ?>" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
</div>
</div>