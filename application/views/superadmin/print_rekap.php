<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Hadir - <?= isset($detail) ? $detail->NAMA : 'Kegiatan' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #000;
            padding-bottom: 0px;
        }

        .logo-section {
            flex: 0 0 80px;
            text-align: center;
        }

        .logo-section img {
            max-width: 150px;
            max-height: 150px;
            width: auto;
            height: auto;
        }

        .header-text {
            flex: 1;
            text-align: center;
        }

        .header-text h2 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .header-text p {
            font-size: 11px;
            margin: 2px 0;
        }

        .header-text .title {
            font-weight: bold;
            margin-top: 5px;
        }

        .info-section {
            margin-bottom: 15px;
        }

        .info-section h3 {
            font-size: 12px;
            font-weight: bold;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .info-row {
            display: flex;
            margin-bottom: 5px;
            font-size: 11px;
        }

        .info-label {
            width: 120px;
            font-weight: bold;
        }

        .info-value {
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table thead {
            background-color: #f5f5f5;
        }

        table th {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
        }

        table td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 10px;
        }

        table tbody tr:hover {
            background-color: #f9f9f9;
        }

        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
            gap: 50px;
        }

        .signature {
            text-align: center;
            width: 150px;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
            min-height: 50px;
        }

        .signature p {
            font-size: 10px;
            margin-top: 5px;
        }

        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
            table {
                page-break-inside: avoid;
            }
        }

        .print-button-group {
            margin-bottom: 15px;
            text-align: right;
        }

        .print-button-group button {
            padding: 8px 15px;
            margin-left: 10px;
            font-size: 14px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
        }

        .print-button-group button:hover {
            background-color: #0056b3;
        }

        .print-button-group button.back {
            background-color: #6c757d;
        }

        .print-button-group button.back:hover {
            background-color: #545b62;
        }
    </style>
</head>
<body>

    <div class="no-print print-button-group">
        <button onclick="window.print()"><i class="fas fa-print"></i> Cetak (Print)</button>
    </div>

    <div class="header">
        <div class="logo-section">
            <img src="<?= base_url('assets/img/logokabupaten.png'); ?>" alt="Logo Kabupaten Malang">
        </div>
        <div class="header-text">
            <h2>PEMERINTAH KABUPATEN MALANG</h2>
            <h2>DINAS KOMUNIKASI DAN INFORMATIKA</h2>
            <p style="font-size: 10px; margin-top: 3px;">Jalan K.H. Agus Salim No. 7 Gedung Jl. 3. Malang Telp/Fax (0341) 408788</p>
            <p style="font-size: 10px;">Email: kominfo@malangkab.go.id - Website: www.malangkab.go.id</p>
        </div>
        <div class="logo-section" style="visibility: hidden;">
            <img src="<?= base_url('assets/img/logo.png'); ?>" alt="">
        </div>
    </div>

    <div style="text-align: center; padding-bottom: 10px; margin-bottom: 15px;">
        <h3 style="margin: 0; font-size: 13px; font-weight: bold;">DAFTAR HADIR</h3>
    </div>

    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Tanggal</div>
            <div class="info-value">: 
                <?php 
                    if (isset($detail->TANGGAL)) {
                        // Mengambil 10 karakter pertama (YYYY-MM-DD) untuk menghindari format sisa
                        $tgl_clean = substr($detail->TANGGAL, 0, 10); 
                        echo date('d F Y', strtotime($tgl_clean));
                    } else {
                        echo "-";
                    }
                ?>
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Jam</div>
            <div class="info-value">: <?= isset($detail) ? $detail->JAM : '-' ?> s/d selesai</div>
        </div>
        <div class="info-row">
            <div class="info-label">Tempat</div>
            <div class="info-value">: <?= isset($detail) ? $detail->TEMPAT : '-' ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Acara</div>
            <div class="info-value">: <?= isset($detail) ? $detail->NAMA : '-' ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Jumlah Peserta</div>
            <div class="info-value">: 
                <?php 
                    // Inisialisasi awal
                    $total_l = 0;
                    $total_p = 0;

                    if (isset($peserta) && is_array($peserta)) {
                        foreach ($peserta as $p) {
                            // Cek apakah JEN_KEL adalah 1 / 'L' (Laki-laki)
                            if ($p->JEN_KEL == 1 || strtoupper($p->JEN_KEL) == 'L') {
                                $total_l++;
                            } 
                            // Cek apakah JEN_KEL adalah 2 / 'P' (Perempuan)
                            elseif ($p->JEN_KEL == 2 || strtoupper($p->JEN_KEL) == 'P') {
                                $total_p++;
                            }
                        }
                    }
                ?>
                <?= $total_l ?> Laki-Laki (L) ; Perempuan (P) : <?= $total_p ?>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">NO</th>
                <th style="width: 20%;">NAMA</th>
                <th style="width: 5%;">L/P</th>
                <th style="width: 25%;">UNIT KERJA</th>
                <th style="width: 20%;">JABATAN</th>
                <th style="width: 15%;">NO HP</th>
                <th style="width: 10%;">TANDA TANGAN</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($peserta) && count($peserta) > 0): ?>
                <?php foreach($peserta as $key => $p): ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $p->NAMA ?? '-' ?></td>
                    <td style="text-align: center;"><?= $p->JEN_KEL == 1 ? 'L' : 'P' ?></td>
                    <td><?= $p->NAMA_OPD ?? '-' ?></td>
                    <td><?= $p->JABATAN ?? '-' ?></td>
                    <td><?= $p->NO_HP ?? '-' ?></td>
                    <td>&nbsp;</td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">
                        <em>Belum ada peserta yang login</em>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>



</body>
</html>
