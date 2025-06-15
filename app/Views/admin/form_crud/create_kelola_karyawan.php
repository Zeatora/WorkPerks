<?= $this->extend('layout/templateOtherPages') ?>
<?= $this->section('content') ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-primary"><i class="bi bi-pencil-square me-2"></i> <?php echo $title; ?></h4>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('KelolaKaryawanController/store') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" id="username"
                                        placeholder="Masukan username!" required>
                                    <div id="username-feedback" class="form-text"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" class="form-control" id="nama_lengkap"
                                        placeholder="Masukan nama lengkap!" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="email"
                                        placeholder="Masukan email!" required>
                                    <div id="email-feedback" class="form-text"></div>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="text" name="password" class="form-control" id="password"
                                        placeholder="Masukan password!" required>
                                </div>

                                <div class="row">
                                    <!-- Departemen -->
                                    <div class="col-md-4 mb-3">
                                        <label for="departemen" class="form-label fw-semibold">Departemen</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-building"></i></span>
                                            <select name="departemen_id" id="departemen_id" class="form-select" style="width: 100%" required></select>
                                        </div>
                                        <small class="text-muted">Pilih divisi tempat karyawan bekerja</small>
                                    </div>

                                    <!-- Role -->
                                    <div class="col-md-4 mb-3">
                                        <label for="role" class="form-label fw-semibold">Role Akun</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-person-badge-fill"></i></span>
                                            <select name="role" id="role" class="form-select" required>
                                                <option value="">-- Pilih --</option>
                                                <option value="karyawan">Karyawan</option>
                                            </select>
                                        </div>
                                        <small class="text-muted">Tentukan akses dan hak pengguna</small>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">

                            <a href="javascript:history.back()" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.getElementById('username').addEventListener('input', function() {
        const username = this.value;
        const feedback = document.getElementById('username-feedback');
        if (username.length < 3) {
            feedback.textContent = "Username harus minimal 3 karakter.";
            feedback.classList.add('text-danger');
            return;
        }
        fetch("<?= base_url('KelolaKaryawanController/CheckUsernameCreate') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": "<?= csrf_hash() ?>"
                },
                body: `username=${encodeURIComponent(username)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    feedback.textContent = "Username sudah digunakan.";
                    feedback.classList.add('text-danger');
                    feedback.classList.remove('text-success');
                } else {
                    feedback.textContent = "Username tersedia.";
                    feedback.classList.add('text-success');
                    feedback.classList.remove('text-danger');
                }


            });
    });

    document.getElementById('email').addEventListener('input', function() {
        const email = this.value;
        const feedback = document.getElementById('email-feedback');

        function validateEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        if (!validateEmail(email)) {
            feedback.textContent = "Format email tidak valid.";
            feedback.classList.add('text-danger');
            feedback.classList.remove('text-success');
            return;
        }
        fetch("<?= base_url('KelolaKaryawanController/CheckEmailCreate') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": "<?= csrf_hash() ?>"
                },
                body: `email=${encodeURIComponent(email)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.EmailExists) {
                    feedback.textContent = "Email sudah digunakan.";
                    feedback.classList.add('text-danger');
                    feedback.classList.remove('text-success');
                } else if (!data.EmailExists) {
                    feedback.textContent = "Email tersedia.";
                    feedback.classList.add('text-success');
                    feedback.classList.remove('text-danger');
                }
            });
    });

    $(document).ready(function () {
    $('#departemen_id').select2({
        placeholder: '-- Pilih Departemen --',
        allowClear: true,
        ajax: {
            url: "<?= base_url('KelolaKaryawanController/search_departemen') ?>",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    term: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.map(dep => ({
                        id: dep.id,
                        text: dep.nama_departemen
                    }))
                };
            },
            cache: true
        }
    });
});
</script>

<?= $this->endSection() ?>