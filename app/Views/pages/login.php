<?= $this->extend('layout/templateOtherPages') ?>



<?= $this->section('content') ?>
<div class="container">
    <div class="row">
        <div class="col">
            <h2>Login</h2>
            <form action="/function/login/loginAccount" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>