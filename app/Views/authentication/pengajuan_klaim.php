<?= $this->extend('layout/templateOtherPages') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <h2 class="mb-4">Pengajuan Klaim & Reimburse</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="<?= base_url('PagesController/ajukan') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tipe_klaim" class="form-label">Jenis Klaim</label>
                        <select name="tipe_klaim" id="tipe_klaim" class="form-select" required>
                            <option value="">-- Pilih Tunjangan Aktif --</option>
                            <?php foreach ($benefits as $b): ?>
                                <option value="<?= esc($b['kategori']) ?>">
                                    <?= esc($b['nama']) ?> (<?= ucfirst($b['kategori']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="jumlah" class="form-label">Jumlah (Rp)</label>
                        <input type="text" id="jumlahFormatted" class="form-control" required>
                        <input type="hidden" name="jumlah" id="jumlah">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" placeholder="Contoh: Penggantian biaya obat demam"></textarea>
                </div>
                <div class="mb-3">
                    <label for="bukti" class="form-label">Upload Bukti (PDF/JPG/PNG)</label>
                    <input type="file" name="bukti" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>
                <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
            </form>
        </div>
    </div>

    <!-- Riwayat Klaim -->
    <div class="card shadow-sm">
        <div class="card-header bg-light fw-bold">Riwayat Pengajuan Anda</div>
        <div class="card-body">
            <?php if (!empty($riwayatKlaim)): ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($riwayatKlaim as $k): ?>
                            <tr>
                                <td><?= esc($k['tipe_klaim']) ?></td>
                                <td>Rp<?= number_format($k['jumlah'], 0, ',', '.') ?></td>
                                <td>
                                    <span class="badge bg-<?= match ($k['status']) {
                                                                'approved' => 'success',
                                                                'pending' => 'warning text-dark',
                                                                'rejected' => 'danger',
                                                                default => 'secondary'
                                                            } ?> text-light p-2">
                                        <?= ucfirst($k['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('d M Y', strtotime($k['submitted_at'])) ?></td>
                                <td>
                                    <?php if ($k['status'] == 'pending'): ?>
                                        <a href="<?= base_url('ClaimsController/batalkan_claim/' . $k['id']) ?>" onclick="return confirm('Batalkan klaim ini?')" class="btn btn-sm btn-outline-danger">Batalkan</a>
                                    <?php else: ?>
                                        <a href="<?= base_url('ClaimsController/lihat_bukti/' . $k['id']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">Lihat Bukti</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">Belum ada klaim yang diajukan.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputFormatted = document.getElementById('jumlahFormatted');
    const inputHidden = document.getElementById('jumlah');

    inputFormatted.addEventListener('input', function () {
        let raw = this.value.replace(/[^0-9]/g, '');

        // If input is empty, set both to empty
        if (!raw) {
            inputHidden.value = '';
            this.value = '';
            return;
        }

        // Remove leading zeros
        raw = raw.replace(/^0+/, '');

        inputHidden.value = raw;

        this.value = formatRupiah(raw);
    });

    function formatRupiah(angka) {
        let number_string = angka.replace(/\D/g, ''),
            sisa = number_string.length % 3,
            rupiah = number_string.substr(0, sisa),
            ribuan = number_string.substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            rupiah += (sisa ? '.' : '') + ribuan.join('.');
        }
        return 'Rp' + rupiah;
    }
});
</script>

<?= $this->endSection() ?>