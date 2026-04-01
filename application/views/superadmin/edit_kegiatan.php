<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
        <div class="container-fluid">
            <h4 class="mt-4 mb-4 text-gray-800 font-weight-bold">Edit Kegiatan</h4>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="<?= base_url('superadmin/update') ?>" method="post">
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
                                <label>Jam</label>
                                <input type="text" name="JAM" class="form-control" value="<?= $kegiatan->JAM ?>">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Tanggal</label>
                                <input type="date" name="TANGGAL" class="form-control" value="<?= $kegiatan->TANGGAL ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Penyelenggara Kegiatan</label>
                            <input type="text" name="SKPD_PENYELENGGARA" class="form-control" value="<?= $kegiatan->SKPD_PENYELENGGARA ?>">
                        </div>

                        <div class="form-group">
                            <label>Pimpinan Rapat</label>
                            <input type="text" name="PIMPINAN_RAPAT" class="form-control" value="<?= $kegiatan->PIMPINAN_RAPAT ?>">
                        </div>

                        <?php 
                            // Inisialisasi array untuk menampung data lama
                            $saved_values = []; // Untuk menampung ID (misal: "JENIS_1", "24")
                            $saved_jml    = []; // Untuk menampung Jumlah (misal: "50", "10")

                            // 1. Cek apakah data ID_OPD dari database tidak kosong
                            if(!empty($kegiatan->ID_OPD)){
                                // Pecah string berdasarkan koma (hasil: ["JENIS_1:50", "24:10"])
                                $pairs = explode(',', $kegiatan->ID_OPD);
                                
                                foreach($pairs as $p){
                                    // Pecah lagi berdasarkan titik dua (hasil: ["JENIS_1", "50"])
                                    $part = explode(':', $p);
                                    
                                    // Pastikan formatnya valid (ada ID dan ada Jumlah)
                                    if(count($part) == 2){
                                        $saved_values[] = $part[0]; // Simpan ID/Identitas
                                        $saved_jml[]    = $part[1]; // Simpan Jumlahnya
                                    }
                                }
                            }

                            // 2. Gabungkan kembali array jumlah menjadi string koma untuk isi input teks
                            $string_jml = implode(',', $saved_jml); // Hasil: "50,10"
                        ?>

                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <label class="font-weight-bold text-primary">Perangkat Daerah / Jenis (Kolektif)</label>
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
                                            <option value="<?= $o->ID_OPD ?>" 
                                                    data-jenis="JENIS_<?= $o->{'ID_OPD'} ?>" 
                                                    data-type="individual"
                                                    <?= in_array($o->ID_OPD, $saved_values) ? 'selected' : '' ?>>
                                                <?= $o->NAMA_OPD ?>
                                            </option>
                                        <?php endforeach; endif; ?>
                                    </optgroup>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="font-weight-bold text-dark">Jumlah Peserta (Pisahkan koma)</label>
                                <input type="text" name="JML_PESERTA" id="jml_peserta_input" class="form-control" 
                                    value="<?= $string_jml ?>" placeholder="Contoh: 12,14,18">
                                <small class="text-danger" id="error-koma" style="display:none;">* Jumlah angka tidak sesuai!</small>
                            </div>
                        </div>

<script>
$(document).ready(function() {
    var $select = $('#ID_OPD').select2({
        placeholder: " Pilih instansi...",
        allowClear: true,
        width: '100%'
    });

    function updateHelper() {
        var selectedData = $select.select2('data');
        var names = [];
        selectedData.forEach(function(item) {
            names.push(item.text);
        });
        $('#list-urutan').text(names.length > 0 ? names.join(' → ') : '-');
    }

    updateHelper(); // Jalankan saat load

    $select.on('change', function() {
        updateHelper();
    });

    // Logika Auto-Select Kolektif
    $select.on('select2:select', function (e) {
        var data = e.params.data;
        var $el = $(data.element);
        
        if ($el.data('type') === 'group') {
            var jenisId = data.id; 
            var currentValues = $select.val() || [];

            $(`#ID_OPD option[data-type="individual"][data-jenis='${jenisId}']`).each(function() {
                if (currentValues.indexOf($(this).val()) === -1) {
                    currentValues.push($(this).val());
                }
            });

            // Hapus tag [SEMUA] agar tidak tersimpan sebagai ID tunggal
            currentValues = currentValues.filter(id => id !== jenisId);
            $select.val(currentValues).trigger('change');
        }
    });
});
</script>

                        <hr>
                        <a href="<?= base_url('superadmin/kegiatan') ?>" class="btn btn-secondary shadow-sm">Batal</a>
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

<script>
    $select.on('select2:select', function (e) {
    var data = e.params.data;
    var $element = $(data.element);
    
    // Jika yang diklik adalah tipe 'group' (Kolektif)
    if ($element.data('type') === 'group') {
        var jenisId = data.id; // Ini akan berisi "JENIS_1", "JENIS_2", dst
        var currentValues = $select.val() || [];

        // Cari semua opsi individual yang memiliki data-jenis sama dengan ID yang diklik
        $(`#ID_OPD option[data-type="individual"][data-jenis='${jenisId}']`).each(function() {
            var val = $(this).val();
            if (currentValues.indexOf(val) === -1) {
                currentValues.push(val);
            }
        });

        // Update Select2 dengan nilai baru
        $select.val(currentValues).trigger('change');
        
        // Hapus pilihan "[SEMUA]" agar tidak ikut tersimpan sebagai ID di database
        setTimeout(function() {
            currentValues = currentValues.filter(id => id !== jenisId);
            $select.val(currentValues).trigger('change');
        }, 100);
    }
});
</script>