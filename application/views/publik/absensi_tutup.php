<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $judul ?></title>
    
    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <style>
        body { 
            background-color: #f8f9fc; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center;
        }
        .error-card {
            max-width: 500px;
            width: 100%;
            border-radius: 20px;
            border: none;
        }
        .icon-lock {
            font-size: 80px;
            color: #e74a3b;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="container text-center">
        <div class="card shadow-lg p-5 error-card mx-auto">
            <div class="card-body">
                <div class="icon-lock">
                    <i class="fas fa-user-lock"></i>
                </div>
                <h2 class="font-weight-bold text-gray-900 mb-3"><?= $judul ?></h2>
                <p class="text-muted lead mb-4">
                    <?= $pesan ?>
                </p>
                <hr>
                <div class="mt-4">
                    <p class="small text-gray-500">
                        silakan hubungi admin penyelenggara kegiatan.
                    </p>
                    <a href="javascript:history.back()" class="btn btn-primary btn-sm px-4">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
        <p class="mt-4 text-gray-500 small">&copy; <?= date('Y') ?> Aplikasi Absensi - Kominfo Kabupaten Malang</p>
    </div>

</body>
</html>