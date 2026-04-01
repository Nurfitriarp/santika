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
                            <label>Penyelenggara</label>
                            <input type="text" name="SKPD_PENYELENGGARA" class="form-control" value="<?= $kegiatan->SKPD_PENYELENGGARA ?>">
                        </div>

                        <div class="form-group">
                            <label>Pimpinan Rapat</label>
                            <input type="text" name="PIMPINAN_RAPAT" class="form-control" value="<?= $kegiatan->PIMPINAN_RAPAT ?>">
                        </div>

                        <?php 
                            $saved_values = []; 
                            $saved_jml    = []; 
                            if(!empty($kegiatan->ID_OPD)){
                                $pairs = explode(',', $kegiatan->ID_OPD);
                                foreach($pairs as $p){
                                    $part = explode(':', $p);
                                    if(count($part) == 2){
                                        $saved_values[] = $part[0]; 
                                        $saved_jml[]    = $part[1]; 
                                    }
                                }
                            }
                            $string_jml = implode(',', $saved_jml);
                        ?>

                        <select class="form-control select2-multiple" name="ID_OPD[]" id="ID_OPD" multiple="multiple" style="width: 100%;" required>
                            <optgroup label="PILIH BERDASARKAN JENIS (KOLEKTIF)">
                                <?php if(!empty($jenis_opd)): foreach ($jenis_opd as $j): ?>
                                    <option value="JENIS_<?= $j->{'ID_J-OPD'} ?>" data-type="group"
                                        <?= in_array("JENIS_".$j->{'ID_J-OPD'}, $saved_values) ? 'selected' : '' ?>>
                                        [SEMUA] <?= $j->NAMA_OPD ?>
                                    </option>
                                <?php endforeach; endif; ?>
                            </optgroup>
                            <optgroup label="PILIH PERANGKAT DAERAH (INDIVIDU)">
                                <?php if(!empty($opd)): foreach ($opd as $o): ?>
                                    <option value="<?= $o->ID_OPD ?>" data-jenis="JENIS_<?= $o->{'ID_J-OPD'} ?>" data-type="individual"
                                        <?= in_array($o->ID_OPD, $saved_values) ? 'selected' : '' ?>>
                                        <?= $o->NAMA_OPD ?>
                                    </option>
                                <?php endforeach; endif; ?>
                            </optgroup>
                        </select>

                        <input type="text" name="JML_PESERTA" id="jml_peserta_input" class="form-control" value="<?= $string_jml ?>">
                        

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