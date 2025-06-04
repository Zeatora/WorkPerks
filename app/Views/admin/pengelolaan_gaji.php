<?= $this->extend('layout/templateOtherPages') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-4">Pengelolaan Gaji & Insentif</h2>

    <a href="<?= base_url('gaji/tambah') ?>" class="btn btn-primary mb-3">+ Tambah Data Gaji</a>

    <form method="get" class="row mb-3">
        <div class="col-md-4">
            <input type="month" name="periode" value="<?= esc($periodeDipilih ?? date('Y-m')) ?>" class="form-control">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-secondary">Filter</button>
        </div>
    </form>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Karyawan</th>
                        <th>Gaji Pokok</th>
                        <th>Bonus</th>
                        <th>Insentif Kinerja</th>
                        <th>Total</th>
                        <th>Periode</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($daftarGaji)) : ?>
                        <?php $no = 1; foreach ($daftarGaji as $gaji): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($gaji['nama_lengkap']) ?></td>
                                <td>Rp<?= number_format($gaji['gaji_pokok'], 0, ',', '.') ?></td>
                                <td>Rp<?= number_format($gaji['bonus'], 0, ',', '.') ?></td>
                                <td>Rp<?= number_format($gaji['insentif_kinerja'], 0, ',', '.') ?></td>
                                <td>Rp<?= number_format($gaji['total_gaji'], 0, ',', '.') ?></td>
                                <td><?= date('M Y', strtotime($gaji['periode'])) ?></td>
                                <td>
                                    <a href="<?= base_url('gaji/edit/' . $gaji['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="<?= base_url('gaji/hapus/' . $gaji['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Data gaji belum tersedia untuk periode ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
