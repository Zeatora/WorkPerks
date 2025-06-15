<?= $this->extend('layout/templateOtherPages') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-4">Pengelolaan Gaji & Insentif</h2>
    <form method="get" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="search" value="<?= esc($search) ?>" class="form-control" placeholder="Cari nama...">
        </div>
        <div class="col-md-2">
            <input type="number" name="min_total" value="<?= esc($min_total) ?>" class="form-control" placeholder="Min Total">
        </div>
        <div class="col-md-2">
            <input type="number" name="max_total" value="<?= esc($max_total) ?>" class="form-control" placeholder="Max Total">
        </div>
        <div class="col-md-3">
            <input type="month" name="periode" value="<?= esc($periodeDipilih) ?>" class="form-control">
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Cari</button>
            <a href="<?= base_url('PagesController/pengelolaan_gaji') . '?periode=' . urlencode($periodeDipilih) ?>" class="btn btn-secondary w-100 ml-2">Reset</a>
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
                        <?php $no = 1 + ($pager->getCurrentPage('default') - 1) * $pager->getPerPage('default'); ?>
                        <?php foreach ($daftarGaji as $gaji): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($gaji['nama_lengkap']) ?></td>
                                <td>Rp<?= number_format($gaji['gaji_pokok'], 0, ',', '.') ?></td>
                                <td>Rp<?= number_format($gaji['bonus'], 0, ',', '.') ?></td>
                                <td>Rp<?= number_format($gaji['insentif_kinerja'], 0, ',', '.') ?></td>
                                <td>Rp<?= number_format($gaji['total_gaji'], 0, ',', '.') ?></td>
                                <td><?= date('M Y', strtotime($gaji['periode'])) ?></td>
                                <td>
                                    <a href="<?= base_url('KelolaGajiController/edit/' . $gaji['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="<?= base_url('KelolaGajiController/delete/' . $gaji['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data?')">Hapus</a>
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
            <?= $pager->links('default', 'bootstrap') ?>
        </div>
        <div class="card-footer text-end">
            <a href="<?= base_url('KelolaGajiController/index') ?>" class="btn btn-primary">+ Tambah Data Gaji</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>