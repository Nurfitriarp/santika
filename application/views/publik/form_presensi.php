<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Presensi Peserta - <?= $kegiatan->NAMA ?></title>
    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
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
                        <p class="text-center text-muted small">Silakan isi data diri Anda di bawah ini:</p>
                        <hr>
                        
                        <form action="<?= base_url('presensi/kirim') ?>" method="POST">
                            <input type="hidden" name="ID_KEGIATAN" value="<?= $kegiatan->ID_KEGIATAN ?>">

                            <div class="form-group">
                                <label class="font-weight-bold">Nama Lengkap</label>
                                <input type="text" name="NAMA" class="form-control" 
                                    value="<?= isset($lama) ? $lama->NAMA : '' ?>" required>
                            </div>  

                            <div class="form-group">
                                <label class="font-weight-bold">Jenis Kelamin</label>
                                <select name="JEN_KEL" class="form-control" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Instansi / OPD</label>
                                <select name="ID_OPD" class="form-control" required>
                                    <option value="">-- Pilih Instansi --</option>
                                    <?php foreach($opd as $o): ?>
                                        <option value="<?= $o->ID_OPD ?>" <?= (isset($lama) && $lama->ID_OPD == $o->ID_OPD) ? 'selected' : '' ?>>
                                            <?= $o->NAMA_OPD ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Jabatan</label>
                                <input type="text" name="JABATAN" class="form-control" placeholder="Contoh: Analis Data" required>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">No. HP / WhatsApp</label>
                                <input type="number" name="NO_HP" class="form-control" placeholder="08..." required>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Email</label>
                                <input type="email" name="EMAIL" class="form-control" placeholder="nama@email.com">
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
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <script>
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas);
        const form = document.querySelector('form');
        const ttdInput = document.getElementById('ttd_image');

        // Menangani tombol hapus
        document.getElementById('clear').addEventListener('click', function () {
            signaturePad.clear();
        });

        // Sebelum form dikirim, masukkan data gambar ke input hidden
        form.addEventListener('submit', function (e) {
            if (signaturePad.isEmpty()) {
                alert("Silakan bubuhkan tanda tangan terlebih dahulu.");
                e.preventDefault();
            } else {
                const dataUrl = signaturePad.toDataURL();
                ttdInput.value = dataUrl;
            }
        });

        // Menyesuaikan ukuran kanvas agar responsif
        function resizeCanvas() {
            const ratio =  Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }
        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();
    </script>
</body>
</html>