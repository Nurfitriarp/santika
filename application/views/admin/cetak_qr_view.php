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

    /* Container Kartu dengan Border */
    .card {
        width: 350px; 
        background-color: white;
        padding: 30px;
        text-align: center;
        /* Border tipis yang akan terlihat di layar dan print */
        border: 1px solid #000000 !important; 
        box-shadow: none; /* Menghilangkan shadow agar tidak kotor saat diprint */
        position: relative;
    }

    /* Logo */
    .logo {
        width: 150px; 
        height: auto;
        margin-bottom: 10px;
    }

    /* Teks Header */
    .gov-name {
        font-size: 12px;
        margin: 0;
        letter-spacing: 1px;
    }

    h2 {
        font-size: 20px;
        margin: 5px 0;
        font-weight: bold;
    }

    .organizer {
        font-size: 13px;
        margin-bottom: 15px;
        font-weight: normal;
    }

    /* QR Code */
    .qr-wrapper {
        margin: 20px 0;
        display: flex;
        justify-content: center;
    }

    .qr-wrapper img {
        width: 180px; 
        height: 180px;
        border: 1px solid #eee; /* Border sangat tipis hanya di gambar QR */
    }

    /* Nama Kegiatan di Bawah */
    .event-name {
        font-size: 15px;
        font-weight: bold;
        margin-top: 15px;
        padding-top: 10px;
        border-top: 1px double #000; /* Garis pemisah di atas nama kegiatan */
        text-transform: uppercase;
    }

    /* Pengaturan Cetak Khusus */
    @media print {
        body { 
            background-color: white; 
            padding: 0; 
        }
        .card { 
            /* Memastikan border muncul di semua browser */
            border: 1px solid #000 !important; 
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            margin: 20px auto;
        }
        .no-print { 
            display: none; 
        }
        /* Menghilangkan header/footer otomatis browser (url, tgl, dll) */
        @page { 
            margin: 0.5cm; 
        }
    }
</style>
</head>
<body onload="window.print()">

    <div class="card">
        <img src="<?= base_url('assets/img/logokabupaten.png') ?>" class="logo" alt="Logo">
        
        <div class="sub-title">PEMERINTAH KABUPATEN MALANG</div>
        <h2>SCAN QR CODE</h2>
        <div class="organizer"><?= $kegiatan->TANGGAL ?></div>

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
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Sekarang</button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer;">Tutup</button>
    </div>

</body>
</html>