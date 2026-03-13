<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <div class="container-fluid">
            <h4 class="mt-4 mb-4 text-gray-800 font-weight-bold">Edit Kegiatan</h4>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="<?= base_url('admin/update') ?>" method="post">
                        <input type="hidden" name="ID_KEGIATAN" value="<?= $kegiatan->ID_KEGIATAN ?>">

                        <div class="form-group">
                            <label>Nama Kegiatan</label>
                            <input type="text" name="NAMA" class="form-control" value="<?= $kegiatan->NAMA ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Tempat</label>
                                <input type="text" name="TEMPAT" class="form-control" value="<?= $kegiatan->TEMPAT ?>">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Tanggal</label>
                                <input type="date" name="TANGGAL" class="form-control" value="<?= $kegiatan->TANGGAL ?>">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Jam</label>
                                <input type="text" name="JAM" class="form-control" value="<?= $kegiatan->JAM ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Penyelenggara (SKPD)</label>
                            <input type="text" name="SKPD_PENYELENGGARA" class="form-control" value="<?= $kegiatan->SKPD_PENYELENGGARA ?>">
                        </div>

                        <div class="form-group">
                            <label>Pimpinan Rapat</label>
                            <input type="text" name="PIMPINAN_RAPAT" class="form-control" value="<?= $kegiatan->PIMPINAN_RAPAT ?>">
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>OPD</label>
                                <select name="ID_OPD" class="form-control">
                                    <option value="">-- Pilih OPD --</option>
                                    <?php foreach ($opd as $o): ?>
                                        <option value="<?= $o->ID_OPD ?>" <?= ($o->ID_OPD == $kegiatan->ID_OPD) ? 'selected' : '' ?>>
                                            <?= $o->NAMA_OPD ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Jumlah Peserta</label>
                                <input type="number" name="JML_PESERTA" class="form-control" value="<?= $kegiatan->JML_PESERTA ?>">
                            </div>
                        </div>
                        

                        <hr>
                        <a href="<?= base_url('admin/kegiatan') ?>" class="btn btn-secondary shadow-sm">Batal</a>
                        <button type="submit" class="btn btn-warning font-weight-bold shadow-sm">
                            <i class="fas fa-save fa-sm"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
                                    </div>