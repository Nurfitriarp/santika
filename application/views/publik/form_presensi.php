<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Presensi Peserta - <?= $kegiatan->NAMA ?></title>
    
    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <style>
        body { background-color: #f8f9fc; }
        .card { border-radius: 15px; overflow: hidden; margin-top: 20px; }
        .header-presensi { background: #4e73df; color: white; padding: 20px; }
        
        .wrapper-canvas {
            border: 2px dashed #d1d3e2;
            border-radius: 10px;
            background: #fff;
            position: relative;
            touch-action: none; 
        }
        .signature-pad { width: 100%; height: 200px; cursor: crosshair; }
        
        #daftar_nama_saran {
            position: absolute;
            z-index: 9999;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            background-color: #ffffff;
            border: 1px solid #d1d3e2;
            display: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .saran-item { padding: 12px 15px; border-bottom: 1px solid #f1f1f1; cursor: pointer; }
        .saran-item:hover { background: #4e73df; color: white; }

        .select2-container--default .select2-selection--single {
            border: 1px solid #d1d3e2 !important;
            height: calc(1.5em + 0.75rem + 2px) !important;
        }
    </style>
</head>
<body>
    <div class="container mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow border-0">
                    <div class="header-presensi text-center">
                        <h4 class="font-weight-bold mb-0">DAFTAR HADIR</h4>
                        <p class="mb-0 small text-white-50"><?= $kegiatan->NAMA ?></p>
                    </div>
                    <div class="card-body">
                        <?php if($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                        <?php endif; ?>

                        <form action="<?= base_url('presensi/kirim') ?>" method="POST" id="formUtama">
                            <input type="hidden" name="ID_KEGIATAN" value="<?= $kegiatan->ID_KEGIATAN ?>">

                            <div class="form-group position-relative">
                                <label class="font-weight-bold text-dark">Nama Lengkap</label>
                                <input type="text" name="NAMA" id="input_nama" class="form-control" 
                                       placeholder="Ketik Nama Lengkap Anda..." autocomplete="off" required>
                                <div id="daftar_nama_saran"></div>
                            </div>  

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="font-weight-bold text-dark">Jenis Kelamin</label>
                                    <select name="JEN_KEL" id="jen_kel" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="font-weight-bold text-dark">No. HP / WhatsApp</label>
                                    <input type="number" name="NO_HP" id="no_hp" class="form-control" placeholder="08..." required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold text-dark">Kategori Instansi</label>
                                <select id="jenis_opd" class="form-control" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach($jenis_opd as $j): ?>
                                        <?php $row = (array)$j; $keys = array_keys($row); ?>
                                        <option value="<?= $row[$keys[0]] ?>"><?= $row[$keys[1]] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold text-dark">Instansi / Perangkat Daerah</label>
                                <select name="ID_OPD" id="id_opd" class="form-control" required>
                                    <option value="">-- Pilih Kategori Terlebih Dahulu --</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold text-dark">Jabatan</label>
                                <select name="JABATAN" class="form-control" required>
                                    <option value="">-- Pilih Jabatan --</option>
                                    <?php foreach($list_jabatan as $j): ?>
                                        <option value="<?= $j->NAMA_JABATAN ?>"><?= $j->NAMA_JABATAN ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold text-dark">Email (Opsional)</label>
                                <input type="email" name="EMAIL" id="email" class="form-control" placeholder="nama@email.com">
                            </div>
                            
                            <div class="form-group">
                                <label class="font-weight-bold text-dark">Tanda Tangan</label>
                                <div class="wrapper-canvas">
                                    <canvas id="signature-pad" class="signature-pad"></canvas>
                                </div>
                                <input type="hidden" name="TTD" id="ttd_image">
                                <button type="button" id="btn-hapus-ttd" class="btn btn-sm btn-outline-danger mt-2">
                                    <i class="fas fa-eraser"></i> Hapus
                                </button>
                            </div>

                            <button type="submit" id="btnSubmit" class="btn btn-primary btn-block btn-lg mt-4 shadow-lg">
                                <i class="fas fa-paper-plane mr-2"></i> Kirim Presensi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <script>
    $(document).ready(function() {
    const allOpd = <?= json_encode($opd) ?>;
    const canvas = document.getElementById('signature-pad');
    const signaturePad = new SignaturePad(canvas);

    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        signaturePad.clear();
    }
    window.addEventListener("resize", resizeCanvas);
    resizeCanvas();

    $('#btn-hapus-ttd').on('click', function() { signaturePad.clear(); });

    // --- 1. SARAN NAMA ---
    $('#input_nama').on('input', function() {
    let term = $(this).val();
    console.log("Mengetik: " + term); // DEBUG 1

    if (term.length < 2) { // Kita turunkan ke 2 huruf agar lebih cepat muncul
        $('#daftar_nama_saran').hide();
        return;
    }

    $.ajax({
        url: "<?= base_url('presensi/get_saran_nama') ?>",
        type: "GET",
        data: { term: term },
        dataType: "json",
        success: function(data) {
            console.log("Data diterima dari server:", data); // DEBUG 2
            if (data.length > 0) {
                let html = '';
                data.forEach(item => {
                    html += `
                    <div class="saran-item" 
                         data-nama="${item.NAMA}" 
                         data-jenkel="${item.JEN_KEL}" 
                         data-jabatan="${item.JABATAN}" 
                         data-hp="${item.NO_HP}" 
                         data-email="${item.EMAIL}"
                         data-skpd="${item.SKPD}">
                        <strong>${item.NAMA}</strong><br>
                        <small>${item.SKPD}</small>
                    </div>`;
                });
                $('#daftar_nama_saran').html(html).show();
            } else {
                $('#daftar_nama_saran').hide();
            }
        },
        error: function(xhr, status, error) {
            console.error("Kesalahan AJAX:", error); // DEBUG 3
            console.log(xhr.responseText);
        }
    });
});
    // --- 2. KLIK ITEM SARAN (AUTOFILL) ---
    $(document).on('click', '.saran-item', function() {
        const d = $(this).data();
        $('#input_nama').val(d.nama);
        $('#jen_kel').val(d.jenkel);
        $('#jabatan').val(d.jabatan);
        $('#no_hp').val(d.hp);
        $('#email').val(d.email);

        // Autofill Dropdown Instansi
        const targetSkpd = d.skpd;
        if (targetSkpd) {
            const found = allOpd.find(o => o.NAMA_OPD.trim() === targetSkpd.trim());
            if (found) {
                $('#jenis_opd').val(found['ID_J-OPD']).trigger('change');
                setTimeout(() => {
                    $('#id_opd').val(found.ID_OPD).trigger('change');
                }, 100);
            }
        }
        $('#daftar_nama_saran').hide();
    });

    // --- 3. FILTER OPD ---
    $('#id_opd').select2({ width: '100%' });
    $('#jenis_opd').on('change', function() {
        const val = $(this).val();
        $('#id_opd').empty().append('<option value="">-- Pilih Instansi --</option>');
        allOpd.filter(i => i['ID_J-OPD'] == val).forEach(o => {
            $('#id_opd').append(new Option(o.NAMA_OPD, o.ID_OPD));
        });
        $('#id_opd').trigger('change');
    });

    // --- 4. SUBMIT ---
    $('#formUtama').on('submit', function(e) {
        if (signaturePad.isEmpty()) {
            alert("Silakan bubuhkan tanda tangan terlebih dahulu.");
            return false; 
        } else {
            // PROSES KRUSIAL: Ambil gambar dari canvas
            var dataURL = signaturePad.toDataURL('image/png');
            // Masukkan ke input hidden
            $('#ttd_image').val(dataURL); 
            return true; 
        }
    });

    // Tutup saran jika klik luar
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#input_nama, #daftar_nama_saran').length) {
            $('#daftar_nama_saran').hide();
        }
    });
});
    </script>

</body>
</html>