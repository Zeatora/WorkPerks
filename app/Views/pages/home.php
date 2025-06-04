<?= $this->extend('layout/templateHome') ?>

<?= $this->section('content') ?>
<?php
$session = session();
$isLoggedIn = $session->get('DataUser.login') ?? false;
$nama_lengkap = $session->get('DataUser.nama_lengkap') ?? 'User';

?>


<div class="container mt-5">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="display-4 fw-bold">Selamat Datang di <span style="color: #ff0000;">WorkPerks</span></h1>
            <p class="lead text-muted">
                Platform manajemen tunjangan & kesejahteraan karyawan yang memudahkan HR dan transparan bagi karyawan.
            </p>
            <?php if (!$isLoggedIn): ?>
                <a href="<?= base_url('login') ?>" class="btn btn-primary btn-lg me-2">Masuk</a>
                <a href="<?= base_url('PagesController/signupPage') ?>" class="btn btn-outline-primary btn-lg">Daftar</a>
            <?php else: ?>
                <p style="color: #ff0000;" class="display-6 fw-bold bg-light rounded-4 shadow-sm px-4 py-3 mb-3 d-inline-block">
                    <i class="bi bi-emoji-smile" ></i> Hallo, <?= $nama_lengkap ?>
                </p>
            <?php endif; ?>
        </div>
        <div class="col-md-6 text-center">
            <img src="<?= base_url('assets/img/illustrasi.svg') ?>" alt="WorkPerks Illustration" class="img-fluid" style="max-height: 350px;">
        </div>
    </div>

    <hr class="my-5">

    <!-- Fitur Utama -->
    <div class="text-center mb-4">
        <h2 class="fw-bold">Fitur Utama WorkPerks</h2>
        <p class="text-muted">Solusi lengkap untuk pengelolaan kesejahteraan karyawan Anda</p>
    </div>

    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow h-100">
                <div class="card-body">
                    <div class="mb-3">
                        <i class="bi bi-person-lines-fill fs-1 text-primary"></i>
                    </div>
                    <h5 class="card-title">Manajemen Karyawan</h5>
                    <p class="card-text text-muted">Tambah, kelola, dan pantau data karyawan secara terpusat.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow h-100">
                <div class="card-body">
                    <div class="mb-3">
                        <i class="bi bi-cash-stack fs-1 text-success"></i>
                    </div>
                    <h5 class="card-title">Pengelolaan Gaji & Insentif</h5>
                    <p class="card-text text-muted">Kelola gaji bulanan, bonus, dan insentif berbasis kinerja.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow h-100">
                <div class="card-body">
                    <div class="mb-3">
                        <i class="bi bi-heart-pulse fs-1 text-danger"></i>
                    </div>
                    <h5 class="card-title">Tunjangan Kesejahteraan</h5>
                    <p class="card-text text-muted">Berikan tunjangan kesehatan, transportasi, dan lainnya dengan mudah.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>