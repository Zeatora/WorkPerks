<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 30px; }
        .slip { border: 1px solid #000; padding: 20px; max-width: 600px; margin: auto; }
        h2, h4 { text-align: center; }
        table { width: 100%; margin-top: 20px; }
        td { padding: 8px; }
    </style>
</head>
<body onload="window.print()">

    <div class="slip">
        <h2>Slip Gaji Karyawan</h2>
        <h4><?= date('F Y', strtotime($gaji['periode'] . '-01')) ?></h4>

        <table>
            <tr>
                <td><strong>Nama</strong></td>
                <td>: <?= esc($gaji['nama_lengkap']) ?></td>
            </tr>
            <tr>
                <td><strong>Email</strong></td>
                <td>: <?= esc($gaji['email']) ?></td>
            </tr>
            <tr>
                <td><strong>Username</strong></td>
                <td>: <?= esc($gaji['username']) ?></td>
            </tr>
        </table>

        <hr>

        <table>
            <tr>
                <td>Gaji Pokok</td>
                <td>: Rp<?= number_format($gaji['gaji_pokok'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td>Bonus</td>
                <td>: Rp<?= number_format($gaji['bonus'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td>Insentif Kinerja</td>
                <td>: Rp<?= number_format($gaji['insentif_kinerja'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td><strong>Total Gaji</strong></td>
                <td><strong>: Rp<?= number_format($gaji['total_gaji'], 0, ',', '.') ?></strong></td>
            </tr>
        </table>

        <p style="margin-top: 40px; text-align: right;">
            Tanggal Cetak: <?= date('d M Y') ?>
        </p>
    </div>

</body>
</html>
