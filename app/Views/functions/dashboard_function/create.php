<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="container mt-10">
    <div class="row">
        <div class="col pt-5">
            <div class="mb-3">
                <form action="/pages/dashboard/create/createFunction" method="post">
                    <label for="name" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Enter your username!" required>
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email!" required>
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter your name!" required>
                    <button type="submit" class="btn btn-primary mt-3">Create Data</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection('') ?>