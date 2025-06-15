<?= $this->extend('layout/templateOtherPages') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-4">Kelola Tunjangan</h2>
    <form method="get" action="<?= base_url('PagesController/kelola_tunjangan') ?>" class="mb-4">
        <div class="row align-items-end g-2">
            <!-- Kolom pencarian -->
            <div class="col-md-4">
                <label for="search" class="form-label">Cari Nama Tunjangan</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Cari..." value="<?= esc($search ?? '') ?>">
            </div>

            <!-- Kolom filter kategori -->
            <div class="col-md-4">
                <label for="kategori" class="form-label">Filter Kategori</label>
                <select name="kategori" id="kategori" class="form-select p-2 border border-secondary">
                    <option value="">-- Semua Kategori --</option>
                    <?php foreach ($kategoriList as $kat): ?>
                        <option value="<?= $kat ?>" <?= ($kategoriFilter ?? '') === $kat ? 'selected' : '' ?>><?= $kat ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tombol aksi -->
            <div class="col-md-4 d-flex gap-2 mt-md-0 mt-3">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="<?= base_url('PagesController/kelola_tunjangan') ?>" class="btn btn-outline-secondary flex-fill">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                </a>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Tunjangan</th>
                        <th>Kategori Tunjangan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($daftarTunjangan)) : ?>
                        <?php $no = 1 + ($pager->getCurrentPage('default') - 1) * $pager->getPerPage('default'); ?>
                        <?php foreach ($daftarTunjangan as $tunjangan): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($tunjangan['nama']) ?></td>
                                <td><?= esc(ucfirst($tunjangan['kategori'])) ?></td>
                                <td>
                                    <span class="badge <?= $tunjangan['is_active'] ? 'bg-success' : 'bg-secondary' ?> text-light p-2">
                                        <?= $tunjangan['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('KelolaTunjanganController/edit/' . $tunjangan['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="<?= base_url('KelolaTunjanganController/delete/' . $tunjangan['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data tunjangan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?= $pager->links('default', 'bootstrap') ?>
        </div>
        <div class="card-footer text-end">
            <a href="<?= base_url('KelolaTunjanganController/index') ?>" class="btn btn-primary">+ Tambah Tunjangan</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
