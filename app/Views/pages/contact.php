<?= $this->extend('layout/templateOtherPages') ?>

<?= $this->section('content') ?>
<?php
$session = session();
$isLoggedIn = $session->get('DataUser.login') ?? false;
$nama_lengkap = $session->get('DataUser.nama_lengkap') ?? 'User';
?>
<div class="container py-5">
    <div class="row justify-content-between align-items-center">
        <div class="col-lg-6 mb-4">
            <h2 class="fw-bold mb-3">Hubungi <span style="color: #ff0000;"><?= esc($setting['nama_perusahaan'] ?? 'Perusahaan Anda') ?></span></h2>
            <p class="text-muted mb-4">
                Kami siap membantu Anda. Silakan hubungi kami melalui informasi berikut atau kirim pesan langsung kepada tim kami.
            </p>

            <ul class="list-unstyled">
                <li class="mb-3">
                    <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                    <?= esc($setting['alamat_perusahaan'] ?? '-') ?>
                </li>
                <li class="mb-3">
                    <i class="bi bi-envelope-fill text-primary me-2"></i>
                    <a href="mailto:<?= esc($setting['email_kontak'] ?? '-') ?>" class="text-decoration-none">
                        <?= esc($setting['email_kontak'] ?? '-') ?>
                    </a>
                </li>
                <?php if (!empty($setting['nomor_telepon'])): ?>
                <li class="mb-3">
                    <i class="bi bi-telephone-fill text-success me-2"></i>
                    <?= esc($setting['nomor_telepon']) ?>
                </li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-3">Kirim Pesan Cepat</h5>
                    <form action="<?php echo base_url('PagesController/post_pesan_cepat'); ?>" method="post">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Anda" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="email@domain.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="pesan" class="form-label">Pesan</label>
                            <textarea class="form-control" id="pesan" name="pesan" rows="4" placeholder="Tulis pesan Anda di sini..." required></textarea>
                        </div>
                        <?php if ($isLoggedIn): ?>
                            <button type="submit" class="btn btn-primary w-100">Kirim Pesan</button>
                        <?php else: ?>
                            <p class="text-danger">Anda harus <a href="<?= base_url('login') ?>">masuk</a> untuk mengirim pesan.</p>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
