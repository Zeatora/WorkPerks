<?= $this->extend('layout/templateOtherPages') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-warning text-dark d-flex align-items-center">
                    <i class="bi bi-pencil-square me-2 fs-4"></i>
                    <h5 class="mb-0"> Edit Data Gaji</h5>
                </div>

                <div class="card-body px-4 py-4">
                    <form action="<?= base_url('KelolaGajiController/update/' . $gaji['id']) ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <label for="user_id" class="form-label fw-bold">Nama Karyawan</label>
                            <input type="text" class="form-control" value="<?= esc($gaji['nama_lengkap'] ?? '-') ?>" readonly>
                        </div>

                        <div class="mb-4">
                            <label for="periode" class="form-label fw-bold">Periode Gaji</label>
                            <input type="month" name="periode" id="periode" class="form-control" value="<?= esc(substr($gaji['periode'], 0, 7)) ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="gaji_pokok" class="form-label fw-bold">Gaji Pokok</label>
                                <input type="text" id="gaji_pokokFormatted" class="form-control format-rupiah" placeholder="Masukkan gaji pokok" value="<?= esc($gaji['gaji_pokok']) ?>" required>
                                <input type="hidden" name="gaji_pokok" id="gaji_pokok" value="<?= esc($gaji['gaji_pokok']) ?>" required min="0">
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="bonus" class="form-label fw-bold">Bonus</label>
                                <input type="text" id="bonusFormatted" class="form-control format-rupiah" value="<?= esc($gaji['bonus']) ?>">
                                <input type="hidden" name="bonus" id="bonus" value="<?= esc($gaji['bonus']) ?>" min="0">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="insentif_kinerja" class="form-label fw-bold">Insentif Kinerja</label>
                            <input type="text" id="insentif_kinerjaFormatted" class="form-control format-rupiah" value="<?= esc($gaji['insentif_kinerja']) ?>">
                            <input type="hidden" name="insentif_kinerja" id="insentif_kinerja" value="<?= esc($gaji['insentif_kinerja']) ?>" min="0">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="javascript:history.back()" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-warning text-white">
                                <i class="bi bi-save me-1"></i> Perbarui Gaji
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fields = ['gaji_pokok', 'bonus', 'insentif_kinerja'];

        fields.forEach(function(field) {
            const formattedInput = document.getElementById(field + 'Formatted');
            const hiddenInput = document.getElementById(field);

            if (!formattedInput || !hiddenInput) return;

            const currentVal = hiddenInput.value;
            if (currentVal && !isNaN(currentVal)) {
                formattedInput.value = formatRupiah(currentVal);
            }

            formattedInput.addEventListener('input', function() {
                let raw = this.value.replace(/[^0-9]/g, '');

                if (!raw) {
                    hiddenInput.value = '';
                    this.value = '';
                    return;
                }

                raw = raw.replace(/^0+/, '') || '0';

                hiddenInput.value = raw;
                this.value = formatRupiah(raw);
            });
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