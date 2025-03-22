<?= $this->extend('layout/templateHome') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row">
        <div class="col">
            <h1><?= $title ?></h1>
            <pre>
    Current URL: <?= current_url() ?><br>
    Home URL: <?= base_url('home') ?>
</pre>
        </div>
    </div>
</div>

<?= $this->endSection() ?>