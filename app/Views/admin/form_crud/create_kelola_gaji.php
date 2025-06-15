<?= $this->extend('layout/templateOtherPages') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-success text-white d-flex align-items-center">
                    <i class="bi bi-cash-stack me-2 fs-4"></i>
                    <h5 class="mb-0"> Tambah Data Gaji</h5>
                </div>

                <div class="card-body px-4 py-4">
                    <form action="<?= base_url('KelolaGajiController/store') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <label for="user_id" class="form-label fw-bold">Nama Karyawan</label>
                            <select class="form-select" id="user_id" name="user_id" style="width: 100%" required></select>
                        </div>

                        <div class="mb-4">
                            <label for="periode" class="form-label fw-bold">Periode Gaji</label>
                            <input type="month" name="periode" id="periode" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="gaji_pokok" class="form-label fw-bold">Gaji Pokok</label>
                                <input type="text" id="gaji_pokokFormatted" class="form-control format-rupiah" placeholder="Masukkan gaji pokok" required>
                                <input type="hidden" name="gaji_pokok" id="gaji_pokok" required>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="bonus" class="form-label fw-bold">Bonus</label>
                                <input type="text" id="bonusFormatted" class="form-control format-rupiah" placeholder="Masukkan bonus" required>
                                <input type="hidden" name="bonus" id="bonus"  min="0" value="0">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="insentif_kinerja" class="form-label fw-bold">Insentif Kinerja</label>
                            <input type="text" id="insentif_kinerjaFormatted" class="form-control format-rupiah" placeholder="Masukkan Insentif Kerja" required>
                            <input type="hidden" name="insentif_kinerja" id="insentif_kinerja" min="0" value="0">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="javascript:history.back()" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save me-1"></i> Simpan Gaji
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        $('#user_id').select2({
            placeholder: 'Cari karyawan...',
            allowClear: true,
            minimumInputLength: 1,
            ajax: {
                url: '<?= base_url('KelolaGajiController/cari_karyawan') ?>',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        term: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const fields = ['gaji_pokok', 'bonus', 'insentif_kinerja'];

        fields.forEach(function(field) {
            const formattedInput = document.getElementById(field + 'Formatted');
            const hiddenInput = document.getElementById(field);

            if (!formattedInput || !hiddenInput) return;

            formattedInput.addEventListener('input', function() {
                let raw = this.value.replace(/[^0-9]/g, '');

                // Kosongkan bila tidak ada angka
                if (!raw) {
                    hiddenInput.value = '';
                    this.value = '';
                    return;
                }

                // Hilangkan nol di depan
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