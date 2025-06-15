<style>
    body {
        font-family: Arial, sans-serif;
        color: #333;
    }

    h3 {
        text-align: center;
        margin-bottom: 20px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        font-size: 14px;
    }

    thead {
        background-color: #f2f2f2;
    }

    th,
    td {
        border: 1px solid #ccc;
        padding: 8px 12px;
        text-align: left;
    }

    th {
        background-color: #007BFF;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 4px;
        color: #fff;
        font-size: 12px;
    }

    .badge.approved {
        background-color: #28a745;
    }

    .badge.pending {
        background-color: #ffc107;
        color: #333;
    }

    .badge.rejected {
        background-color: #dc3545;
    }
</style>

<h3>Laporan Klaim Periode <?= esc(date('F Y', strtotime($periode))) ?></h3>
<h2 style="text-align: center;">Laporan Klaim Periode <?= esc($periode) ?></h2>
<hr>

<p><strong>Total Gaji:</strong> Rp<?= number_format($totalGaji, 0, ',', '.') ?></p>
<p><strong>Total Klaim:</strong> Rp<?= number_format($totalKlaim, 0, ',', '.') ?></p>
<p><strong>Total Tunjangan:</strong> Rp<?= number_format($totalTunjangan, 0, ',', '.') ?></p>
<br>

<table>
    <thead>
        <tr>
            <th>Nama</th>
            <th>Departemen</th>
            <th>Jenis Klaim</th>
            <th class="text-right">Jumlah</th>
            <th>Status</th>
            <th class="text-center">Tanggal</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($detailKlaim as $klaim): ?>
            <tr>
                <td><?= esc($klaim['nama_lengkap']) ?></td>
                <td><?= esc($klaim['nama_departemen']) ?></td>
                <td><?= esc($klaim['tipe_klaim']) ?></td>
                <td class="text-right">Rp<?= number_format($klaim['jumlah'], 0, ',', '.') ?></td>
                <td>
                    <span class="badge <?= $klaim['status'] === 'approved' ? 'approved' : ($klaim['status'] === 'pending' ? 'pending' : 'rejected') ?>">
                        <?= ucfirst($klaim['status']) ?>
                    </span>
                </td>
                <td class="text-center"><?= date('d M Y', strtotime($klaim['submitted_at'])) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>