<?= $this->extend('layout/templateOtherPages') ?>
<?= $this->section('content') ?>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h4 class="text-warning mb-0">
                        <i class="bi bi-pencil-square me-2"></i>Edit Tunjangan
                    </h4>
                </div>
                <div class="card-body px-4 py-4">
                    <form action="<?= base_url('KelolaTunjanganController/update/' . $tunjangan['id']) ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="nama" class="form-label fw-semibold">Nama Tunjangan</label>
                            <input type="text" name="nama" id="nama" class="form-control shadow-sm"
                                value="<?= esc($tunjangan['nama']) ?>" placeholder="Contoh: Tunjangan Kesehatan" required>
                        </div>

                        <div class="mb-3">
                            <label for="kategori" class="form-label fw-semibold">Kategori Tunjangan</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-light"><i class="bi bi-tags-fill"></i></span>
                                <select name="kategori" id="kategori" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php
                                    $kategoriList = ['Kesehatan', 'Transportasi', 'Asuransi', 'Makan', 'Lainnya'];
                                    foreach ($kategoriList as $kategori):
                                    ?>
                                        <option value="<?= strtolower($kategori) ?>"
                                            <?= strtolower($tunjangan['kategori']) == strtolower($kategori) ? 'selected' : '' ?>>
                                            <?= $kategori ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control shadow-sm"
                                rows="3" placeholder="Deskripsi singkat tunjangan"><?= esc($tunjangan['deskripsi']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="is_active" class="form-label fw-semibold">Status Tunjangan</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-light"><i class="bi bi-toggle-on"></i></span>
                                <select name="is_active" id="is_active" class="form-select" required>
                                    <option value="1" <?= $tunjangan['is_active'] == 1 ? 'selected' : '' ?>>Aktif</option>
                                    <option value="0" <?= $tunjangan['is_active'] == 0 ? 'selected' : '' ?>>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="javascript:history.back()" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save me-1"></i> Perbarui Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
