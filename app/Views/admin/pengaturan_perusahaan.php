<?= $this->extend('layout/templateOtherPages') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-4">Pengaturan Perusahaan</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('pengaturan/update') ?>" method="post">
        <?= csrf_field() ?>

        <?php foreach ($pengaturan as $item): ?>
            <div class="mb-3">
                <label for="<?= $item['setting_key'] ?>" class="form-label"><strong><?= ucwords(str_replace('_', ' ', $item['setting_key'])) ?></strong></label>
                <input type="text" name="pengaturan[<?= $item['setting_key'] ?>]" class="form-control" id="<?= $item['setting_key'] ?>" value="<?= esc($item['setting_value']) ?>">
                <small class="text-muted"><?= esc($item['deskripsi']) ?></small>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    </form>
</div>

<?= $this->endSection() ?>
