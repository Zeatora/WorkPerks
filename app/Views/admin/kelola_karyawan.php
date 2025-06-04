<?= $this->extend('layout/templateOtherPages') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-4">Kelola Karyawan</h2>

    <a href="<?= base_url('karyawan/tambah') ?>" class="btn btn-primary mb-3">+ Tambah Karyawan</a>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Departemen</th>
                        <th>Status</th>
                        <th>Aksi</th>
                        <th>Aksi Admin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($daftarKaryawan)) : ?>
                        <?php $no = 1; foreach ($daftarKaryawan as $karyawan): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($karyawan['nama_lengkap']) ?></td>
                                <td><?= esc($karyawan['email']) ?></td>
                                <td><?= esc($karyawan['departemen']) ?></td>
                                <td>
                                    <span class="badge <?= $karyawan['status'] == 'active' ? 'bg-success' : 'bg-secondary' ?>">
                                        <?= ucfirst($karyawan['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('karyawan/profile/' . $karyawan['id']) ?>" class="btn btn-sm btn-warning">Profile</a>
                                </td>
                                <td>
                                    <a href="<?= base_url('karyawan/edit/' . $karyawan['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="<?= base_url('karyawan/hapus/' . $karyawan['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
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
        </div>
    </div>
</div>

<?= $this->endSection() ?>
