<?= $this->extend('layout/templateOtherPages') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2 class="mb-4">Kelola Tunjangan</h2>

    <a href="<?= base_url('tunjangan/tambah') ?>" class="btn btn-primary mb-3">+ Tambah Tunjangan</a>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Tunjangan</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($daftarTunjangan)) : ?>
                        <?php $no = 1; foreach ($daftarTunjangan as $tunjangan): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($tunjangan['nama_tunjangan']) ?></td>
                                <td><?= esc($tunjangan['jumlah']) ?></td>
                                <td>
                                    <span class="badge <?= $tunjangan['status'] == 'active' ? 'bg-success' : 'bg-secondary' ?>">
                                        <?= ucfirst($tunjangan['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('tunjangan/edit/' . $tunjangan['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="<?= base_url('tunjangan/hapus/' . $tunjangan['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
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
        </div>
    </div>
<?= $this->endSection() ?>
