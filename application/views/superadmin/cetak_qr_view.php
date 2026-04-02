<!DOCTYPE html>
<html>
<head>
    <title>Cetak QR - <?= $kegiatan->NAMA ?></title>
    <style>
    /* Pengaturan Dasar */
    body { 
        font-family: 'Times New Roman', Times, serif; 
        margin: 0; 
        padding: 40px; 
        display: flex; 
        justify-content: center; 
        background-color: #f0f0f0; 
    }

    /* Container Kartu */
    .card {
        width: 350px; 
        background-color: white;
        padding: 30px;
        text-align: center;
        border: 1px solid #000000 !important; 
        box-shadow: none;
        position: relative;
    }

    /* Logo */
    .logo {
        width: 100px; 
        height: auto;
        margin-bottom: 10px;
    }

    .sub-title {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 5px;
    }

    h2 {
        font-size: 22px;
        margin: 5px 0;
        font-weight: bold;
        text-decoration: underline;
    }

    .info-kegiatan {
        font-size: 14px;
        margin-bottom: 5px;
        font-weight: normal;
    }

    /* QR Code */
    .qr-wrapper {
        margin: 15px 0;
        display: flex;
        justify-content: center;
    }

    .qr-wrapper img {
        width: 200px; 
        height: 200px;
        border: 1px solid #000;
        padding: 5px;
    }

    /* Nama Kegiatan di Bawah */
    .event-name {
        font-size: 16px;
        font-weight: bold;
        margin-top: 15px;
        padding-top: 10px;
        border-top: 1px double #000;
        text-transform: uppercase;
    }

    /* Pengaturan Cetak */
    @media print {
        body { background-color: white; padding: 0; }
        .card { border: 1px solid #000 !important; margin: 20px auto; }
        .no-print { display: none; }
        @page { margin: 0.5cm; }
    }
    </style>
</head>
<body onload="window.print()">

    <div class="card">
        <img src="<?= base_url('assets/img/logokabupaten.png') ?>" class="logo" alt="Logo">
        <div class="sub-title">PEMERINTAH KABUPATEN MALANG</div>
        <h2>SCAN QR CODE</h2>

        <div class="info-kegiatan">
            <strong>
            <?php
                $bulan = array (
                    1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                );
                $split = explode('-', $kegiatan->TANGGAL); 
                echo $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
            ?>
            </strong>
        </div>
        <div class="info-kegiatan">Pukul: <?= $kegiatan->JAM ?></div>
        <div class="info-kegiatan">Tempat: <?= $kegiatan->TEMPAT ?></div>

        <div class="qr-wrapper">
            <?php 
                $url_data = base_url('presensi/isi/' . $kegiatan->qr_token);
                $qr_api = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($url_data);
            ?>
            <img src="<?= $qr_api ?>" alt="QR Code">
        </div>

        <div class="event-name">
            <?= $kegiatan->NAMA ?>
        </div>
    </div>

    <div class="no-print" style="position: fixed; bottom: 20px; right: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #28a745; color: white; border: none; border-radius: 5px;">
            <i class="fas fa-print"></i> Cetak Sekarang
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer; background: #dc3545; color: white; border: none; border-radius: 5px;">
            Tutup
        </button>
    </div>

</body>
</html>