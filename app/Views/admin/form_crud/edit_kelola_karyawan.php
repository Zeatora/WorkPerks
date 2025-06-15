<?= $this->extend('layout/templateOtherPages') ?>
<?= $this->section('content') ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<?php
$session = session();
$user = $session->get('DataUser');
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-primary"><i class="bi bi-pencil-square me-2"></i> <?php echo $title; ?> <?php echo $id; ?></h4>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('KelolaKaryawanController/update/' . $karyawan['id']) ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" id="username"
                                        value="<?= esc($karyawan['username']) ?>" required>
                                    <div id="username-feedback" class="form-text"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" class="form-control" id="nama_lengkap"
                                        value="<?= esc($karyawan['nama_lengkap']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" id="email"
                                        value="<?= esc($karyawan['email']) ?>" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="departemen_id" class="form-label fw-semibold">
                                            <i class="bi bi-building me-1"></i> Departemen
                                        </label>
                                        <select name="departemen_id" id="departemen_id" class="form-select" style="width: 100%;" required></select>
                                        <small class="text-muted">Pilih divisi tempat karyawan bekerja</small>
                                    </div>


                                    <?php if ($user['role'] === 'admin') { ?>
                                        <div class="col-md-4 mb-3">
                                            <label for="role" class="form-label fw-semibold">
                                                <i class="bi bi-person-badge-fill me-1"></i> Role Akun
                                            </label>
                                            <select name="role" id="role" class="form-select" required>
                                                <option value="karyawan" <?= $karyawan['role'] == 'karyawan' ? 'selected' : '' ?>>Karyawan</option>
                                                <option value="admin" <?= $karyawan['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                                <?php if ($user['role'] === 'super_admin') { ?>   
                                                    <option value="super_admin" <?= $karyawan['role'] == 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
                                                <?php } ?>
                                            </select>
                                            <br>
                                            <small class="text-muted">Tentukan akses dan hak pengguna</small>
                                        </div>

                                        <!-- Status -->
                                        <div class="col-md-4 mb-3">
                                            <label for="status" class="form-label fw-semibold">
                                                <i class="bi bi-shield-check me-1"></i> Status Akun
                                            </label>

                                            <select name="status" id="status" class="form-select" required>
                                                <option value="active" <?= $karyawan['status'] == 'active' ? 'selected' : '' ?>>Aktif</option>
                                                <option value="inactive" <?= $karyawan['status'] == 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                                            </select>
                                            <br>
                                            <small class="text-muted">Aktifkan atau nonaktifkan akun karyawan</small>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="col-md-4 text-center">
                                <label class="form-label">Foto Profil</label>
                                <div class="mb-3">
                                    <img
                                        id="preview"
                                        src="<?= base_url(file_exists(FCPATH . $profilePath) ? $profilePath : 'uploads/foto/default_profile.jpg') ?>"
                                        alt="Foto Profil"
                                        class="img-thumbnail rounded-circle shadow-sm"
                                        width="150"
                                        height="150"
                                        onerror="this.src='<?= base_url('uploads/profile/default_profile.jpg') ?>'">

                                </div>
                                <input type="file" name="url_profile" class="form-control" accept="image/*" onchange="previewFoto(event)">
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
    $(document).ready(function() {
        $('#departemen_id').select2({
            placeholder: '-- Pilih Departemen --',
            allowClear: true,
            ajax: {
                url: "<?= base_url('KelolaKaryawanController/search_departemen') ?>",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        term: params.term
                    };
                },
                processResults: function(data) {
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

        const selectedDepartemenId = "<?= $karyawan['departemen_id'] ?>";
        const selectedDepartemenText = "<?= esc($karyawan['nama_departemen']) ?>";

        if (selectedDepartemenId && selectedDepartemenText) {
            const option = new Option(selectedDepartemenText, selectedDepartemenId, true, true);
            $('#departemen_id').append(option).trigger('change');
        }

    });

    function previewFoto(event) {
        const [file] = event.target.files;
        if (file) {
            document.getElementById('preview').src = URL.createObjectURL(file);
        }
    }

    document.getElementById('username').addEventListener('input', function() {
        const username = this.value;
        const id = "<?= $karyawan['id'] ?>";
        const feedback = document.getElementById('username-feedback');
        if (username.length < 3) {
            feedback.textContent = "Username harus minimal 3 karakter.";
            feedback.classList.add('text-danger');
            return;
        }
        fetch("<?= base_url('KelolaKaryawanController/CheckUsername') ?>", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": "<?= csrf_hash() ?>"
                },
                body: `username=${encodeURIComponent(username)}&id=${encodeURIComponent(id)}`
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
</script>

<?= $this->endSection() ?>