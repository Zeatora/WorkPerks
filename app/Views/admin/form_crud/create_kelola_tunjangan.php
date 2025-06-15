<?= $this->extend('layout/templateOtherPages') ?>
<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h4 class="mb-0 text-primary">
                        <i class="bi bi-plus-circle me-2"></i> Tambah Tunjangan Baru
                    </h4>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('KelolaTunjanganController/create') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Tunjangan</label>
                            <input type="text" name="nama" id="nama" class="form-control" placeholder="Contoh: Tunjangan Kesehatan" required>
                        </div>

                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-light"><i class="bi bi-tags-fill"></i></span>
                                <select name="kategori" id="kategori" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="kesehatan">Kesehatan</option>
                                    <option value="transportasi">Transportasi</option>
                                    <option value="asuransi">Asuransi</option>
                                    <option value="makan">Makan</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>

                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi (Opsional)</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" placeholder="Deskripsi tunjangan..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="is_active" class="form-label fw-semibold">Status Tunjangan</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-light"><i class="bi bi-toggle-on"></i></span>
                                <select name="is_active" id="is_active" class="form-select" required>
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="javascript:history.back()" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Tunjangan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>