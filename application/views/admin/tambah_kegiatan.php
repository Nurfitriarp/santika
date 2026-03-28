<div id="content-wrapper" class="d-flex flex-column">

    <div id="content">

        <div class="container-fluid">
            <h4 class="mt-4 mb-4">Tambah Kegiatan</h4>

            <?php $CI =& get_instance(); ?>
            <?php if ($CI->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show msg-auto-hide" role="alert">
                    <?= $CI->session->flashdata('success') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if ($CI->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show msg-auto-hide" role="alert">
                    <?= $CI->session->flashdata('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="<?= base_url('admin/simpan') ?>" method="post">
                        <div class="form-group">
                            <label class="font-weight-bold text-dark">Nama Kegiatan</label>
                            <input type="text" name="NAMA" class="form-control" placeholder="Masukkan nama kegiatan" required>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="font-weight-bold text-dark">Tempat</label>
                                <input type="text" name="TEMPAT" class="form-control" placeholder="Lokasi kegiatan">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold text-dark">Jam</label>
                                <input type="text" name="JAM" class="form-control" placeholder="Contoh : 09.00 - 12.00">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="font-weight-bold text-dark">Tanggal</label>
                                <input type="date" name="TANGGAL" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                        <label class="font-weight-bold text-primary">Penyelenggara Kegiatan</label>
                        <input type="text" name="SKPD_PENYELENGGARA" class="form-control bg-light font-weight-bold" 
                            value="<?= isset($admin->PERANGKAT_DAERAH) ? $admin->PERANGKAT_DAERAH : ''; ?>" 
                            readonly>
                        <small class="text-info">* Instansi Anda terdeteksi otomatis.</small>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Pimpinan Rapat</label>
                        <input type="text" name="PIMPINAN_RAPAT" class="form-control" placeholder="Nama pimpinan rapat">
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label class="font-weight-bold text-primary">Perangkat Daerah / Jenis</label>
                            <select class="form-control select2-multiple" name="ID_OPD[]" id="ID_OPD" multiple="multiple" style="width: 100%;" required>
                                <optgroup label="PILIH BERDASARKAN JENIS (KOLEKTIF)">
                                    <?php if(!empty($jenis_opd)): foreach ($jenis_opd as $j): ?>
                                        <option value="JENIS_<?= $j->{'ID_J-OPD'} ?>" data-type="group">[SEMUA] <?= $j->NAMA_OPD ?></option>
                                    <?php endforeach; endif; ?>
                                </optgroup>
                                <optgroup label="PILIH PERANGKAT DAERAH (INDIVIDU)">
                                    <?php if(!empty($opd)): foreach ($opd as $o): ?>
                                        <option value="<?= $o->ID_OPD ?>" data-jenis="JENIS_<?= $o->{'ID_OPD'} ?>" data-type="individual"><?= $o->NAMA_OPD ?></option>
                                    <?php endforeach; endif; ?>
                                </optgroup>
                            </select>
                            
                            <div id="urutan-helper" class="mt-2 small text-muted" style="display:none;">
                                Urutan Input: <span id="list-urutan" class="font-weight-bold text-dark"></span>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label class="font-weight-bold text-dark">Jumlah Peserta (Pisahkan dengan koma)</label>
                            <input type="text" name="JML_PESERTA" id="jml_peserta_input" class="form-control" placeholder="Contoh: 10,15,5">
                            <small class="text-danger" id="error-koma" style="display:none;">* Jumlah angka harus sama dengan jumlah instansi!</small>
                        </div>
                    </div>

                        <div class="mt-4 text-right">
                            <hr>
                            <a href="<?= base_url('admin/kegiatan') ?>" class="btn btn-secondary shadow-sm mr-2">
                                <i class="fas fa-arrow-left fa-sm"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary shadow-sm">
                                <i class="fas fa-save fa-sm"></i> Simpan Kegiatan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Alert Notifikasi tetap hilang otomatis (success/error)
        const autoHideAlerts = document.querySelectorAll('.msg-auto-hide');
        autoHideAlerts.forEach(function(alert) {
            setTimeout(function() {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            }, 3000);
        });
    });

    $(document).ready(function() {
        // 1. Inisialisasi Select2
        var $select = $('#ID_OPD').select2({
            placeholder: " Cari dan pilih...",
            allowClear: true,
            width: '100%'
        });

        // 2. Fungsi untuk menampilkan daftar pilihan di bawah
        function updatePreview() {
            var selectedData = $select.select2('data');
            var $area = $('#badge-area');
            var $container = $('#preview-selected');
            
            $area.empty(); // Kosongkan area sebelum diisi ulang

            if (selectedData.length > 0) {
                $container.show();
                selectedData.forEach(function(item) {
                    // Buat tampilan badge biru ala Bootstrap
                    var badge = `<span class="badge badge-primary m-1 p-2" style="font-size: 13px;">
                                    <i class="fas fa-check-circle mr-1"></i> ${item.text}
                                </span>`;
                    $area.append(badge);
                });
            } else {
                $container.hide();
            }
        }

        // 3. Jalankan fungsi setiap kali ada perubahan
        $select.on('change', function() {
            updatePreview();
        });

        // 4. Logika Auto-Select Kategori (Tetap dipertahankan)
        $select.on('select2:select', function (e) {
            var data = e.params.data;
            var $element = $(data.element);
            
            if ($element.data('type') === 'group') {
                var jenisId = data.id; 
                var currentValues = $select.val() || [];

                $(`#ID_OPD option[data-type="individual"][data-jenis='${jenisId}']`).each(function() {
                    var val = $(this).val();
                    if (currentValues.indexOf(val) === -1) {
                        currentValues.push(val);
                    }
                });

                $select.val(currentValues).trigger('change');
            }
        });
    });

</script>
