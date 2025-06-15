<?= $this->extend('layout/templateOtherPages') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2 class="mb-4">INBOX PESAN</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Pengirim</th>
                        <th>Email Pengirim</th>
                        <th>Pesan</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pesanCepat)) : ?>
                        <?php $no = 1 + ($pager->getCurrentPage('default') - 1) * $pager->getPerPage('default'); ?>
                        <?php foreach ($pesanCepat as $pesan): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($pesan['nama']) ?></td>
                                <td><?= esc($pesan['email']) ?></td>
                                <td><?= esc($pesan['pesan']) ?></td>
                                <td><?= date('d M Y H:i', strtotime($pesan['created_at'])) ?></td>
                                <td>
                                    <a href="<?= base_url('inbox/detail/' . $pesan['id']) ?>" class="btn btn-sm btn-info">Detail</a>
                                    <a href="<?= base_url('inbox/hapus/' . $pesan['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus pesan ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <?= $pager->links('default', 'bootstrap') ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
