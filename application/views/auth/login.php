<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - DAHAR</title>
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico?v=' . time()) ?>">
    
    <style>
        /* Styling tambahan agar tampilan captcha rapi */
        .captcha-box img {
            border-radius: 5px;
            margin-bottom: 10px;
            width: 100%; /* Agar gambar menyesuaikan lebar container */
            height: auto;
        }
    </style>
</head>
<body class="bg-gradient-dark">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4 font-weight-bold">DAHAR LOGIN</h1>
                            </div>

                            <?php if($this->session->flashdata('error')): ?>
                                <div class="alert alert-danger small"><?= $this->session->flashdata('error') ?></div>
                            <?php endif; ?>

                            <?php if($this->session->flashdata('success')): ?>
                                <div class="alert alert-success small"><?= $this->session->flashdata('success') ?></div>
                            <?php endif; ?>

                            <form class="user" method="post" action="<?= base_url('auth/login_process') ?>">
                                <div class="form-group">
                                    <input type="text" name="username" class="form-control form-control-user" 
                                           placeholder="Masukkan Username..." required>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control form-control-user" 
                                           placeholder="Password" required>
                                </div>

                                <div class="form-group row align-items-center">
                                    <div class="col-sm-6 captcha-box">
                                        <?= $captcha_img; ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" name="captcha_code" class="form-control form-control-user" 
                                               placeholder="Kode Angka" required autocomplete="off">
                                    </div>
                                    <div class="col-12 mt-1">
                                        <small class="text-muted">*Masukkan angka yang muncul di atas</small>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Masuk ke Dashboard
                                </button>
                            </form>
                            
                            <hr>
                            <div class="text-center small">
                                DAHAR - Daftar Hadir
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>