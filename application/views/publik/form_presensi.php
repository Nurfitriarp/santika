<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Presensi Peserta - <?= $kegiatan->NAMA ?></title>
    
    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        body { background-color: #f8f9fc; }
        .card { border-radius: 15px; }
        .header-presensi { background: #4e73df; color: white; padding: 20px; border-radius: 15px 15px 0 0; }
        .signature-pad {
            border: 1px solid #ced4da;
            border-radius: 5px;
            width: 100%;
            height: 200px;
            cursor: crosshair;
            background-color: #fff;
        }
        
        /* FIX: CSS agar box saran muncul di atas elemen lain dan terlihat jelas */
        #daftar_nama_saran {
                position: absolute;
                z-index: 1050;
                width: 100%;
                max-height: 200px;
                overflow-y: auto;
                background-color: #ffffff !important; /* Wajib putih */
                border: 1px solid #d1d3e2;
                display: none;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            }
            .saran-item {
                padding: 10px;
                border-bottom: 1px solid #eee;
                cursor: pointer;
                background: white;
            }
            .saran-item:hover {
                background: #f8f9fc;
            }

        .select2-container--default .select2-selection--single {
            border: 1px solid #d1d3e2 !important;
            border-radius: 0.35rem !important;
            height: calc(1.5em + 0.75rem + 2px) !important;
        }
    </style>
</head>
<body>
    <div class="container mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow border-0">
                    <div class="header-presensi text-center">
                        <h4 class="font-weight-bold mb-0">DAFTAR HADIR</h4>
                        <p class="mb-0 small text-white-50"><?= $kegiatan->NAMA ?></p>
                    </div>
                    <div class="card-body">
                        <p class="text-center text-muted small">Ketik nama Anda. Jika tidak muncul di saran, Anda bisa langsung isi manual.</p>
                        <hr>
                        
                        <form action="<?= base_url('presensi/kirim') ?>" method="POST">
                            <input type="hidden" name="ID_KEGIATAN" value="<?= $kegiatan->ID_KEGIATAN ?>">

                            <div class="form-group position-relative">
                                <label class="font-weight-bold">Nama Lengkap</label>
                                <input type="text" name="NAMA" id="input_nama" class="form-control" 
                                       placeholder="Ketik Nama Lengkap Anda..." autocomplete="off" required>
                                <div id="daftar_nama_saran" class="list-group shadow"></div>
                            </div>  

                            <div class="form-group">
                                <label class="font-weight-bold">Jenis Kelamin</label>
                                <select name="JEN_KEL" id="jen_kel" class="form-control" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Kategori Instansi</label>
                                <select id="jenis_opd" class="form-control" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach($jenis_opd as $j): ?>
                                        <?php $row = (array)$j; $keys = array_keys($row); ?>
                                        <option value="<?= $row[$keys[0]] ?>"><?= $row[$keys[1]] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Instansi / Perangkat Daerah</label>
                                <select name="ID_OPD" id="id_opd" class="form-control" required>
                                    <option value="">-- Pilih Kategori Terlebih Dahulu --</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Jabatan</label>
                                <input type="text" name="JABATAN" id="jabatan" class="form-control" placeholder="Contoh: Analis Data" required>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">No. HP / WhatsApp</label>
                                <input type="number" name="NO_HP" id="no_hp" class="form-control" placeholder="08..." required>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Email</label>
                                <input type="email" name="EMAIL" id="email" class="form-control" placeholder="nama@email.com">
                            </div>
                            
                            <div class="form-group">
                                <label class="font-weight-bold">Tanda Tangan</label>
                                <canvas id="signature-pad" class="signature-pad"></canvas>
                                <input type="hidden" name="TTD" id="ttd_image">
                                <button type="button" id="clear" class="btn btn-sm btn-secondary mt-2">Hapus Tanda Tangan</button>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block btn-lg mt-4 shadow">
                                <i class="fas fa-paper-plane mr-2"></i> Kirim Presensi
                            </button>
                        </form>
                    </div>
                </div>
                <p class="text-center mt-3 text-muted small">&copy; 2026 Diskominfo - ANTIKA</p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

  <script>
    $(document).ready(function() {
    const allOpd = <?= json_encode($opd) ?>;
    let searchTimer;

    // --- A. LOGIKA SARAN NAMA ---
    $('#input_nama').on('input', function() {
        let term = $(this).val();
        let $saranBox = $('#daftar_nama_saran');
        clearTimeout(searchTimer);

        if (term.length < 2) { // Diturunkan ke 2 karakter agar lebih responsif
            $saranBox.hide();
            return;
        }

        searchTimer = setTimeout(function() {
            $.ajax({
                url: "<?= base_url('presensi/get_peserta_by_nama') ?>",
                type: "GET",
                data: { term: term },
                dataType: 'json',
                success: function(data) {
                    console.log("Data diterima:", data); // Pantau hasil di Console
                    if (Array.isArray(data) && data.length > 0) {
                        let html = '';
                        data.forEach(function(item) {
                            // Map data sesuai kolom database KAPITAL
                            html += `<div class="saran-item list-group-item list-group-item-action" 
                                     style="cursor:pointer; padding: 10px; border-bottom: 1px solid #ddd;"
                                     data-nama="${item.NAMA || ''}" 
                                     data-jenkel="${item.JEN_KEL || ''}"
                                     data-jabatan="${item.JABATAN || ''}"
                                     data-hp="${item.NO_HP || ''}"
                                     data-email="${item.EMAIL || ''}">${item.NAMA}</div>`;
                        });
                        $saranBox.html(html).fadeIn();
                    } else {
                        $saranBox.hide();
                    }
                },
                error: function(xhr) {
                    // Jika masih Error 500, detail error akan muncul di tab Console
                    console.error("Error Detail:", xhr.responseText);
                    $saranBox.hide();
                }
            });
        }, 300);
    });

    // Pilih Nama dari Saran
    $(document).on('click', '.saran-item', function() {
        // Mengisi input form secara otomatis dari data terpilih
        $('#input_nama').val($(this).data('nama'));
        $('#jen_kel').val($(this).data('jenkel'));
        $('#jabatan').val($(this).data('jabatan'));
        $('#no_hp').val($(this).data('hp'));
        $('#email').val($(this).data('email'));
        $('#daftar_nama_saran').hide();
    });

    // Sembunyikan saran jika klik di luar area input
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#input_nama, #daftar_nama_saran').length) {
            $('#daftar_nama_saran').hide();
        }
    });

    // --- B. LOGIKA FILTER INSTANSI (SUDAH JALAN) ---
    $('#id_opd').select2({ width: '100%' });

    $('#jenis_opd').on('change', function() {
        const selectedJenis = $(this).val();
        const $opdSelect = $('#id_opd');
        
        $opdSelect.empty().append('<option value="">-- Pilih Instansi --</option>');
        
        if (selectedJenis && allOpd) {
            // Menggunakan ['ID_J-OPD'] karena simbol minus pada nama kolom
            const filtered = allOpd.filter(item => 
                item['ID_J-OPD'] == selectedJenis || item.ID_J_OPD == selectedJenis
            );

            filtered.forEach(opd => {
                $opdSelect.append(new Option(opd.NAMA_OPD, opd.ID_OPD));
            });
        }
        $opdSelect.trigger('change');
    });

    // --- C. TANDA TANGAN ---
    const canvas = document.getElementById('signature-pad');
    if (canvas) {
        const signaturePad = new SignaturePad(canvas);
        $('#clear').on('click', () => signaturePad.clear());

        $('form').on('submit', function (e) {
            if (signaturePad.isEmpty()) {
                alert("Silakan tanda tangan terlebih dahulu.");
                e.preventDefault();
            } else {
                // Konversi tanda tangan ke base64 untuk disimpan ke database
                $('#ttd_image').val(signaturePad.toDataURL());
            }
        });

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear(); // Reset canvas setelah resize
        }
        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();
    }
});
  </script>

</body>
</html>