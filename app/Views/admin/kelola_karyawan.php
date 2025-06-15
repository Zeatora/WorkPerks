<?= $this->extend('layout/templateOtherPages') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-4">Kelola Karyawan</h2>
    <form method="get" action="<?= base_url('PagesController/kelola_karyawan') ?>" class="mb-4">
        <div class="row align-items-end g-2">
            <!-- Kolom pencarian -->
            <div class="col-md-4">
                <label for="search" class="form-label">Cari Nama / Email</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Cari..." value="<?= esc($search ?? '') ?>">
            </div>

            <!-- Kolom filter departemen -->
            <div class="col-md-4">
                <label for="departemen" class="form-label">Filter Departemen</label>
                <select name="departemen" id="departemen" class="form-select p-2 border border-secondary">
                    <option value="">-- Semua Departemen --</option>
                    <?php foreach ($departemenList as $dep): ?>
                        <option value="<?= $dep['id'] ?>" <?= ($departemenFilter == $dep['id']) ? 'selected' : '' ?>>
                            <?= esc($dep['nama_departemen']) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>

            <!-- Tombol aksi -->
            <div class="col-md-4 d-flex gap-2 mt-md-0 mt-3">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="<?= base_url('PagesController/kelola_karyawan') ?>" class="btn btn-outline-secondary flex-fill ml-2">
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
                        <th>
                            <a class="text-dark" href="<?= base_url('PagesController/kelola_karyawan') . '?' . http_build_query(array_merge($_GET, ['sort' => 'username', 'order' => ($sort == 'username' && $order == 'asc') ? 'desc' : 'asc'])) ?>">
                                Username
                                <?= ($sort == 'username') ? ($order == 'asc' ? '↑' : '↓') : '' ?>
                            </a>
                        </th>
                        <th>
                            <a class="text-dark" href="<?= base_url('PagesController/kelola_karyawan') . '?' . http_build_query(array_merge($_GET, ['sort' => 'nama_lengkap', 'order' => ($sort == 'nama_lengkap' && $order == 'asc') ? 'desc' : 'asc'])) ?>">
                                Nama Lengkap
                                <?= ($sort == 'nama_lengkap') ? ($order == 'asc' ? '↑' : '↓') : '' ?>
                            </a>
                        </th>
                        <th>
                            <a class="text-dark" href="<?= base_url('PagesController/kelola_karyawan') . '?' . http_build_query(array_merge($_GET, ['sort' => 'email', 'order' => ($sort == 'email' && $order == 'asc') ? 'desc' : 'asc'])) ?>">
                                Email
                                <?= ($sort == 'email') ? ($order == 'asc' ? '↑' : '↓') : '' ?>
                            </a>
                        </th>
                        <th>
                            <a class="text-dark" href="<?= base_url('PagesController/kelola_karyawan') . '?' . http_build_query(array_merge($_GET, ['sort' => 'nama_departemen', 'order' => ($sort == 'nama_departemen' && $order == 'asc') ? 'desc' : 'asc'])) ?>">
                                Departemen
                                <?= ($sort == 'nama_departemen') ? ($order == 'asc' ? '↑' : '↓') : '' ?>
                            </a>
                        </th>
                        <th>Status</th>
                        <th>Redirect</th>
                        <th>Aksi Admin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($daftarKaryawan)) : ?>
                        <?php $no = 1 + ($pager->getCurrentPage('default') - 1) * $pager->getPerPage('default'); ?>
                        <?php foreach ($daftarKaryawan as $karyawan): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($karyawan['username']) ?></td>
                                <td><?= esc($karyawan['nama_lengkap']) ?></td>
                                <td><?= esc($karyawan['email']) ?></td>
                                <td><?= esc($karyawan['nama_departemen']) ?></td>
                                <td>
                                    <span class="badge <?= $karyawan['status'] == 'active' ? 'bg-success' : 'bg-secondary' ?> text-light p-2">
                                        <?= ucfirst($karyawan['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('PagesController/detail_karyawan/' . $karyawan['id']) ?>" class="btn btn-sm btn-warning">Profile</a>
                                </td>
                                <td>
                                    <a href="<?= base_url('KelolaKaryawanController/edit/' . $karyawan['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="<?= base_url('KelolaKaryawanController/delete/' . $karyawan['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data karyawan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?= $pager->links('default', 'bootstrap') ?>
        </div>
        <div class="card-footer text-end">
            <a href="<?= base_url('KelolaKaryawanController/create') ?>" class="btn btn-primary">+ Tambah Karyawan</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>