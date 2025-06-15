<?= $this->extend('layout/templateOtherPages') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-4">Kelola Departemen</h2>
    <form method="get" action="<?= base_url('PagesController/kelola_departemen') ?>" class="mb-4">
        <div class="row align-items-end g-2">
            <!-- Kolom pencarian -->
            <div class="col-md-4">
                <label for="search" class="form-label">Cari Nama Departemen</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Cari..." value="<?= esc($search ?? '') ?>">
            </div>

            <!-- Tombol aksi -->
            <div class="col-md-4 d-flex gap-2 mt-md-0 mt-3">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="<?= base_url('PagesController/kelola_departemen') ?>" class="btn btn-outline-secondary flex-fill ml-2">
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
                        <th>Nama Departemen</th>
                        <th>Total Karyawan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($departemenList)) : ?>
                        <?php $no = 1 + ($pager->getCurrentPage('default') - 1) * $pager->getPerPage('default'); ?>
                        <?php foreach ($departemenList as $departemen): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($departemen['nama_departemen']) ?></td>
                                <td><?= esc($departemen['total_karyawan']) ?></td>
                                <td>
                                    <a href="<?= base_url('KelolaDepartemenController/edit/' . $departemen['id']) ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <a href="<?= base_url('KelolaDepartemenController/delete/' . $departemen['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus departemen ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada data departemen</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?= $pager->links('default', 'bootstrap') ?>
        </div>
        <div class="card-footer text-end">
            <a href="<?= base_url('KelolaDepartemenController/index') ?>" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Tambah Departemen
            </a>
        </div>
    </div>
</div>

<?= $this->endsection() ?>