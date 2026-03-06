<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - SANTIKA</title>
    <link href="<?= base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4 font-weight-bold">SANTIKA LOGIN</h1>
                            </div>

                            <?php if($this->session->flashdata('error')): ?>
                                <div class="alert alert-danger small"><?= $this->session->flashdata('error') ?></div>
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
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Masuk ke Dashboard
                                </button>
                            </form>
                            <hr>
                            <div class="text-center small">
                                &copy; 2026 Diskominfostandi
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>