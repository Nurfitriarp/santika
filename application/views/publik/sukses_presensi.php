<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Presensi Berhasil</title>
    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background-color: #f8f9fc; display: flex; align-items: center; min-height: 100vh; }
        .card { border-radius: 15px; border: none; }
        .success-icon { font-size: 80px; color: #1cc88a; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card shadow-lg p-5">
                    <div class="card-body">
                        <div class="mb-4">
                            <i class="fas fa-check-circle success-icon animate__animated animate__zoomIn"></i>
                        </div>
                        <h2 class="font-weight-bold text-gray-800">Presensi Berhasil!</h2>
                        <p class="text-muted">
                            <?= $this->session->flashdata('sukses_presensi') ?: 'Data Anda telah berhasil terekam dalam sistem kami.' ?>
                        </p>
                        <hr>
                        <p class="small text-muted mb-4">Anda sekarang dapat menutup halaman ini.</p>
                    </div>
                </div>
                <p class="mt-4 text-muted small">&copy; 2026 Diskominfo - ANTIKA</p>
            </div>
        </div>
    </div>
</body>
</html>