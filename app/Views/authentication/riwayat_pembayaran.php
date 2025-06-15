<?= $this->extend('layout/templateOtherPages') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <h2 class="mb-4">Riwayat Pembayaran & Insentif</h2>

    <form method="get" class="mb-3 d-flex justify-content-end">
        <select name="tahun" class="form-select w-auto">
            <?php foreach ($tahunList as $t): ?>
                <option value="<?= $t ?>" <?= ($t == $tahunDipilih ? 'selected' : '') ?>><?= $t ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn btn-secondary ms-2">Tampilkan</button>
    </form>

    <div class="card shadow-sm">
        <div class="card-body">
            <?php if (!empty($riwayatGaji)): ?>
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Periode</th>
                            <th>Gaji Pokok</th>
                            <th>Bonus</th>
                            <th>Insentif</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($riwayatGaji as $gaji): ?>
                            <tr>
                                <td><?= date('F Y', strtotime($gaji['periode'] . '-01')) ?></td>
                                <td>Rp<?= number_format($gaji['gaji_pokok'], 0, ',', '.') ?></td>
                                <td>Rp<?= number_format($gaji['bonus'], 0, ',', '.') ?></td>
                                <td>Rp<?= number_format($gaji['insentif_kinerja'], 0, ',', '.') ?></td>
                                <td><strong>Rp<?= number_format($gaji['total_gaji'], 0, ',', '.') ?></strong></td>
                                <td>
                                    <span class="badge bg-success p-2 text-light">Dibayar</span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailGajiModal"
                                        data-periode="<?= date('F Y', strtotime($gaji['periode'] . '-01')) ?>"
                                        data-gaji_pokok="<?= number_format($gaji['gaji_pokok'], 0, ',', '.') ?>"
                                        data-bonus="<?= number_format($gaji['bonus'], 0, ',', '.') ?>"
                                        data-insentif="<?= number_format($gaji['insentif_kinerja'], 0, ',', '.') ?>"
                                        data-total="<?= number_format($gaji['total_gaji'], 0, ',', '.') ?>">
                                        Lihat
                                    </button>

                                    <a href="<?= base_url('LaporanController/cetak/' . $gaji['id']) ?>" class="btn btn-sm btn-outline-secondary" target="_blank">Cetak</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">Belum ada data gaji untuk tahun ini.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="detailGajiModal" tabindex="-1" aria-labelledby="detailGajiModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="detailGajiModalLabel">Detail Gaji</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Periode:</strong> <span id="modal-periode"></span></p>
        <p><strong>Gaji Pokok:</strong> Rp<span id="modal-gaji-pokok"></span></p>
        <p><strong>Bonus:</strong> Rp<span id="modal-bonus"></span></p>
        <p><strong>Insentif Kinerja:</strong> Rp<span id="modal-insentif"></span></p>
        <hr>
        <h5>Total Diterima: Rp<span id="modal-total"></span></h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('detailGajiModal');
    modal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;

        var periode = button.getAttribute('data-periode');
        var gajiPokok = button.getAttribute('data-gaji_pokok');
        var bonus = button.getAttribute('data-bonus');
        var insentif = button.getAttribute('data-insentif');
        var total = button.getAttribute('data-total');

        modal.querySelector('#modal-periode').textContent = periode;
        modal.querySelector('#modal-gaji-pokok').textContent = gajiPokok;
        modal.querySelector('#modal-bonus').textContent = bonus;
        modal.querySelector('#modal-insentif').textContent = insentif;
        modal.querySelector('#modal-total').textContent = total;
    });
});
</script>

<?= $this->endSection() ?>