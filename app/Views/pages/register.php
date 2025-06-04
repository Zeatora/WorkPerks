
<?= $this->extend('layout/templateOtherPages') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-center align-items-center" style="height: 80vh; overflow: hidden;">
    <div class="card shadow-lg" style="width: 100%; max-width: 500px; border-radius: 15px; background-color: #2a2a2a; color: #fff;">
        <div class="card-body p-4">
            <h2 class="text-center mb-3" style="color:rgb(255, 255, 255);">Sign Up</h2>
            <p class="text-center text-muted mb-4" style="color: #ccc;">Buat akun kamu dan nikmati!</p>
            <form action="<?php echo base_url('AuthController/registerUser'); ?>" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label" style="color: #fff;">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukan Email" required style="background-color: #333; color: #fff; border: 1px solid #444;">
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label" style="color: #fff;">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Pilih Username" required style="background-color: #333; color: #fff; border: 1px solid #444;">
                </div>
                <div class="mb-3">
                    <label for="nama_lengkap" class="form-label" style="color: #fff;">Name</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Masukan nama lengkap kamu!" required style="background-color: #333; color: #fff; border: 1px solid #444;">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label" style="color: #fff;">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required style="background-color: #333; color: #fff; border: 1px solid #444;">
                        <span class="input-group-text bg-dark">
                            <i class="fa fa-eye" id="togglePassword" style="cursor: pointer; color: #ff0000;"></i>
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label" style="color: #fff;">Confirm Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required style="background-color: #333; color: #fff; border: 1px solid #444;">
                        <span class="input-group-text bg-dark">
                            <i class="fa fa-eye" id="toggleConfirmPassword" style="cursor: pointer; color: #ff0000;"></i>
                        </span>
                    </div>
                </div>
                <button type="submit" class="btn btn-danger w-100" style="background: linear-gradient(45deg, #ff0000, #cc0000); border: none;">Create Account</button>
            </form>
        </div>
    </div>
</div>

<script>
    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#password");

    const toggleConfirmPassword = document.querySelector("#toggleConfirmPassword");
    const confirmPassword = document.querySelector("#confirm_password");

    togglePassword.addEventListener("click", function() {
        const type = password.getAttribute("type") === "password" ? "text" : "password";
        password.setAttribute("type", type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    toggleConfirmPassword.addEventListener("click", function() {
        const type = confirmPassword.getAttribute("type") === "password" ? "text" : "password";
        confirmPassword.setAttribute("type", type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
</script>
<?= $this->endSection() ?>