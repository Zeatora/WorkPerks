<?= $this->extend('layout/templateOtherPages') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-primary"><i class="bi bi-building me-2"></i> <?= $title; ?></h4>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('KelolaDepartemenController/update/' . $departemen['id']) ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="nama_departemen" class="form-label">Nama Departemen</label>
                            <input type="text" class="form-control" id="nama_departemen" name="nama_departemen" value="<?= esc($departemen['nama_departemen']) ?>" required>
                            <input type="hidden" name="id" value="<?= esc($departemen['id']) ?>">
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="javascript:history.back()" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-saveme-1"></i> Simpan Departemen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>