<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>

<div class="container mt-10">
    <div class="row">
        <div class="col pt-5">
            <div class="mb-3">
                <form action="/pages/dashboard/update/<?= $user['id'] ?>" method="post">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?= $user['email'] ?>">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" id="name" value="<?= $user['name'] ?>">
                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection('') ?>