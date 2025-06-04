<?= $this->extend('layout/templateOtherPages') ?>



<?= $this->section('content') ?>
<div class="d-flex justify-content-center align-items-center" style="height: 80vh; overflow: hidden;">
    <div class="card shadow-lg" style="width: 100%; max-width: 500px; border-radius: 15px; background-color: #2a2a2a; color: #fff;">
        <div class="card-body p-4">
            <h2 class="text-center mb-3" style="color:rgb(255, 255, 255);">Login</h2>
            <p class="text-center text-muted mb-4" style="color: #ccc;">Masuk ke akun kamu dan nikmati berbagai fiktur!</p>
            <form action="<?php echo base_url('/AuthController/loginUser'); ?>" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label" style="color: #fff;">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukan Email" required style="background-color: #333; color: #fff; border: 1px solid #444;">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label" style="color: #fff;">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required style="background-color: #333; color: #fff; border: 1px solid #444;">
                    </div>
                </div>
                <button type="submit" class="btn btn-danger w-100" style="background: linear-gradient(45deg, #ff0000, #cc0000); border: none;">Masuk</button>
            </form>
        </div>
    </div>
</div>

<script>
    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#password");

    togglePassword.addEventListener("click", function() {
        const type = password.getAttribute("type") === "password" ? "text" : "password";
        password.setAttribute("type", type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

</script>
<?= $this->endSection() ?>